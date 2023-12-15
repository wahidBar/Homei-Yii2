<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\base;

use app\components\UploadFile;
use app\models\Model;
use app\models\TentangHomei;
use app\models\TentangHomeiDetail;
use app\models\TentangHomeiSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * TentangHomeiController implements the CRUD actions for TentangHomei model.
 **/
class TentangHomeiController extends \app\components\productive\DefaultActiveController
{
    use UploadFile;
    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     **/
    public $enableCsrfValidation = false;


    /**
     * Lists all TentangHomei models.
     * @return mixed
     */
    public function actionIndex()
    {
        $data = TentangHomei::find()->count();
        $data = TentangHomei::find()->all();
        if ($data == 0) {
            return $this->redirect(['create']);
        } else {
            return $this->redirect(['update', 'id' => $data[0]['id']]);
        }

        $searchModel  = new TentangHomeiSearch;
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
     * Creates a new TentangHomei model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TentangHomei;
        $modelDetail = $model->detailTentangHomei;
        if ($modelDetail == []) $modelDetail = [new TentangHomeiDetail()];
        $model->scenario = $model::SCENARIO_CREATE;
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->load($_POST)) :
                $file = UploadedFile::getInstance($model, "gambar");
                if ($file) {
                    $response = $this->uploadImage($file, "tentang_homei", $model::DEFAULT_FILE_VALIDATION);
                    if ($response->success == false) {
                        throw new HttpException(419, "Gambar gagal diunggah");
                    }
                    $model->gambar = $response->filename;
                } else {
                    toastError("Gambar tidak boleh kosong");
                    goto end;
                }
                if ($model->validate() == false) :
                    $transaction->rollBack();
                    toastError("Validasi gagal");
                    goto end;
                endif;
                $model->save();
                // detail
                $details = Model::createMultiple(TentangHomeiDetail::classname());
                Model::loadMultiple($details, Yii::$app->request->post());

                foreach ($details as $idx => $detail) {
                    $details[$idx]->id_tentang_homei = $model->id;
                }
                $validate = Model::validateMultiple($details);
                if ($model->validate() && $validate) :
                    $model->save();
                    foreach ($details as $idx => $detail) $detail->save();
                    $this->messageCreateSuccess();
                    $transaction->commit();
                    return $this->redirect(['update', 'id' => $model->id]);
                endif;
                $transaction->rollBack();
                $this->messageValidationFailed();
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $transaction->rollBack();
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        $model->setRender(['modelDetail' => $modelDetail]);
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
        $modelDetail = $model->detailTentangHomei;
        $oldBannerIDs = ArrayHelper::map($modelDetail, 'id', 'id');
        $oldBanner = [];
        // foreach ($modelDetail as $key => $value) 
        // $oldBanner[$key] = (object)["contoh_produk" => $value->gambar];

        if ($modelDetail == []) $modelDetail = [new TentangHomeiDetail()];
        $model->scenario = $model::SCENARIO_UPDATE;
        $old = $model->gambar;
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->load($_POST)) :
                $file = UploadedFile::getInstance($model, "gambar");
                if ($file) {
                    $response = $this->uploadImage($file, "tentang_homei");
                    if ($response->success == false) {
                        throw new HttpException(419, "Gambar gagal diunggah");
                    }
                    $model->gambar = $response->filename;
                    $this->deleteOne($old);
                } else {
                    $model->gambar = $old;
                }
                // banner
                $banners = Model::createMultiple(TentangHomeiDetail::classname(), $modelDetail);
                Model::loadMultiple($banners, Yii::$app->request->post());
                $deletedBannerIDs = array_diff($oldBannerIDs, array_filter(ArrayHelper::map($banners, 'id', 'id')));
                foreach ($banners as $idx => $banner) {
                    $banners[$idx]->id_tentang_homei = $model->id;
                }

                $validate = Model::validateMultiple($banners);

                if ($model->validate() && $validate) :
                    // banner
                    foreach ($banners as $idx => $banner) {
                        $banner->save();
                    }
                    $model->save();
                    $transaction->commit();
                    $this->messageUpdateSuccess();
                    return $this->redirect(['update', 'id' => $model->id]);
                endif;
                $this->messageValidationFailed();
                $transaction->rollBack();
            endif;
            goto end;
        } catch (\Exception $e) {
            $transaction->rollBack();
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        $model->setRender(['modelDetail' => $modelDetail]);
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
     * Finds the TentangHomei model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TentangHomei the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TentangHomei::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
