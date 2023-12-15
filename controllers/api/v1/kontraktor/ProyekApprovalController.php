<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\api\v1\kontraktor;

use yii\web\HttpException;
use Yii;

/**
 * ProyekCctvController implements the CRUD actions for ProyekCctv model.
 **/
class ProyekApprovalController extends \app\controllers\api\v1\kontraktor\BaseController
{
    use \app\components\UploadFile;
    public $modelClass = 'app\models\ApprovalSebelumPekerjaan';

    const ALLOWED_ROLES = [
        \app\components\Constant::ROLE_TUKANG_SAMEDAY,
        \app\components\Constant::ROLE_KONTRAKTOR,
    ];

    /**
     * @inheritdoc
     */
    function verbs()
    {
        $parent = parent::verbs();
        $parent['dilakukan-revisi'] = ['POST'];
        return $parent;
    }


    public function actionSearchProgress($id_project)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $query = \app\models\ProyekKemajuan::searchForApproval($project->id, $_GET);
        return $query;
    }

    /**
     * actionIndex
     */
    public function actionIndex($id_project)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $query = $this->modelClass::showAtIndex($project->id);

        return $this->dataProvider($query);
    }

    /**
     * actionView
     */
    public function actionView($id_project, $id)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $model = $this->findModel($id);

        if ($model->id_proyek != $project->id) {
            throw new HttpException(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }

        return $model;
    }


    /**
     * Creates a new ApprovalSebelumPekerjaan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_project)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $model = new $this->modelClass;
        $model->scenario = $model::SCENARIO_CREATE;

        try {
            if ($model->load($_POST, '')) :
                $model->id_proyek = $project->id;
                // upload image
                $image = \yii\web\UploadedFile::getInstanceByName('foto_material');
                if ($image == null) {
                    throw new HttpException(400, 'Foto material harus diisi.');
                } else {
                    $response = $this->uploadFile($image, $model->getUploadedPath());
                    if ($response->success == false) {
                        throw new HttpException(419, $response->message);
                    }

                    $model->foto_material = $response->filename;
                }

                $model->nama_progress = $model->generateNamaProgress();
                $model->status = $this->modelClass::STATUS_PENDING;

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
                    return ['success' => true, 'message' => 'Data berhasil ditambahkan.'];
                endif;
                throw new HttpException(422, \app\components\Constant::flattenError(
                    $model->getErrors()
                ));
            endif;
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? 'Terjadi kesalahan saat memproses data.');
        }
        throw new HttpException(400, 'Data tidak lengkap.');
    }

    /**
     * Updates an existing SuratBeritaAcaraPemasanganAlat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id_project, $id)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $model = $this->findModel([
            'id_proyek' => $project->id,
            'id' => $id
        ]);
        $model->scenario = $model::SCENARIO_UPDATE;
        $old_foto = $model->foto_material;

        try {
            if ($model->load($_POST, '')) :
                // upload image
                $image = \yii\web\UploadedFile::getInstanceByName('foto_material');
                if ($image == null) {
                    $model->foto_material = $old_foto;
                } else {
                    $response = $this->uploadFile($image, $model->getUploadedPath());
                    if ($response->success == false) {
                        throw new HttpException(419, $response->message);
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

                    return ['success' => true, 'message' => 'Data berhasil diperbaharui.'];
                endif;
                throw new HttpException(422, 'Data tidak valid.');
            endif;
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? 'Terjadi kesalahan saat memproses data.');
        }

        throw new HttpException(400, 'Data tidak lengkap.');
    }

    /**
     * Deletes an existing SuratBeritaAcaraPemasanganAlat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id_project, $id)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = $this->findModel([
                'id_proyek' => $project->id,
                'id' => $id,
                'flag' => 1,
            ]);
            if ($model->status != \app\models\ApprovalSebelumPekerjaan::STATUS_PENDING) {
                throw new \yii\web\HttpException(403, 'Data tidak dapat dihapus, karena status tidak sesuai');
            }
            $model->flag = 0;
            $model->save();

            $transaction->commit();

            return ['success' => true, 'message' => 'Data berhasil dihapus.'];
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? 'Terjadi kesalahan saat memproses data.');
        }

        throw new HttpException(400, 'Data tidak lengkap.');
    }


    /**
     * action-id: dilakukan-revisi
     * action-desc: action untuk melakukan revisi data
     * @param integer $id_project
     * @param integer $id
     * @return mixed
     */
    public function actionDilakukanRevisi($id_project, $id)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $model = $this->findModel([
            "id" => $id,
            "id_proyek" => $project->id,
            "flag" => 1,
        ]);

        $old_foto = $model->foto_material;
        if ($model->status != \app\models\ApprovalSebelumPekerjaan::STATUS_REJECTED && $model->status != \app\models\ApprovalSebelumPekerjaan::STATUS_PENDING) {
            throw new \yii\web\HttpException(400, 'Data tidak dapat diubah, karena status tidak sesuai');
        }

        try {
            if ($model->load($_POST, '')) {
                // upload image
                $image = \yii\web\UploadedFile::getInstanceByName('foto_material');
                if ($image == null) {
                    $model->foto_material = $old_foto;
                } else {
                    $response = $this->uploadFile($image, $model->getUploadedPath());
                    if ($response->success == false) {
                        throw new HttpException(419, $response->message);
                    }

                    $model->foto_material = $response->filename;
                }

                $model->revisi = null;
                if ($model->validate()) {
                    $model->status = \app\models\ApprovalSebelumPekerjaan::STATUS_PENDING;
                    $model->save();

                    \app\components\Notif::log(
                        $model->proyek->id_user,
                        'Revisi Approval Proyek ' . $model->proyek->nama_proyek,
                        'Approval Proyek ' . $model->proyek->nama_proyek . ' telah diperbaharui',
                        [
                            'controller' => '/home/proyek-saya/detail-proyek',
                            'android_route' => 'app-proyek-detail',
                            'params' => [
                                'id' => $model->proyek->kode_unik
                            ],
                        ]
                    );

                    \app\models\LogApproval::copy($model, "Dilakukan revisi");

                    return ['success' => true, 'message' => 'Data berhasil diperbaharui.'];
                }

                throw new HttpException(422, \app\components\Constant::flattenError($model->errors));
            }
        } catch (\Throwable $th) {
            throw new HttpException($th->statusCode ?? 500, $th->getMessage() ?? 'Terjadi kesalahan saat memproses data.');
        }

        throw new HttpException(400, 'Data tidak lengkap.');
    }
}
