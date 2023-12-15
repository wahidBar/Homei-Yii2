<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\api\v1\keuangan;

use Yii;
use yii\web\HttpException;


class KeluarController extends BaseController
{
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
        $query = $this->modelClass::showAtIndex($project->id, false);

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
            'tipe' => \app\models\ProyekKeuanganKeluar::TIPE_DEFAULT,
        ])->one();

        return [
            "success" => true,
            "data" => $model
        ];
    }

    /**
     * actionCreate
     */
    public function actionCreate($id_project)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $model = new $this->modelClass;
        $model->scenario = $model::SCENARIO_PENGELUARAN;
        $modelDetail = [new \app\models\ProyekKeuanganKeluarDetail];

        try {
            $transaction = Yii::$app->db->beginTransaction();
            if ($model->load($_POST, '')) :
                $model->id_proyek = $project->id;
                $model->tipe =  \app\models\ProyekKeuanganKeluar::TIPE_DEFAULT;
                // save data
                if ($model->validate() == false) {
                    if ($transaction->isActive) $transaction->rollBack();
                    throw new HttpException(422, "Data gagal divalidasi");
                }
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
                        throw new HttpException(422, "Nilai dari harga satuan harus berupa angka");
                    }
                    $modelDetail[$id]->harga_satuan = $to_idr;
                    $modelDetail[$id]->jumlah = intval($modelDetail[$id]->harga_satuan) * intval($modelDetail[$id]->kuantitas);
                    $total_jumlah += intval($modelDetail[$id]->jumlah);
                }

                // validate
                $model->status = \app\models\ProyekKeuanganKeluar::STATUS_LUNAS;
                $model->flag = 1;
                $valid = $model->validate() && \app\models\Model::validateMultiple($modelDetail);
                if ($valid) :
                    $model->total_jumlah = $total_jumlah;
                    $model->save();
                    foreach ($modelDetail as $item) $item->save();
                    $transaction->commit();

                    return [
                        "success" => true,
                        "message" => "Data berhasil disimpan",
                    ];
                endif;
                throw new HttpException(422, $this->messageValidationFailed(
                    \app\components\Constant::flattenError(
                        $model->getErrors()
                    )
                ));
            endif;
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? "Internal server error");
        }

        throw new HttpException(400, "Data tidak valid");
    }

    /**
     * Updates an existing SuratBeritaAcaraPemasanganAlat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    // public function actionUpdate($id_project, $id)
    // {
    //     $project = $this->hasAccessAtThisProject($id_project);
    //     $model = $this->findModel([
    //         "id" => $id,
    //         "id_proyek" => $project->id,
    //         "flag" => 1,
    //         'tipe' => \app\models\ProyekKeuanganKeluar::TIPE_DEFAULT,
    //     ]);

    //     $modelDetail = $model->proyekKeuanganKeluarDetails;
    //     $oldDetail = \yii\helpers\ArrayHelper::map($modelDetail, 'id', 'id');

    //     if ($modelDetail == []) $modelDetail = [new \app\models\ProyekKeuanganKeluarDetail()];
    //     $model->scenario = $model::SCENARIO_PENGELUARAN;
    //     $transaction = Yii::$app->db->beginTransaction();

    //     try {
    //         if ($model->load($_POST, '')) :

    //             // load model detail
    //             $modelDetail = \app\models\Model::createMultiple(\app\models\ProyekKeuanganKeluarDetail::class, $modelDetail);
    //             \app\models\Model::loadMultiple($modelDetail, $_POST);
    //             $deleteDetail = array_diff($oldDetail, array_filter(\yii\helpers\ArrayHelper::map($modelDetail, 'id', 'id')));
    //             $total_jumlah = 0;
    //             foreach ($modelDetail as $id => $item) {
    //                 $modelDetail[$id]->id_keuangan_keluar = $model->id;
    //                 $to_idr = str_replace(",", "", $modelDetail[$id]->harga_satuan);
    //                 if (is_numeric($to_idr) == false) {
    //                     if ($transaction->isActive) $transaction->rollBack();
    //                     throw new HttpException(422, "Terdapat data detail yang tidak valid");
    //                 }

    //                 $modelDetail[$id]->harga_satuan = $to_idr;
    //                 $modelDetail[$id]->jumlah = intval($modelDetail[$id]->harga_satuan) * intval($modelDetail[$id]->kuantitas);
    //                 $total_jumlah += intval($modelDetail[$id]->jumlah);
    //             }
    //             $model->total_jumlah = $total_jumlah;

    //             // set default value
    //             $model->tipe = 0; // biasa , bukan po
    //             $model->status = 1; // lunas

    //             // validate
    //             $valid = $model->validate() && \app\models\Model::validateMultiple($modelDetail);
    //             if ($valid) :
    //                 $model->save();

    //                 foreach ($modelDetail as $item) $item->save();
    //                 \app\models\ProyekKeuanganKeluarDetail::deleteAll(['in', 'id', $deleteDetail]);
    //                 $transaction->commit();

    //                 return [
    //                     "success" => true,
    //                     "message" => $this->messageUpdateSuccess()
    //                 ];
    //             endif;
    //             throw new HttpException(422, "Terdapat data yang tidak valid");
    //         endif;
    //     } catch (\Exception $e) {
    //         if ($transaction->isActive) $transaction->rollBack();
    //         throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? "Terjadi kesalahan pada server");
    //     }

    //     throw new HttpException(400, "Data tidak valid");
    // }

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
            'tipe' => \app\models\ProyekKeuanganKeluar::TIPE_DEFAULT,
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
}
