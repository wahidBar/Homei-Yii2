<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\base;

use app\components\UploadFile;
use app\models\MasterKonsepDesain;
use app\models\search\MasterKonsepDesainSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\web\UploadedFile;

/**
 * MasterKonsepDesainController implements the CRUD actions for MasterKonsepDesain model.
 **/
class MasterKonsepDesainController extends \app\components\productive\DefaultActiveController
{

    use UploadFile;
    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     **/
    public $enableCsrfValidation = false;


    /**
     * Lists all MasterKonsepDesain models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new MasterKonsepDesainSearch;
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
     * Creates a new MasterKonsepDesain model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MasterKonsepDesain;
        $model->scenario = $model::SCENARIO_CREATE;

        try {
            if ($model->load($_POST)) :
                $file = UploadedFile::getInstance($model, "gambar");
                if ($file) {
                    $response = $this->uploadImage($file, "konsep_desain", $model::DEFAULT_FILE_VALIDATION);
                    if ($response->success == false) {
                        toastError("Gambar gagal diunggah");
                        goto end;
                    }
                    $model->gambar = $response->filename;
                } else {
                    toastError("Gambar tidak boleh kosong");
                    goto end;
                }

                if ($model->validate()) :
                    $model->save();
                    $this->messageCreateSuccess();
                    return $this->redirect(['index']);
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

    public function actionView($id)
    {
        throw new HttpException(404, "Page not found");
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
        $old = $model->gambar;
        try {
            if ($model->load($_POST)) :
                $file = UploadedFile::getInstance($model, "gambar");
                if ($file) {
                    $response = $this->uploadImage($file, "konsep_desain", $model::DEFAULT_FILE_VALIDATION);
                    if ($response->success == false) {
                        throw new HttpException(419, "Gambar gagal diunggah");
                    }
                    $model->gambar = $response->filename;
                    $this->deleteOne($old);
                } else {
                    $model->gambar = $old;
                }

                if ($model->validate()) :
                    $model->save();
                    $this->messageUpdateSuccess();
                    return $this->redirect(['index']);
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

            $model->flag = 0;
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
     * Finds the MasterKonsepDesain model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MasterKonsepDesain the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MasterKonsepDesain::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
