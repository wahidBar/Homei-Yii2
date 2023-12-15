<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\base;

use app\models\ProyekKemajuanTarget;
use app\models\search\ProyekKemajuanTarget as ProyekKemajuanTargetSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use app\components\annex\Tabs;
use Yii;

/**
 * ProyekKemajuanTargetController implements the CRUD actions for ProyekKemajuanTarget model.
 **/
class ProyekKemajuanTargetController extends \app\components\productive\DefaultActiveController
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
     * Lists all ProyekKemajuanTarget models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new ProyekKemajuanTargetSearch;
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
     * Creates a new ProyekKemajuanTarget model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProyekKemajuanTarget;
        $model->scenario = $model::SCENARIO_CREATE;

        try {
            if ($model->load($_POST)) :
                if ($model->validate()) :
                    $model->save();
                    $this->messageCreateSuccess();
                    return $this->redirect(['view', 'id' => $model->id]);
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
        $prev = ProyekKemajuanTarget::find()->where(['<', 'id', $id])->andWhere(['id_proyek' => $model->id_proyek])->orderBy('id desc')->one();
        $last = ProyekKemajuanTarget::find()->andWhere(['id_proyek' => $model->id_proyek])->orderBy('id desc')->one();
        $jumlah = ProyekKemajuanTarget::find()->andWhere(['id_proyek' => $model->id_proyek])->sum('nilai_target');
        
        $model->scenario = $model::SCENARIO_UPDATE_NILAI;

        try {
            if ($model->load($_POST)) :
                $nilai_target = $model->nilai_target;
                $jumlah_target = $model->jumlah_target;
                if ($jumlah_target == 0 || $jumlah_target == null) {
                    $jumlah_target = 0;
                }
                if ($model->nama_target == "Minggu-1") {
                    $model->jumlah_target = $model->nilai_target;
                } else {
                    $model->jumlah_target = $prev->jumlah_target + $model->nilai_target;
                }
                $total_target = $jumlah + $nilai_target;
                
                if ($total_target > 100) {
                    toastError("Tidak Boleh Lebih Dari 100%. Total Target : $jumlah %");
                    goto end;
                }
                if ($model->id == $last->id && $total_target < 100) {
                    toastError("Tidak Boleh Kurang Dari 100%. Total Target : $jumlah %");
                    goto end;
                }
                if ($model->validate()) :
                    $model->save();
                    $this->messageUpdateSuccess();
                    return $this->redirect(['proyek/view', 'id' => $model->id_proyek]);
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

    /**
     * Deletes an existing SuratBeritaAcaraPemasanganAlat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = $this->findModel($id);

            $model->delete();

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
     * Finds the ProyekKemajuanTarget model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProyekKemajuanTarget the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProyekKemajuanTarget::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
