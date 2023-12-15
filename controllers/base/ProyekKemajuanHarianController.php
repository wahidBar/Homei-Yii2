<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\base;

use app\components\UploadFile;
use app\models\Proyek;
use app\models\ProyekGaleri;
use app\models\ProyekKemajuan;
use app\models\ProyekKemajuanHarian;
use app\models\search\ProyekKemajuanHarianSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\web\UploadedFile;

/**
 * ProyekKemajuanHarianController implements the CRUD actions for ProyekKemajuanHarian model.
 **/
class ProyekKemajuanHarianController extends \app\components\productive\DefaultActiveController
{
    use UploadFile;

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
     * Lists all ProyekKemajuanHarian models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new ProyekKemajuanHarianSearch;
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
     * Creates a new ProyekKemajuanHarian model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        //set timezone to jakarta   
        $id_proyek_kemajuan = intval($_GET['ProyekKemajuanHarian']['id_proyek_kemajuan']);
        $proyek_kemajuan = ProyekKemajuan::findOne(['id' => $id_proyek_kemajuan]);
        if ($proyek_kemajuan == false) throw new HttpException(404, "Data tidak ditemukan");
        $kemajuan_sampai_sekarang = floatval(ProyekKemajuanHarian::find()->where(['id_proyek_kemajuan' => $id_proyek_kemajuan])->select('sum(volume)')->column()[0]);
        $bobot_sampai_sekarang = floatval(ProyekKemajuanHarian::find()->where(['id_proyek_kemajuan' => $id_proyek_kemajuan])->select('sum(bobot)')->column()[0]);

        if ($kemajuan_sampai_sekarang >= $proyek_kemajuan->volume) {
            toastError("Volume kemajuan dari proyek ini telah mencapai volume yang ditetapkan.");
            return $this->redirect(['/proyek/view', 'id' => $proyek_kemajuan->id_proyek]);
        }

        $progress_exist = ProyekKemajuanHarian::find()->where(['id_proyek_kemajuan' => $id_proyek_kemajuan, 'tanggal' => date("Y-m-d")])->one();
        if ($progress_exist) {
            return $this->redirect(['proyek-kemajuan-harian/update', 'id' => $progress_exist->id]);
        }

        $model = new ProyekKemajuanHarian;
        $modelImage = new ProyekGaleri;
        $modelImage->scenario = ProyekGaleri::SCENARIO_CREATE_AT_PROGRESS;
        $model->scenario = $model::SCENARIO_CREATE;

