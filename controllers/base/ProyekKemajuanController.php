<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\base;

use app\components\Constant;
use app\models\ProyekKemajuan;
use app\models\search\ProyekKemajuanSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;
use Yii;

/**
 * ProyekKemajuanController implements the CRUD actions for ProyekKemajuan model.
 **/
class ProyekKemajuanController extends \app\components\productive\DefaultActiveController
{

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     **/
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return \app\models\Action::getAccess($this->id, true, "id_project");
    }


    /**
     * Lists all ProyekKemajuan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new ProyekKemajuanSearch;
        $dataProvider = $searchModel->search($_GET);

        Tabs::clearLocalStorage();

        Url::remember();
        \Yii::$app->session['__crudReturnUrl'] = null;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Creates a new ProyekKemajuan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProyekKemajuan;
        $model->scenario = $model::SCENARIO_CREATE;

        try {
            if ($model->load($_POST)) :

                if ($model->validate()) :
                    if ($model->id_satuan || $model->volume || $model->bobot) {
                        if (($model->id_satuan && $model->volume && $model->bobot) == false) {
                            toastError("Untuk membuat item progress, Perlu menambahkan Satuan, Volume, dan Bobot");
                            goto end;
                        }

                        $model->nilai_biaya = $model->proyek->nilai_kontrak - ($model->proyek->nilai_kontrak / $model->bobot);
                    }
                    if ($model->proyek->getTotalBobot() > 100) {
                        toastError("Bobot telah melebihi 100 %");
                        goto end;
                    }
                    $model->save();
                    $this->messageCreateSuccess();
                    return $this->redirect(['/proyek/view', 'id' => $model->id_proyek]);
                endif;
                $this->messageValidationFailed();
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->render('create', $model->render());
    }

    /**
     * Updates an existing SuratBeritaAcaraPemasanganAlat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = $model::SCENARIO_UPDATE;

        try {
            if ($model->load($_POST)) :
                if ($model->validate()) :
                    if ($model->id_satuan || $model->volume || $model->bobot) {
                        if (($model->id_satuan && $model->volume && $model->bobot) == false) {
                            toastError("Untuk membuat item progress, Perlu menambahkan Satuan, Volume, dan Bobot");
                            goto end;
                        }

                        $model->nilai_biaya = $model->proyek->nilai_kontrak - ($model->proyek->nilai_kontrak / $model->bobot);
                    }

                    if ($model->proyek->getTotalBobot() > 100) {
                        toastError("Bobot telah melebihi 100 %");
                        goto end;
                    }

                    $model->save();
                    $this->messageUpdateSuccess();
                    return $this->redirect(['/proyek/view', 'id' => $model->id_proyek]);
                endif;
                $this->messageValidationFailed();
            endif;
            goto end;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->render('update', $model->render());
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->scenario = $model::SCENARIO_DELETE;

        try {
            if ($model->validate()) :
                $model->flag = 0;
                $model->deleted_at = date("Y-m-d H:i:s");
                $model->deleted_by = Yii::$app->user->id;
                $model->save();
                $this->messageDeleteSuccess();
                return $this->redirect(['/proyek/view', 'id' => $model->id_proyek]);
            endif;
            $this->messageValidationFailed();
            goto end;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->render('update', $model->render());
    }

    /**
     * Deletes an existing SuratBeritaAcaraPemasanganAlat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeletePermanent($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = $this->findModel($id);
            $model->deleted_at = date("Y-m-d H:i:s");
            $model->deleted_by = Constant::getUser()->id;
            $model->save();

            $transaction->commit();
            $this->messageDeleteSuccess();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            \Yii::$app->getSession()->addFlash('error', $msg);
            return $this->redirect(Url::previous());
        }

        // TODO: improve detection
        $isPivot = strstr('$id', ',');
        if ($isPivot == true) :
            return $this->redirect(Url::previous());
        elseif (isset(\Yii::$app->session['__crudReturnUrl']) && \Yii::$app->session['__crudReturnUrl'] != '/') :
            Url::remember(null);
            $url = \Yii::$app->session['__crudReturnUrl'];
            \Yii::$app->session['__crudReturnUrl'] = null;

            return $this->redirect($url);
        else :
            return $this->redirect(['index']);
        endif;
    }

    /**
     * Finds the ProyekKemajuan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProyekKemajuan the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProyekKemajuan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
