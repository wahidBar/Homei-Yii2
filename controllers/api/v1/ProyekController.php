<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "ProyekController".
 * Modified by Defri Indra
 */

use app\models\Proyek;
use app\components\UploadFile;
use app\models\ProyekCctv;
use app\models\ProyekTermin;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\UploadedFile;

class ProyekController extends \app\controllers\api\BaseController
{
    use UploadFile;

    public $modelClass = 'app\models\Proyek';

    public function actionIndex()
    {
        $id_user = Yii::$app->user->id;
        $query = $this->modelClass::find()
            ->andWhere(['id_user' => $id_user, 'flag' => 1])
            ->select('id,nama_proyek,tanggal_awal_kontrak,tanggal_akhir_kontrak');
        return $this->dataProvider($query);
    }

    public function actionView($id)
    {
        $id_user = Yii::$app->user->id;
        $data = $this->findModel(["id" => $id, "id_user" => $id_user, 'flag' => 1]);
        return [
            "success" => true,
            "data" => $data
        ];
    }

    public function actionBayarTermin($id)
    {
        date_default_timezone_set("Asia/Jakarta");
        $model = ProyekTermin::find()->where(['id' => $id])->andWhere(['user_id' => \Yii::$app->user->identity->id])->one();
        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }

        // cek apakah pemilik proyek ini
        if ($model->proyek->id_user != Yii::$app->user->id) {
            throw new HttpException(404, "Data tidak ditemukan");
        }

        $status = $model->status;
        if ($model == null) {
            return [
                "success" => false,
                "message" => "Data Tidak Ditemukan"
            ];
        }
        if ($status == 1  || $status == 2) {
            return [
                "success" => false,
                "message" => "Termin Telah Dibayar"
            ];
        }
        if ($model->proyek->total_pembayaran == $model->proyek->nilai_kontrak) {
            return [
                "success" => false,
                "message" => "Pembayaran Tidak Dapat Melebihi Nilai Kontrak"
            ];
        }

        $proyek = Proyek::find()->where(['id' => $model->proyek_id])->andWhere(['id_user' => \Yii::$app->user->identity->id])->one();
        $proyek->scenario = $proyek::SCENARIO_BAYAR_TERMIN;

        $model->scenario = $model::SCENARIO_BAYAR_TERMIN;
        $oldbukti = $model->bukti_pembayaran;

        $bukti_pembayarans = UploadedFile::getInstanceByName('bukti_pembayaran');

        try {
            if ($model->load(\Yii::$app->request->post(), '')) :
                if ($model->status == 3) {
                    unlink(Yii::getAlias("@app/web/uploads/") . $oldbukti);
                }

                $model->bukti_pembayaran = $bukti_pembayarans->name;
                $arr = explode(".", $bukti_pembayarans->name);
                $extension = end($arr);

                # generate a unique bukti_transaksis name
                $filename = Yii::$app->security->generateRandomString() . ".{$extension}";

                $model->bukti_pembayaran = "project/pembayaran_termin/{$model->kode_unik}/" . $filename;

                # the path to save bukti_transaksis
                if (file_exists(Yii::getAlias("@app/web/uploads/project/pembayaran_termin/{$model->kode_unik}/")) == false) {
                    mkdir(Yii::getAlias("@app/web/uploads/project/pembayaran_termin/{$model->kode_unik}/"), 0777, true);
                }
                $path = Yii::getAlias("@app/web/uploads/project/pembayaran_termin/{$model->kode_unik}/") . $filename;
                $bukti_pembayarans->saveAs($path);

                $model->tanggal_pembayaran = date('Y-m-d H:i:s');
                $jumlah_sebelum = $proyek->total_pembayaran;
                $jumlah_bayar = $model->nilai_pembayaran;
                $bayar = $jumlah_bayar;
                $proyek->total_pembayaran = $jumlah_sebelum + $bayar;
                $model->status = 1;
                $model->alasan_tolak_pembayaran = null;

                if ($model->validate()) :
                    $proyek->save();
                    $model->save();

                    \app\components\Notif::log(
                        null,
                        "Pembayaran Termin Proyek {$proyek->nama_proyek}",
                        "Pembayaran Termin Proyek {$proyek->nama_proyek} menunggu persetujuan dari Anda",
                        [
                            "controller" => "proyek/view",
                            "android_route" => null,
                            "params" => [
                                "id" => $proyek->id
                            ]
                        ]
                    );
                    return [
                        "success" => true,
                        "data" => $model
                    ];
                endif;
                return [
                    "success" => false,
                    "message" => "Data Gagal Disimpan1"
                ];
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Throwable $th) {
            return [
                "success" => false,
                "message" => "Data Gagal Disimpan2"
            ];
        }
    }
}