        try {
            if ($model->load($_POST)) :
                $proyek = Proyek::findOne($model->proyekKemajuan->id_proyek);
                if ($proyek == null) {
                    toastError("Data Proyek tidak ditemukan");
                    goto end;
                }


                $tanggal1 = $model->tanggal;
                $tanggal2 = $proyek->tanggal_awal_kontrak;
                $tanggal3 = $proyek->tanggal_akhir_kontrak;

                if (strtotime($tanggal1) < strtotime($tanggal2)) {
                    toastError("Tanggal kemajuan tidak boleh lebih kecil dari tanggal mulai kontrak");
                    goto end;
                } elseif (strtotime($tanggal1) > strtotime($tanggal3)) {
                    toastError("Tanggal kemajuan tidak boleh lebih besar dari tanggal akhir kontrak");
                    goto end;
                }

                $model->id_proyek = $proyek->id;
                $proyek_kemajuan->volume_kemajuan = $kemajuan_sampai_sekarang;
                $proyek_kemajuan->volume_kemajuan += $model->volume;
                $proyek_kemajuan->bobot_kemajuan = $bobot_sampai_sekarang;
                $model->bobot = ($model->volume / $proyek_kemajuan->volume) * $proyek_kemajuan->bobot;
                $proyek_kemajuan->bobot_kemajuan += $model->bobot;

                $proyek_kemajuan->status_verifikasi = Yii::$app->request->post('ProyekKemajuan')['status_verifikasi'];

                if ($proyek_kemajuan->volume_kemajuan > $proyek_kemajuan->volume) {
                    toastError("Qty kemajuan tidak dapat lebih besar dari total Qty");
                    goto end;
                }

                //  load image if exist
                $image = UploadedFile::getInstance($modelImage, 'nama_file');
                $modelImage->id_proyek_kemajuan = $proyek_kemajuan->getGrandMasterParent();

                if ($image) {
                    $modelImage->load($_POST);
                    $modelImage->id_proyek = $proyek_kemajuan->id_proyek;
                    $response = $this->uploadImage($image, $modelImage->getUploadedPath());
                    if ($response->success == false) {
                        toastError($response->message);
                        goto end;
                    }

                    $modelImage->nama_file = $response->filename;
                    if ($modelImage->validate() == false) {
                        $this->messageValidationFailed(
                            \app\components\Constant::flattenError(
                                $modelImage->getErrors()
                            )
                        );
                        goto end;
                    }
                    $modelImage->save();
                }


                if ($model->validate()) :
                    $model->save();
                    $proyek_kemajuan->save();

                    $transaction->commit();
                    toastSuccess("Kemajuan Harian berhasil dibuat");
                    return $this->redirect(['/proyek/view', 'id' => $proyek_kemajuan->id_proyek]);
                else :
                    $this->messageValidationFailed();
                endif;
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        if ($transaction->getIsActive()) {
            $transaction->rollBack();
        }
        //refresh variable $proyek_kemajuan
        $proyek_kemajuan = ProyekKemajuan::findOne(['id' => $id_proyek_kemajuan]);
        $model->setRender(compact('proyek_kemajuan', 'modelImage'));
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
        $transaction = Yii::$app->db->beginTransaction();
        $modelImage = new ProyekGaleri;
        $modelImage->scenario = ProyekGaleri::SCENARIO_CREATE_AT_PROGRESS;
        $model = $this->findModel($id);
        $proyek_kemajuan = ProyekKemajuan::findOne(['id' => $model->id_proyek_kemajuan]);
        $old_volume = $model->volume;
        $old_bobot = $model->bobot;
        $model->scenario = $model::SCENARIO_UPDATE;
        $kemajuan_sampai_sekarang = floatval(ProyekKemajuanHarian::find()->where(['id_proyek_kemajuan' => $proyek_kemajuan->id])->select('sum(volume)')->column()[0]);
        $bobot_sampai_sekarang = floatval(ProyekKemajuanHarian::find()->where(['id_proyek_kemajuan' => $proyek_kemajuan->id])->select('sum(bobot)')->column()[0]);

        if ($kemajuan_sampai_sekarang >= $proyek_kemajuan->volume) {
            toastError("Volume kemajuan dari proyek ini telah mencapai volume yang ditetapkan.");
            return $this->redirect(['/proyek/view', 'id' => $proyek_kemajuan->id_proyek]);
        }

        try {
            if ($model->load($_POST)) :
                $proyek_kemajuan->volume_kemajuan = $kemajuan_sampai_sekarang;
                $proyek_kemajuan->volume_kemajuan += $model->volume - $old_volume;
                $proyek_kemajuan->bobot_kemajuan = $bobot_sampai_sekarang;
                $model->bobot = ($model->volume / $proyek_kemajuan->volume) * $proyek_kemajuan->bobot;
                $proyek_kemajuan->bobot_kemajuan += $model->bobot - $old_bobot;
                if ($proyek_kemajuan->volume < $proyek_kemajuan->volume_kemajuan) {
                    toastError("Qty kemajuan tidak dapat lebih besar dari total Qty");
                    goto end;
                }

                //  load image if exist
                $image = UploadedFile::getInstance($modelImage, 'nama_file');
                $modelImage->id_proyek_kemajuan = $proyek_kemajuan->getGrandMasterParent();

                if ($image) {
                    $modelImage->load($_POST);
                    $modelImage->id_proyek = $proyek_kemajuan->id_proyek;
                    $response = $this->uploadImage($image, $modelImage->getUploadedPath());
                    if ($response->success == false) {
                        toastError($response->message);
                        goto end;
                    }

                    $modelImage->nama_file = $response->filename;

                    if ($modelImage->validate() == false) {
                        $this->messageValidationFailed(
                            \app\components\Constant::flattenError(
                                $modelImage->getErrors()
                            )
                        );
                        goto end;
                    }

                    $modelImage->save();
                }

                if ($model->validate()) :
                    $model->save();
                    $proyek_kemajuan->save();
                    $transaction->commit();
                    $this->messageUpdateSuccess();
                    return $this->redirect(['/proyek/view', 'id' => $proyek_kemajuan->id_proyek]);
                else :
                    $this->messageValidationFailed();
                endif;
            endif;
            goto end;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        if ($transaction->getIsActive()) {
            $transaction->rollBack();
        }
        $proyek_kemajuan = ProyekKemajuan::findOne(['id' => $model->id_proyek_kemajuan]);
        $model->setRender(compact('proyek_kemajuan', 'modelImage'));
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
     * Finds the ProyekKemajuanHarian model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProyekKemajuanHarian the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProyekKemajuanHarian::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
