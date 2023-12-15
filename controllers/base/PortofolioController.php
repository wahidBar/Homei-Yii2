<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\base;

use app\components\UploadFile;
use app\models\Model;
use app\models\Portofolio;
use app\models\PortofolioGambar;
use app\models\search\PortofolioSearch;
use app\models\PortolioGambarHasil;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\web\UploadedFile;

/**
 * PortofolioController implements the CRUD actions for Portofolio model.
 **/
class PortofolioController extends \app\components\productive\DefaultActiveController
{
    use UploadFile;
    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     **/
    public $enableCsrfValidation = false;


    /**
     * Lists all Portofolio models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new PortofolioSearch;
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
     * Creates a new Portofolio model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Portofolio;
        $modelGambar = $model->portofolioGambars;
        if ($modelGambar == []) $modelGambar = [new PortofolioGambar()];
        $model->scenario = $model::SCENARIO_CREATE;
        $transaction = Yii::$app->db->beginTransaction();
        $model->kode_unik = Yii::$app->security->generateRandomString(30);
        // $modelGH = new PortolioGambarHasil();
        try {
            if ($model->load($_POST)) :
                $model->slug = Inflector::slug($model->judul);
                $harga = str_replace(",","",$model->total_harga);
                $model->total_harga = $harga;
                $model->flag = 1;
                if ($model->validate() == false) :
                    $transaction->rollBack();
                    toastError("Validasi gagal");
                    goto end;
                endif;
                $model->save();
                // banner
                $banners = Model::createMultiple(PortofolioGambar::classname());
                Model::loadMultiple($banners, Yii::$app->request->post());

                foreach ($banners as $idx => $banner) {
                    $files = UploadedFile::getInstance($banner, "[$idx]gambar_design");
                    $date = date("Y-m-d");
                    $response = $this->uploadImage($files, "portofolio/$model->id");
                    if ($response->success == false) {
                        Yii::$app->session->setFlash("error", "Gambar tidak boleh kosong");
                        goto end;
                    }

                    $banners[$idx]->gambar_design = $response->filename;
                    $banners[$idx]->portofolio_id = $model->id;
                }
                $validate = Model::validateMultiple($banners);
                if ($model->validate() && $validate) :
                    $model->save();
                    foreach ($banners as $idx => $banner) $banner->save();
                    $this->messageCreateSuccess();
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
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
        $model->setRender(['modelGambar' => $modelGambar]);
        return $this->render(
            'create',
            $model->render()
            // ,$modelGH->render()
        );
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
        $modelGambar = $model->portofolioGambars;
        $oldBannerIDs = ArrayHelper::map($modelGambar, 'id', 'id');
        $oldBanner = [];
        foreach ($modelGambar as $key => $value) $oldBanner[$key] = (object)["gambar_design" => $value->gambar_design];
        if ($modelGambar == []) $modelGambar = [new PortofolioGambar()];
        $model->scenario = $model::SCENARIO_UPDATE;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load($_POST)) :
                if($model->kode_unik == null)
                {
                    $model->kode_unik = Yii::$app->security->generateRandomString(30);
                }
                $harga = str_replace(",","",$model->total_harga);
                // banner
                $banners = Model::createMultiple(PortofolioGambar::classname(), $modelGambar);
                Model::loadMultiple($banners, Yii::$app->request->post());
                $deletedBannerIDs = array_diff($oldBannerIDs, array_filter(ArrayHelper::map($banners, 'id', 'id')));
                foreach ($banners as $idx => $banner) {
                    $files = UploadedFile::getInstance($banner, "[$idx]gambar_design");
                    if ($files) {
                        $response = $this->uploadImage($files, "portofolio/$model->id");
                        if ($response->success == false) {
                            $transaction->rollBack();
                            toastError("Gambar tidak boleh kosong");
                            goto end;
                        }
                        $banners[$idx]->gambar_design = $response->filename;
                        $this->deleteOne($oldBanner[$idx]->gambar_design);
                    } else {
                        $banners[$idx]->gambar_design = $oldBanner[$idx]->gambar_design;
                    }
                    $banners[$idx]->portofolio_id = $model->id;
                }

                $validate = Model::validateMultiple($banners);

                if ($model->validate() && $validate) :
                    // banner
                    if (!empty($deletedBannerIDs)) {
                        $deletedData = PortofolioGambar::find()->andWhere(['id' => $deletedBannerIDs])->all();
                        // dd($deletedData);
                        // foreach ($deletedData as $_deleted) {
                        //     $this->deleteOne($_deleted->gambar_design);
                        // }

                        PortofolioGambar::deleteAll(['id' => $deletedBannerIDs]);
                    }

                    foreach ($banners as $idx => $banner) {
                        if ($banner->gambar_design != null) {
                            $banner->save();
                        }
                    }
                    $model->total_harga = $harga;
                    $model->save();
                    $transaction->commit();
                    $this->messageUpdateSuccess();
                    return $this->redirect(['view', 'id' => $model->id]);
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
        $model->setRender(['modelGambar' => $modelGambar]);
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
                return $this->redirect(['index']);
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
     * Finds the Portofolio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Portofolio the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Portofolio::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
