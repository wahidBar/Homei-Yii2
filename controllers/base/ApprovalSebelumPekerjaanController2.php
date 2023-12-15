<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\base;

use app\models\ApprovalSebelumPekerjaan;
use app\models\search\ApprovalSebelumPekerjaanSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use app\components\annex\Tabs;
use Yii;

/**
 * ApprovalSebelumPekerjaanController implements the CRUD actions for ApprovalSebelumPekerjaan model.
 **/
class ApprovalSebelumPekerjaanController extends \app\components\productive\DefaultActiveController
{
    // trait upload file
    use \app\components\UploadFile;

    public function behaviors()
    {
        return \app\models\Action::getAccess($this->id, true, 'id_project');
    }

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     **/
    public $enableCsrfValidation = false;


    /**
     * Lists all ApprovalSebelumPekerjaan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new ApprovalSebelumPekerjaanSearch;
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
     * Creates a new ApprovalSebelumPekerjaan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ApprovalSebelumPekerjaan;
        $model->scenario = $model::SCENARIO_CREATE;

        try {
            if ($model->load($_POST)) :
                // upload image
                $image = \yii\web\UploadedFile::getInstance($model, 'foto_material');
                if ($image == null) {
                    toastError("Foto Material tidak boleh kosong");
                    goto end;
                } else {
                    $response = $this->uploadFile($image, $model->getUploadedPath());
                    if ($response->success == false) {
                        toastError("Foto Material gagal diupload");
                        goto end;
                    }

                    $model->foto_material = $response->filename;
                }

                $model->nama_progress = $model->generateNamaProgress();
                $model->status = ApprovalSebelumPekerjaan::STATUS_PENDING;

                if ($model->validate()) :
                    $model->save();

                    \app\components\Notif::log(
                        $model->proyek->id_user,
                        'Approval Proyek ' . $model->proyek->nama_proyek,
                        'Approval Proyek ' . $model->proyek->nama_proyek . ' baru telah ditambahkan',
                        [
                            'controller' => '/home/proyek-saya/detail-proyek',
                            'android_route' => 'app-proyek-view',
                            'params' => [
                                'id' => $model->proyek->kode_unik
                            ],
                        ]
                    );

                    \app\models\LogApproval::copy($model, "Pembuatan Data Approval Sebelum Pekerjaan");
                    $this->messageCreateSuccess();
                    return $this->redirect(['view', 'id' => $model->id, 'id_project' => $model->id_proyek]);
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
        $old_foto = $model->foto_material;

        try {
            if ($model->load($_POST)) :
                // upload image
                $image = \yii\web\UploadedFile::getInstance($model, 'foto_material');
                if ($image == null) {
                    $model->foto_material = $old_foto;
                } else {
                    $response = $this->uploadFile($image, $model->getUploadedPath());
                    if ($response->success == false) {
                        toastError("Foto Material gagal diupload");
                        goto end;
                    }

                    $model->foto_material = $response->filename;
                }

                $model->nama_progress = $model->generateNamaProgress();


                if ($model->validate()) :
                    $model->save();

                    \app\components\Notif::log(
                        $model->proyek->id_user,
                        ' Approval Proyek ' . $model->proyek->nama_proyek,
                        'Approval Proyek ' . $model->proyek->nama_proyek . ' telah diperbaharui',
                        [
                            'controller' => '/home/proyek-saya/detail-proyek',
                            'android_route' => 'app-proyek-view',
                            'params' => [
                                'id' => $model->proyek->kode_unik
                            ],
                        ]
                    );
                    \app\models\LogApproval::copy($model, "Perubahan Data Approval Sebelum Pekerjaan");
                    $this->messageUpdateSuccess();
                    return $this->redirect(['view', 'id' => $model->id, 'id_project' => $model->id_proyek]);
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
            if ($model->status != \app\models\ApprovalSebelumPekerjaan::STATUS_PENDING) {
                throw new \yii\web\HttpException(403, 'Data tidak dapat dihapus, karena status tidak sesuai');
            }
            $id_proyek = $model->id_proyek;
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
            return $this->redirect(['proyek/view', 'id' => $id_proyek]);
        endif;
    }

    /**
     * Finds the ApprovalSebelumPekerjaan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ApprovalSebelumPekerjaan the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ApprovalSebelumPekerjaan::findOne(["id" => $id, "flag" => 1])) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
