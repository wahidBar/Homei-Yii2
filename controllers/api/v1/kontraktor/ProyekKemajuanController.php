<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\api\v1\kontraktor;

use app\models\Proyek;
use app\models\ProyekGaleri;
use app\models\ProyekKemajuanHarian;
use yii\web\HttpException;
use Yii;
use yii\web\UploadedFile;

/**
 * ProyekCctvController implements the CRUD actions for ProyekCctv model.
 **/
class ProyekKemajuanController extends \app\controllers\api\v1\kontraktor\BaseController
{
    use \app\components\UploadFile;
    public $modelClass = 'app\models\ProyekKemajuan';

    const ALLOWED_ROLES = [
        \app\components\Constant::ROLE_TUKANG_SAMEDAY,
        \app\components\Constant::ROLE_KONTRAKTOR,
    ];


    /**
     * actionIndex
     */
    public function actionIndex($id_project, $nested = null)
    {
        $project = $this->hasAccessAtThisProject($id_project);

        $model = new \app\models\ProyekKemajuan;

        $model = $model::find()
            ->andWhere([
                'and',
                ['id_proyek' => $project->id],
                ['flag' => 1]
            ]);

        if ($nested) {
            $model->andWhere(['id_parent' => $nested]);
        }

        $model = $model
            ->select('id,id_parent,id_satuan,item,volume,bobot,volume_kemajuan,bobot_kemajuan,status_verifikasi,created_at,created_by')
            ->all();

        return ["success" => true, "data" => $model];
    }

    /**
     * actionView
     */
    public function actionView($id_project, $id)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $model = $this->findModel([
            "id" => $id,
            "id_proyek" => $project->id,
            "flag" => 1
        ]);

        if ($model->id_proyek != $project->id) {
            throw new HttpException(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }

        return ["success" => true, "data" => $model];
    }


    /**
     * Creates a new ProyekKemajuan model.
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
                if ($model->validate()) :
                    if ($model->id_satuan || $model->volume || $model->bobot) {
                        $model->bobot = ($model->nilai_biaya / $model->proyek->nilai_kontrak) * 100;

                        if (($model->id_satuan && $model->volume && $model->bobot) == false) {
                            throw new HttpException(400, 'Satuan, volume, dan bobot harus diisi.');
                        }
                    }
                    if ($model->proyek->getTotalBobot() > 100) {
                        throw new HttpException(400, 'Bobot telah melebihi 100 %');
                    }
                    $model->save();
                    return ['success' => true, 'message' => 'Berhasil menambahkan data progress.'];
                endif;
                throw new HttpException(400, 'Data tidak valid.');
            endif;
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? 'Terjadi kesalahan saat membuat data progress.');
        }

        throw new HttpException(400, 'Data tidak valid.');
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
            'id' => $id,
        ]);
        $model->scenario = $model::SCENARIO_UPDATE;

        try {
            if ($model->load($_POST, '')) :
                if ($model->validate()) :
                    if ($model->id_satuan || $model->volume || $model->bobot) {
                        $model->bobot = ($model->nilai_biaya / $model->proyek->nilai_kontrak) * 100;
                        if (($model->id_satuan && $model->volume && $model->bobot) == false) {
                            throw new HttpException(400, 'Satuan, volume, dan bobot harus diisi.');
                        }
                    }

                    if ($model->proyek->getTotalBobot() > 100) {
                        throw new HttpException(400, 'Bobot telah melebihi 100 %');
                    }

                    $model->save();
                    return ['success' => true, 'message' => 'Berhasil mengubah data progress.'];
                endif;
                throw new HttpException(400, 'Data tidak valid.');
            endif;
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? 'Terjadi kesalahan saat mengubah data progress.');
        }

        throw new HttpException(400, 'Data belum lengkap.');
    }

    public function actionDelete($id_project, $id)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $model = $this->findModel([
            'id_proyek' => $project->id,
            'id' => $id,
        ]);
        $model->scenario = $model::SCENARIO_DELETE;

        try {
            if ($model->validate()) :
                if ($model->getChildren()->count() == 0) {
                    throw new HttpException(400, "Terjadi Kesalahan");
                }

                $allChild = explode(",", $model->getAllChildren());
                $this->modelClass::updateAll(["flag" => 0, "deleted_at" => date("Y-m-d H:i:s"), "deleted_by" => Yii::$app->user->id], ["id" => $allChild]);

                $model->flag = 0;
                $model->deleted_at = date("Y-m-d H:i:s");
                $model->deleted_by = Yii::$app->user->id;
                $model->save();
                return ['success' => true, 'message' => 'Berhasil menghapus data progress.'];
            endif;
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? 'Terjadi kesalahan saat menghapus data progress.');
        }
        throw new HttpException(400, 'Data belum lengkap.');
    }

    /**
     * actionStatusVerifikasi
     * controller-id: proyek-kemajuan/status-verifikasi
     * action-id: status-verifikasi
     * @return mixed
     */
    public function actionStatusVerifikasi()
    {
        $template = [];
        $statuses = $this->modelClass::getStatuses();
        foreach ($statuses as $key => $value) {
            $template[$key] = [
                'id' => $key,
                'name' => $value,
            ];
        }
        return [
            "success" => true,
            "data" => $template
        ];
    }


