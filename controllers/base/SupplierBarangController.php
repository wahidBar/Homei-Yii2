<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\base;

use app\components\UploadFile;
use app\models\SupplierBarang;
use app\models\search\SupplierBarangSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\web\UploadedFile;

/**
 * SupplierBarangController implements the CRUD actions for SupplierBarang model.
 **/
class SupplierBarangController extends \app\components\productive\DefaultActiveController
{
    use UploadFile;
    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     **/
    public $enableCsrfValidation = false;


    /**
     * Lists all SupplierBarang models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new SupplierBarangSearch;
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
     * Creates a new SupplierBarang model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SupplierBarang;
        $model->scenario = $model::SCENARIO_CREATE;

        try {
            if ($model->load($_POST)) :
                $file = UploadedFile::getInstance($model, "gambar");
                if ($file) {
                    $response = $this->uploadImage($file, "barang");
                    if ($response->success == false) {
                        throw new HttpException(419, "Gambar gagal diunggah");
                    }
                    $model->gambar = $response->filename;
                }
                $model->harga_ritel = str_replace(",", "", $model->harga_ritel);
                $model->harga_proyek = str_replace(",", "", $model->harga_proyek);
                if ($model->validate()) :
                    $params = $this->getParameter($model->material_id);
                    $input_params = [];
                    foreach ($params as $param) {
                        $input_params[$param] = Yii::$app->request->post("SupplierBarang")[$param]['params'];
                    }
                    $model->params = json_encode($input_params);

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
        $model->scenario = $model::SCENARIO_UPDATE;
        $old = $model->gambar;
        $params = $this->getParameter($model->material_id);
        try {
            if ($model->load($_POST)) :
                $file = UploadedFile::getInstance($model, "gambar");
                if ($file) {
                    $response = $this->uploadImage($file, "barang");
                    if ($response->success == false) {
                        throw new HttpException(419, "Gambar gagal diunggah");
                    }
                    $model->gambar = $response->filename;
                    $this->deleteOne($old);
                } else {
                    $model->gambar = $old;
                }
                $model->harga_ritel = str_replace(",", "", $model->harga_ritel);
                $model->harga_proyek = str_replace(",", "", $model->harga_proyek);
                $input_params = [];
                foreach ($params as $param) {
                    $input_params[$param] = Yii::$app->request->post("SupplierBarang")[$param]['params'];
                }
                $model->params = json_encode($input_params);
                if ($model->validate()) :
                    $model->save();
                    $this->messageUpdateSuccess();
                    return $this->redirect(['view', 'id' => $model->id]);
                endif;
                $this->messageValidationFailed();
            endif;
            goto end;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        $model->setRender([
            'params' => $params
        ]);
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
     * Finds the SupplierBarang model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SupplierBarang the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SupplierBarang::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
