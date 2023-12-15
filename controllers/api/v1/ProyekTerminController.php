<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "ProyekTerminController".
 * Modified by Defri Indra
 */

use app\models\Proyek;
use app\models\ProyekTermin;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\UploadedFile;

class ProyekTerminController extends \app\controllers\api\BaseController
{
    use \app\components\UploadFile;
    public $modelClass = 'app\models\ProyekTermin';

    public function actionIndex($id_proyek)
    {
        $query = $this->modelClass::find()->where(['id_proyek' => $id_proyek, 'user_id' => Yii::$app->user->id]);
        return $this->dataProvider($query);
    }

    public function actionPembayaran()
    {
        date_default_timezone_set("Asia/Jakarta");
        $this->view->title = 'Pembayaran Cicilan';
        $model = ProyekTermin::find()->where(['kode_unik' => $_GET['uniq']])->one();
        if ($model == null) throw new HttpException(404, "Data tidak ditemukan");

        $status = $model->status;
        if ($status == 1  || $status == 2) {
            throw new HttpException(400, 'Termin Telah Dibayar');
        }
        if ($model->proyek->total_pembayaran == $model->proyek->nilai_kontrak) {
            throw new HttpException(400, 'Pembayaran Tidak Dapat Melebihi Nilai Kontrak');
        }

        $proyek = Proyek::find()
            ->where(['kode_unik' => $model->kode_proyek])
            ->andWhere(['id_user' => Yii::$app->user->id])
            ->one();
        $proyek->scenario = $proyek::SCENARIO_BAYAR_TERMIN;

        $model->scenario = $model::SCENARIO_BAYAR_TERMIN;
        $oldbukti = $model->bukti_pembayaran;


        try {
            if ($model->status == 3) {
                unlink(Yii::getAlias("@app/web/uploads/") . $oldbukti);
                // $this->deleteOne($oldbukti);
            }
            $instance = UploadedFile::getInstanceByName('bukti_pembayaran');

            $response = $this->uploadFile($instance, "project/pembayaran_termin/{$model->kode_unik}");
            if ($response->success == false) {
                throw new HttpException(400, "Gagl unggah gambar");
            }
            $model->keterangan_pembayaran = $_POST['keterangan_pembayaran'];
            $model->bukti_pembayaran = $response->filename;
            // dd($model->bukti_pembayaran = $response->filename);
            $model->tanggal_pembayaran = date('Y-m-d H:i:s');
            $jumlah_sebelum = $proyek->total_pembayaran;
            $jumlah_bayar = $model->nilai_pembayaran;

            // $model->jumlah_bayar = str_replace(",", "", $model->jumlah_bayar);
            // $model->jumlah_bayar = str_replace("Rp ", "", $model->jumlah_bayar);
            $bayar = $jumlah_bayar;
            // $proyek->total_pembayaran = $jumlah_sebelum + $bayar;
            $model->status = 1;
            $model->alasan_tolak_pembayaran = null;


            if ($model->validate()) {
                $proyek->save();
                $model->save();

                \app\components\Notif::log(
                    null,
                    \Yii::$app->user->identity->name . " telah mengunggah bukti Pembayaran Termin",
                    "Hallo Admin, " . \Yii::$app->user->identity->name . " telah mengunggah bukti Pembayaran Termin. Silahkan cek data proyek",
                    [
                        "controller" => "proyek/view",
                        "android_route" => null,
                        "params" => [
                            "id" => $proyek->id
                        ]
                    ]
                );


                return ['success' => true, 'message' => 'success', "data" => $model];
            } else {
                return ['success' => false, 'message' => 'gagal', 'data' => $model->getErrors()];
            }
        } catch (\Exception $e) {
            throw new \yii\web\HttpException($e->statusCode ?? 500, $e->getMessage());
        }
    }
}
