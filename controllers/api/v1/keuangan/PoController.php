<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\api\v1\keuangan;

use app\components\UploadFile;
use Yii;
use yii\web\HttpException;


class PoController extends BaseController
{
    use UploadFile;
    public $modelClass = 'app\models\ProyekKeuanganKeluar';

    const ALLOWED_ROLES = [
        \app\components\Constant::ROLE_KEUANGAN,
    ];


    /**
     * actionIndex
     */
    public function actionIndex($id_project)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $query = $this->modelClass::showAtIndex($project->id, true);

        return $this->dataProvider($query);
    }

    /**
     * actionView
     */
    public function actionView($id_project, $id)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $model = $this->modelClass::find()->andWhere([
            "id" => $id,
            "id_proyek" => $project->id,
            "flag" => 1,
            'tipe' => \app\models\ProyekKeuanganKeluar::TIPE_PO,
        ])->one();

        return [
            "success" => true,
            "data" => $model
        ];
    }

    /**
     * Creates a new ProyekKeuanganKeluar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_project)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $model = new \app\models\ProyekKeuanganKeluar;
        $model->scenario = $model::SCENARIO_PO;
        $modelDetail = [new \app\models\ProyekKeuanganKeluarDetail];
        $old_dokumen = $model->dokumen_po;

        try {
            $transaction = Yii::$app->db->beginTransaction();

            if ($model->load($_POST, '')) :
                $model->id_proyek = $project->id;
                $dokumen = \yii\web\UploadedFile::getInstanceByName("dokumen_po");
                if ($dokumen) {
                    $response = $this->uploadFile($dokumen, "project/$project->id/dokumen-po");
                    if ($response->success == false) {
                        throw new HttpException(422, "Dokument gagal diunggah");
                    }
                    $model->dokumen_po = $response->filename;
                } else {
                    $model->dokumen_po = $old_dokumen;
                }

                // save data
                if ($model->validate() == false) {
                    if ($transaction->isActive) $transaction->rollBack();
                    throw new HttpException(422, "Data gagal divalidasi");
                }


                $model->tipe = \app\models\ProyekKeuanganKeluar::TIPE_PO;
                $model->save();

                // load model detail
                $modelDetail = \app\models\Model::createMultiple(\app\models\ProyekKeuanganKeluarDetail::class);
                \app\models\Model::loadMultiple($modelDetail, $_POST);
                $total_jumlah = 0;

                foreach ($modelDetail as $id => $item) {
                    $modelDetail[$id]->id_keuangan_keluar = $model->id;
                    $to_idr = str_replace(",", "", $modelDetail[$id]->harga_satuan);
                    if (is_numeric($to_idr) == false) {
                        if ($transaction->isActive) $transaction->rollBack();
                        throw new HttpException(422, "Terdapat data detail yang tidak valid");
                    }
                    $modelDetail[$id]->harga_satuan = $to_idr;
                    $modelDetail[$id]->jumlah = intval($modelDetail[$id]->harga_satuan) * intval($modelDetail[$id]->kuantitas);
                    $total_jumlah += intval($modelDetail[$id]->jumlah);
                }

                // validate
                $valid = $model->validate() && \app\models\Model::validateMultiple($modelDetail);
                if ($valid) :
                    $model->total_jumlah = $total_jumlah;
                    $model->save();
                    foreach ($modelDetail as $item) $item->save();

                    $transaction->commit();
                    return [
                        "success" => true,
                        "message" => $this->messageCreateSuccess(),
                    ];
                endif;
                throw new HttpException(422, $this->messageValidationError());
            endif;
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? "Telah terjadi kesalahan yang tidak diketahui");
        }

        throw new HttpException(400, "Data tidak lengkap");
    }

    /**
     * Updates an existing SuratBeritaAcaraPemasanganAlat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $project = $this->hasAccessAtThisProject($id);
        $transaction = Yii::$app->db->beginTransaction();
        $model = $this->findModel([
            "id" => $id,
            "id_proyek" => $project->id,
            "flag" => 1,
        ]);
        if ($model->status != 0 && \app\components\Constant::getUser()->role_id != \app\components\Constant::ROLES['sa']) throw new HttpException(403, "Anda tidak diperbolehkan mengakses menu ini. Hubungi admin jika anda merasa perlu mengubah data ini");
        $modelDetail = $model->proyekKeuanganKeluarDetails;
        $oldDetail = \yii\helpers\ArrayHelper::map($modelDetail, 'id', 'id');
        if ($modelDetail == []) $modelDetail = [new \app\models\ProyekKeuanganKeluarDetail()];
        $model->scenario = $model::SCENARIO_PO;
        $model->tipe = 1;
        $model->status = 0;
        $old_dokumen = $model->dokumen_po;

        if (Yii::$app->request->isAjax) $render = "renderAjax";
        else $render = "render";

        try {
            if ($model->load($_POST, '')) :
                $model->id_proyek = $project->id;
                $dokumen = \yii\web\UploadedFile::getInstanceByName("dokumen_po");
                if ($dokumen) {
                    $response = $this->uploadFile($dokumen, "project/$project->id/dokumen-po");
                    if ($response->success == false) {
                        throw new HttpException(422, "Dokument gagal diunggah");
                    }
                    $model->dokumen_po = $response->filename;
                } else {
                    $model->dokumen_po = $old_dokumen;
                }

                // load model detail
                $modelDetail = \app\models\Model::createMultiple(\app\models\ProyekKeuanganKeluarDetail::class, $modelDetail);
                \app\models\Model::loadMultiple($modelDetail, $_POST);
                $deleteDetail = array_diff($oldDetail, array_filter(\yii\helpers\ArrayHelper::map($modelDetail, 'id', 'id')));
                $total_jumlah = 0;
                foreach ($modelDetail as $id => $item) {
                    $modelDetail[$id]->id_keuangan_keluar = $model->id;
                    $to_idr = str_replace(",", "", $modelDetail[$id]->harga_satuan);
                    if (is_numeric($to_idr) == false) {
                        if ($transaction->isActive) $transaction->rollBack();
                        throw new HttpException(422, "Terdapat data detail yang tidak valid");
                    }
                    $modelDetail[$id]->harga_satuan = $to_idr;
                    $modelDetail[$id]->jumlah = intval($modelDetail[$id]->harga_satuan) * intval($modelDetail[$id]->kuantitas);
                    $total_jumlah += intval($modelDetail[$id]->jumlah);
                }
                $model->total_jumlah = $total_jumlah;

                // set default value
                $model->tipe = 1; // biasa , bukan po
                $model->status = 0; // lunas

                // validate
                $valid = $model->validate() && \app\models\Model::validateMultiple($modelDetail);
                if ($valid) :
                    $model->save();
                    foreach ($modelDetail as $item) $item->save();
                    \app\models\ProyekKeuanganKeluarDetail::deleteAll(['in', 'id', $deleteDetail]);
                    $transaction->commit();

                    return [
                        "success" => true,
                        "message" => $this->messageUpdateSuccess(),
                    ];
                endif;
                if ($transaction->isActive) $transaction->rollBack();
                throw new HttpException(422, $this->messageValidationError());
            endif;
        } catch (\Exception $e) {
            if ($transaction->isActive) $transaction->rollBack();
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? "Telah terjadi kesalahan yang tidak diketahui");
        }

        throw new HttpException(400, "Data tidak lengkap");
    }

    /**
     * actionDelete
     * action-id: keuangan/masuk/delete
     * @param integer $id_project
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id_project, $id)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $model = $this->findModel([
            "id" => $id,
            "id_proyek" => $project->id,
            "flag" => 1,
            'tipe' => \app\models\ProyekKeuanganKeluar::TIPE_PO,
        ]);

        $model->scenario = $model::SCENARIO_DELETE;

        try {
            if ($model->validate()) :
                if ($model->tipe == 1 && $model->status != 0) {
                    toastError("Anda tidak dapat menghapus data ini. Hubungi admin jika dirasa harus mengakses fitur ini.");
                    return $this->redirect(['keuangan/view', 'id' => $model->id_proyek]);
                }
                $model->deleted_at = date("Y-m-d H:i:s");
                $model->deleted_by = \app\components\Constant::getUser()->id;
                $model->flag = 0;
                $model->save();

                return [
                    "success" => true,
                    "message" => $this->messageDeleteSuccess()
                ];
            endif;
            throw new HttpException(422, $this->messageValidationFailed());
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? "Terjadi kesalahan pada server");
        }

        throw new HttpException(400, "Data tidak valid");
    }


    /**
     * Creates a new ProyekKeuanganKeluarBayar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionBayar($id_project, $id)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $keuanganKeluar = $this->findModel([
            "id" => $id,
            "id_proyek" => $project->id,
            "flag" => 1,
            'tipe' => \app\models\ProyekKeuanganKeluar::TIPE_PO,
        ]);
        $transaction = Yii::$app->db->beginTransaction();
        $model = new \app\models\ProyekKeuanganKeluarBayar;
        $model->scenario = $model::SCENARIO_CREATE;
        $model->id_keuangan_keluar = $keuanganKeluar->id;
        $model->id_proyek = $keuanganKeluar->id_proyek;
        if ($keuanganKeluar == null) throw new HttpException(404);

        try {
            if ($model->load($_POST, '')) :
                $model->dibayar = intval(str_replace(",", "", $model->dibayar));
                if ($model->dibayar <= 0) {
                    throw new HttpException(422, "Nilai dibayarkan harus lebih besar dari 0");
                }
                if ($model->validate()) :
                    $model->flag = 1;
                    $model->save();

                    $keuanganKeluar->total_dibayarkan = $keuanganKeluar->getProyekKeuanganKeluarBayars()->where(['flag' => 1])->sum('dibayar');
                    $keuanganKeluar->status = 1;

                    if ($keuanganKeluar->getProyekKeuanganKeluarBayars()->count() == 0) {
                        $keuanganKeluar->status = 0;
                    } else if (intval($keuanganKeluar->total_dibayarkan) > intval($keuanganKeluar->total_jumlah)) {
                        $transaction->rollBack();
                        $keuanganKeluar->total_dibayarkan = $keuanganKeluar->getProyekKeuanganKeluarBayars()->sum('dibayar'); // refresh nilai ke asal
                        throw new HttpException(422, "Pembayaran tidak boleh melebihi total jumlah");
                    } else if (intval($keuanganKeluar->total_dibayarkan) == intval($keuanganKeluar->total_jumlah)) {
                        $keuanganKeluar->status = 2;
                    }

                    $keuanganKeluar->save();
                    $transaction->commit();
                    return [
                        "success" => true,
                        "message" => $this->messageCreateSuccess(),
                    ];
                endif;

                throw new HttpException(422, $this->messageValidationError(
                    \app\components\Constant::flattenError($model->errors)
                ));
            endif;
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? "Terjadi kesalahan pada server");
        }

        throw new HttpException(400, "Data tidak valid");
    }
}
