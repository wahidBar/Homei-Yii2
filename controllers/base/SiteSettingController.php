<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\base;

use app\components\UploadFile;
use app\models\SiteSetting;
use app\models\search\SiteSetting as SiteSettingSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\web\UploadedFile;

/**
 * SiteSettingController implements the CRUD actions for SiteSetting model.
 **/
class SiteSettingController extends \app\components\productive\DefaultActiveController
{

    use UploadFile;
    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     **/
    public $enableCsrfValidation = false;


    /**
     * Lists all SiteSetting models.
     * @return mixed
     */
    public function actionIndex()
    {
        $setting = SiteSetting::find()->count();
        $setting = SiteSetting::find()->all();
        if ($setting == 0) {
            return $this->redirect(['create']);
        } else {
            return $this->redirect(['update', 'id' => $setting[0]['id']]);
        }
        $searchModel  = new SiteSettingSearch;
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
     * Creates a new SiteSetting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SiteSetting;
        $model->scenario = $model::SCENARIO_CREATE;

        $list_upload = ["logo", "logo_putih", "icon", "gambar_header", "gambar_login"];

        try {
            if ($model->load($_POST)) :
                foreach ($list_upload as $field_name) {
                    $file = UploadedFile::getInstance($model, $field_name);
                    if ($file == false) {
                        toastError("Gambar tidak boleh kosong");
                        goto end;
                    }
                }

                foreach ($list_upload as $field_name) {
                    $file = UploadedFile::getInstance($model, $field_name);
                    if ($file) {
                        $response = $this->uploadImage($file, "site");
                        if ($response->success == false) {
                            toastError("Gambar gagal diunggah");
                            goto end;
                        }
                        $model->$field_name = $response->filename;
                    }
                }

                $instance = UploadedFile::getInstance($model, "contoh_boq_proyek");
                $response = $this->uploadFile($instance, "contoh_boq_proyek");
                if ($response->success == false) {
                    toastError("Dokumen gagal diunggah");
                    goto end;
                }

                if ($model->validate()) :
                    $model->save();
                    $this->messageCreateSuccess();
                    return $this->redirect(['update', 'id' => $model->id]);
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
        $list_upload = ["logo", "logo_putih", "icon", "gambar_header", "gambar_login"];
        foreach ($list_upload as $field_name) {
            $varname_from_field = "old_$field_name";
            $$varname_from_field = $model->$field_name;
        }

        $old_boq = $model->contoh_boq_proyek;
        $old_excel = $model->contoh_import_excel;
        try {
            if ($model->load($_POST)) :
                foreach ($list_upload as $field_name) {
                    $varname_from_field = "old_$field_name";

                    $file = UploadedFile::getInstance($model, $field_name);
                    if ($file) {
                        $response = $this->uploadImage($file, "site");
                        if ($response->success == false) {
                            toastError("Gambar gagal diunggah");
                            goto end;
                        }

                        $model->$field_name = $response->filename;
                        $this->deleteOne($$varname_from_field);
                    } else {
                        $model->$field_name = $$varname_from_field;
                    }
                }

                $instance = UploadedFile::getInstance($model, "contoh_boq_proyek");
                if($instance)
                {
                    $response = $this->uploadFile($instance, "contoh_boq_proyek");
                    if ($response->success == false) {
                        toastError("Dokumen gagal diunggah");
                        goto end;
                    }
                    $model->contoh_boq_proyek = $response->filename;
                    $this->deleteOne($$varname_from_field);
                } else {
                    $model->contoh_boq_proyek = $old_boq;
                }

                $instance2 = UploadedFile::getInstance($model, "contoh_import_excel");
                if($instance2)
                {
                    $response = $this->uploadFile($instance2, "contoh_import_excel");
                    if ($response->success == false) {
                        toastError("Dokumen gagal diunggah");
                        goto end;
                    }
                    $model->contoh_import_excel = $response->filename;
                    $this->deleteOne($$varname_from_field);
                } else {
                    $model->contoh_import_excel = $old_excel;
                }

                if ($model->validate()) :
                    $model->save();
                    $this->messageUpdateSuccess();
                    return $this->redirect(['update', 'id' => $model->id]);
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
        throw new HttpException(404, "Action not exist");
        // $transaction = Yii::$app->db->beginTransaction();
        // try {
        //     $model = $this->findModel($id);

        //     $model->delete();

        //     $transaction->commit();
        //     $this->messageDeleteSuccess();
        // } catch (\Exception $e) {
        //     $transaction->rollBack();
        //     $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
        //     \Yii::$app->getSession()->addFlash('error', $msg);
        //     return $this->redirect(Url::previous());
        // }

        // // TODO: improve detection
        // $isPivot = strstr('$id', ',');
        // if ($isPivot == true) :
        //     return $this->redirect(Url::previous());
        // elseif (isset(\Yii::$app->session['__crudReturnUrl']) && \Yii::$app->session['__crudReturnUrl'] != '/') :
        //     Url::remember(null);
        //     $url = \Yii::$app->session['__crudReturnUrl'];
        //     \Yii::$app->session['__crudReturnUrl'] = null;

        //     return $this->redirect($url);
        // else :
        //     return $this->redirect(['index']);
        // endif;
    }

    /**
     * Finds the SiteSetting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SiteSetting the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SiteSetting::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