    /**
     * Creates a new ProyekKemajuanHarian model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUpdateProgress($id_project, $id)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $transaction = \Yii::$app->db->beginTransaction();

        $proyek_kemajuan = $this->modelClass::findOne(['id' => $id, 'id_proyek' => $project->id]);
        if ($proyek_kemajuan == false) throw new HttpException(404, "Data tidak ditemukan");
        $kemajuan_sampai_sekarang = floatval(ProyekKemajuanHarian::find()->where(['id_proyek_kemajuan' => $id])->select('sum(volume)')->column()[0]);
        $bobot_sampai_sekarang = floatval(ProyekKemajuanHarian::find()->where(['id_proyek_kemajuan' => $id])->select('sum(bobot)')->column()[0]);

        if ($kemajuan_sampai_sekarang >= $proyek_kemajuan->volume) {
            throw new HttpException(400, "Volume kemajuan dari proyek ini telah mencapai volume yang ditetapkan.");
        }

        $progress_exist = ProyekKemajuanHarian::find()->where([
            'id_proyek_kemajuan' => $id,
            'id_proyek' => $project->id,
            'tanggal' => $_POST['tanggal'],
            'deleted_at' => null,
        ])->one();

        try {
            if ($progress_exist) {
                if ($transaction->getIsActive()) $transaction->rollBack();
                return $this->updateHarian($project->id, $proyek_kemajuan->id);
            }

            $model = new ProyekKemajuanHarian;
            $modelImage = new ProyekGaleri;
            $modelImage->scenario = ProyekGaleri::SCENARIO_CREATE_AT_PROGRESS;
            $model->scenario = $model::SCENARIO_CREATE;
            if ($model->load($_POST, '')) :
                $proyek = Proyek::findOne($proyek_kemajuan->id_proyek);
                if ($proyek == null) {
                    throw new HttpException(400, "Data tidak ditemukan");
                }

                $tanggal1 = $model->tanggal;
                $tanggal2 = $proyek->tanggal_awal_kontrak;
                $tanggal3 = $proyek->tanggal_akhir_kontrak;

                if (strtotime($tanggal1) < strtotime($tanggal2)) {
                    throw new HttpException(400, "Tanggal kemajuan tidak boleh lebih kecil dari tanggal mulai kontrak");
                } elseif (strtotime($tanggal1) > strtotime($tanggal3)) {
                    throw new HttpException(400, "Tanggal kemajuan tidak boleh lebih besar dari tanggal akhir kontrak");
                }

                $model->id_proyek = $proyek->id;
                $model->id_proyek_kemajuan = $proyek_kemajuan->id;
                $proyek_kemajuan->volume_kemajuan = $kemajuan_sampai_sekarang;
                $proyek_kemajuan->volume_kemajuan += $model->volume;
                $proyek_kemajuan->bobot_kemajuan = $bobot_sampai_sekarang;
                $model->bobot = ($model->volume / $proyek_kemajuan->volume) * $proyek_kemajuan->bobot;
                $proyek_kemajuan->bobot_kemajuan += $model->bobot;

                $proyek_kemajuan->status_verifikasi = Yii::$app->request->post('status_verifikasi');

                if ($this->modelClass::getStatuses()[$proyek_kemajuan->status_verifikasi] == null) {
                    throw new HttpException(400, "Status verifikasi tidak valid");
                }

                if ($proyek_kemajuan->volume_kemajuan > $proyek_kemajuan->volume) {
                    throw new HttpException(400, "Qty kemajuan tidak dapat lebih besar dari total Qty");
                }

                //  load image if exist
                $image = UploadedFile::getInstanceByName('nama_file');
                $modelImage->id_proyek_kemajuan = $proyek_kemajuan->getGrandMasterParent();

                if ($image) {
                    $modelImage->load($_POST, '');
                    $modelImage->id_proyek = $proyek_kemajuan->id_proyek;
                    $response = $this->uploadImage($image, $modelImage->getUploadedPath());
                    if ($response->success == false) {
                        throw new HttpException(400, $response->message);
                    }

                    $modelImage->nama_file = $response->filename;
                    $modelImage->keterangan = $_POST['keterangan_gambar'];
                    if ($modelImage->validate() == false) {
                        throw new HttpException(400, $this->messageValidationFailed(
                            \app\components\Constant::flattenError(
                                $modelImage->getErrors()
                            )
                        ));
                    }
                    $modelImage->save();
                }


                if ($model->validate()) :
                    $model->save();
                    $proyek_kemajuan->save();

                    $transaction->commit();
                    return ['success' => true, 'message' => 'Berhasil menambahkan data progress.'];
                else :
                    throw new HttpException(400, $this->messageValidationFailed(
                        \app\components\Constant::flattenError(
                            $model->getErrors()
                        )
                    ));
                endif;
            endif;
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? 'Internal server error');
        }

        if ($transaction->getIsActive()) $transaction->rollBack();

        throw new HttpException(400, "Bad Request");
    }

    /**
     * Updates an existing SuratBeritaAcaraPemasanganAlat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    private function updateHarian($id_project, $id)
    {

        $project = $this->hasAccessAtThisProject($id_project);
        $transaction = Yii::$app->db->beginTransaction();
        $modelImage = new ProyekGaleri();
        $modelImage->scenario = ProyekGaleri::SCENARIO_CREATE_AT_PROGRESS;
        $model = \app\models\ProyekKemajuanHarian::findOne(
            [
                'id_proyek' => $project->id,
                'id_proyek_kemajuan' => $id,
                'tanggal' => $_POST['tanggal'],
            ]
        );
        $proyek_kemajuan = $this->modelClass::findOne(['id' => $model->id_proyek_kemajuan]);
        $old_volume = $model->volume;
        $old_bobot = $model->bobot;
        $model->scenario = $model::SCENARIO_UPDATE;
        $kemajuan_sampai_sekarang = floatval(ProyekKemajuanHarian::find()->where(['id_proyek_kemajuan' => $proyek_kemajuan->id])->select('sum(volume)')->column()[0]);
        $bobot_sampai_sekarang = floatval(ProyekKemajuanHarian::find()->where(['id_proyek_kemajuan' => $proyek_kemajuan->id])->select('sum(bobot)')->column()[0]);

        if ($kemajuan_sampai_sekarang >= $proyek_kemajuan->volume) {
            throw new HttpException(400, "Qty kemajuan tidak dapat lebih besar dari total Qty");
        }

        try {
            if ($model->load($_POST, '')) :
                $proyek_kemajuan->volume_kemajuan = $kemajuan_sampai_sekarang;
                $proyek_kemajuan->volume_kemajuan += $model->volume - $old_volume;
                $proyek_kemajuan->bobot_kemajuan = $bobot_sampai_sekarang;
                $model->bobot = ($model->volume / $proyek_kemajuan->volume) * $proyek_kemajuan->bobot;
                $proyek_kemajuan->bobot_kemajuan += $model->bobot - $old_bobot;
                if ($proyek_kemajuan->volume < $proyek_kemajuan->volume_kemajuan) {
                    throw new HttpException(400, "Qty kemajuan tidak dapat lebih besar dari total Qty");
                }

                $proyek_kemajuan->status_verifikasi = Yii::$app->request->post('status_verifikasi');
                if ($this->modelClass::getStatuses()[$proyek_kemajuan->status_verifikasi] == null) {
                    throw new HttpException(400, "Status verifikasi tidak valid");
                }

                //  load image if exist
                $image = UploadedFile::getInstanceByName('nama_file');
                $modelImage->id_proyek_kemajuan = $proyek_kemajuan->getGrandMasterParent();

                if ($image) {
                    $modelImage->load($_POST, '');
                    $modelImage->id_proyek = $proyek_kemajuan->id_proyek;
                    $response = $this->uploadImage($image, $modelImage->getUploadedPath());
                    if ($response->success == false) {
                        throw new HttpException(400, $response->message);
                    }

                    $modelImage->nama_file = $response->filename;
                    $modelImage->keterangan = $_POST['keterangan_gambar'];

                    if ($modelImage->validate() == false) {
                        throw new HttpException(400, $this->messageValidationFailed(
                            \app\components\Constant::flattenError(
                                $modelImage->getErrors()
                            )
                        ));
                    }

                    $modelImage->save();
                }

                if ($model->validate()) :
                    $model->save();
                    $proyek_kemajuan->save();
                    $transaction->commit();
                    return ['success' => true, 'message' => 'Berhasil mengubah data progress.'];
                else :
                    throw new HttpException(400, $this->messageValidationFailed(
                        \app\components\Constant::flattenError(
                            $model->getErrors()
                        )
                    ));
                endif;
            endif;
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? 'Internal server error');
        }

        if ($transaction->getIsActive()) $transaction->rollBack();
        throw new HttpException(400, "Bad Request");
    }
}
