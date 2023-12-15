<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "IsianLanjutanController".
 * Modified by Defri Indra
 */

use app\components\Constant;
use app\components\Notif;
use app\components\UploadFile;
use app\models\IsianLanjutan;
use app\models\IsianLanjutanRuangan;
use app\models\Penawaran;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class IsianLanjutanController extends \app\controllers\api\BaseController
{
    use UploadFile;

    public $modelClass = 'app\models\IsianLanjutan';

    public function actionIndex()
    {
        $data = IsianLanjutan::find()
            ->select([
                't_isian_lanjutan.id',
                't_isian_lanjutan.kode_unik as isian_id',
                't_konsultasi.kode_isian_lanjutan',
                't_konsultasi.is_active',
                't_konsultasi.id_konsultan',
                't_isian_lanjutan.id_user',
                't_isian_lanjutan.label',
                't_isian_lanjutan.luas_tanah',
                't_isian_lanjutan.budget',
                't_isian_lanjutan.status'
            ])
            ->join(
                'LEFT JOIN',
                't_konsultasi',
                't_isian_lanjutan.kode_unik =t_konsultasi.kode_isian_lanjutan'
            )
            ->where(['t_isian_lanjutan.id_user' => \Yii::$app->user->identity->id])
            ->andWhere(['t_konsultasi.is_active' => 0])
            ->andWhere(['>=', 't_isian_lanjutan.status', 1])
            ->andWhere(['<', 't_isian_lanjutan.status', 9])
            ->andWhere(['not', ['t_konsultasi.id_konsultan' => null]])->all();


        if ($data != null) {
            return [
                "success" => true,
                "data" => $data
            ];
        } else {
            return [
                "success" => false,
                "message" => "Data Tidak Ditemukan"
            ];
        }
    }

    public function actionView($id)
    {
        $data = IsianLanjutan::find()->where(['id' => $id])->andWhere(['id_user' => \Yii::$app->user->identity->id])->one();

        return [
            "success" => true,
            "data" => $data
        ];
    }

    public function actionBatalkanRencanaPembangunan($id)
    {
        $modelBatal = IsianLanjutan::find()->where(['id' => $id])->andWhere(['id_user' => \Yii::$app->user->identity->id])->one();
        $modelBatal->scenario = $modelBatal::SCENARIO_BATAL_PROYEK;
        if ($modelBatal == null) {
            return [
                "success" => false,
                "message" => "Data Tidak Ditemukan"
            ];
        }
        try {
            if ($modelBatal->load(\Yii::$app->request->post(), '')) {
                if ($modelBatal->status < $modelBatal::STATUS_RENCANA_PEMBANGUNAN) {
                    return [
                        "success" => false,
                        "message" => "Tidak Dapat Membatalkan Rencana Pembangunan"
                    ];
                }
                if ($modelBatal->validate()) :
                    $modelBatal->status = $modelBatal::STATUS_TOLAK;
                    $modelBatal->save();

                    Notif::log(
                        null,
                        "Rencana Pembangunan di Batalkan",
                        "Rencana Pembangunan dengan kode isian lanjutan " . $modelBatal->kode_unik . " di Batalkan",
                        [
                            "controller" => "isian-lanjutan/view",
                            "android_route" => null,
                            [
                                "id" => $modelBatal->id,
                            ]
                        ]
                    );

                    return [
                        "success" => true,
                        "data" => $modelBatal
                    ];
                endif;
                return [
                    "success" => false,
                    "message" => "Data Gagal Disimpan"
                ];
            }
        } catch (\Throwable $th) {
            throw new \yii\web\HttpException($th->statusCode ?? 500, $th->getMessage());
        }
    }

    public function actionRevisiTor($id)
    {
        $model = IsianLanjutan::find()->where(['id' => $id])->andWhere(['id_user' => \Yii::$app->user->identity->id])->one();
        $model->scenario = $model::SCENARIO_TOLAK;
        if ($model == null) {
            return [
                "success" => false,
                "message" => "Data Tidak Ditemukan"
            ];
        }
        try {
            if ($model->load(\Yii::$app->request->post(), '')) {
                if ($model->validate()) :
                    $model->approval_dokumen_tor = 2;
                    $model->status = $model::STATUS_TOR_BUTUH_REVISI;
                    $model->save();

                    Notif::log(
                        null,
                        "Revisi Dokumen TOR",
                        "User Meminta Revisi Dokumen TOR",
                        [
                            "controller" => "isian-lanjutan/view",
                            "android_route" => null,
                            [
                                "id" => $model->id,
                            ]
                        ]
                    );

                    return [
                        "success" => true,
                        "data" => $model,
                        "message" => "Data Berhasil Disimpan"
                    ];
                endif;
                return [
                    "success" => false,
                    "message" => "Data Gagal Disimpan"
                ];
            }
        } catch (\Throwable $th) {
            throw new \yii\web\HttpException($th->statusCode ?? 500, $th->getMessage());
        }
    }

    public function actionSetujuTor($id)
    {
        $model = IsianLanjutan::find()->where(['id' => $id])->andWhere(['id_user' => \Yii::$app->user->identity->id])->one();
        $model->scenario = $model::SCENARIO_APPROVE_TOR;

        if ($model->id_user != Constant::getUser()->id) {
            return [
                "success" => false,
                "message" => "Data Tidak Ditemukan"
            ];
        }

        $model->status = $model::STATUS_SETUJU_TOR;
        if ($model->save()) {
            // notifikasi ke admin bahwa user menyetujui dokumen tor
            Notif::log(
                null,
                "Dokumen TOR Disetujui",
                "Dokumen TOR dengan kode isian lanjutan " . $model->kode_unik . " telah disetujui",
                [
                    "controller" => "isian-lanjutan/view",
                    "android_route" => null,
                    [
                        "id" => $model->id,
                    ]
                ]
            );

            return [
                "success" => true,
                "data" => $model,
                "message" => "Data Berhasil Disimpan"
            ];
        } else {
            return [
                "success" => false,
                "message" => "Data Gagal Disimpan"
            ];
        }
    }

    public function actionUploadPembayaranDp($id)
    {
        date_default_timezone_set("Asia/Jakarta");
        $model = IsianLanjutan::find()->where(['id' => $id])->andWhere(['id_user' => \Yii::$app->user->identity->id])->one();
        if ($model->status_pembayaran == 1 || $model->status_pembayaran == 2) {
            return [
                "success" => false,
                "message" => "DP Telah Dibayar"
            ];
        }
        if ($model->dp_pembayaran == null) {
            return [
                "success" => false,
                "message" => "Data Belum Ditentukan Admin"
            ];
        }
        if ($model == null) {
            return [
                "success" => false,
                "message" => "Data Tidak Ditemukan"
            ];
        }

        if ($model->status_pembayaran != 3) {
            $model->scenario = $model::SCENARIO_BAYARDP;
        } else {
            $model->scenario = $model::SCENARIO_BAYARDPULANG;
        }
        $oldbukti = $model->bukti_pembayaran;

        $bukti_pembayarans = UploadedFile::getInstanceByName('bukti_pembayaran');

        try {
            if ($model->load(\Yii::$app->request->post(), '')) :

                // dd($model->keterangan_pembayaran);
                if ($bukti_pembayarans != NULL) {
                    # store the source bukti_transaksis name
                    $model->bukti_pembayaran = $bukti_pembayarans->name;
                    $arr = explode(".", $bukti_pembayarans->name);
                    $extension = end($arr);

                    # generate a unique bukti_transaksis name
                    $filename = Yii::$app->security->generateRandomString() . ".{$extension}";

                    $model->bukti_pembayaran = "rencana-pembangunan/pembayaran_dp/{$model->kode_unik}/" . $filename;

                    // dd($model->bukti_pembayaran);
                    # the path to save bukti_transaksis
                    if (file_exists(Yii::getAlias("@app/web/uploads/rencana-pembangunan/pembayaran_dp/{$model->kode_unik}/")) == false) {
                        mkdir(Yii::getAlias("@app/web/uploads/rencana-pembangunan/pembayaran_dp/{$model->kode_unik}/"), 0777, true);
                    }
                    $path = Yii::getAlias("@app/web/uploads/rencana-pembangunan/pembayaran_dp/{$model->kode_unik}/") . $filename;
                    $bukti_pembayarans->saveAs($path);
                }
                $model->tanggal_pembayaran = date('Y-m-d H:i:s');
                $model->status_pembayaran = 1;
                $model->alasan_tolak = null;
                if ($model->validate()) {
                    $model->save();
                    // notifikasi ke admin bahwa user telah melakukan pembayaran DP
                    Notif::log(
                        null,
                        "Pembayaran DP Rencana Pembangunan",
                        "User Melakukan Pembayaran DP dengan kode isian lanjutan " . $model->kode_unik,
                        [
                            "controller" => "isian-lanjutan/view",
                            "android_route" => null,
                            [
                                "id" => $model->id,
                            ]
                        ]
                    );

                    return ['success' => true, 'message' => 'success', "data" => $model,];
                } else {
                    return ['success' => false, 'message' => 'gagal', 'data' => $model->getErrors()];
                }
            endif;
        } catch (\Throwable $th) {
            throw new \yii\web\HttpException($th->statusCode ?? 500, $th->getMessage());
        }
    }

    public function actionTanggalRencanaPembangunan($id)
    {
        $model = IsianLanjutan::find()->where(['id' => $id])->andWhere(['id_user' => \Yii::$app->user->identity->id])->one();

        if ($model->status_pembayaran != 2) {
            return [
                "success" => false,
                "message" => "DP Belum Dibayar atau Diverifikasi"
            ];
        }
        $model->scenario = $model::SCENARIO_BANGUN;

        if ($model->status != $model::STATUS_SETUJU_TOR && $model->status != $model::STATUS_TOLAK && $model->status != $model::STATUS_REVISI_RENCANA_PEMBANGUNAN) {
            return [
                "success" => false,
                "message" => "TOR Belum Disetujui atau Rencana Pembangunan Ditolak"
            ];
        }
        try {
            if ($model->load(\Yii::$app->request->post(), '')) :
                if (strtotime($model->rencana_pembangunan) < time()) {
                    toastError(Yii::t("cruds", "Tanggal Pembangunan Harus lebih besar dari tanggal sekarang."));
                    return $this->redirect(['isian-lanjutan/view', 'id' => $model->id]);
                }

                if ($model->validate()) :
                    $model->status = $model::STATUS_RENCANA_PEMBANGUNAN;
                    $model->alasan_tolak = null;
                    $model->save();
                    // notifikasi ke admin bahwa user mengajukan tanggal rencana pembangunan
                    Notif::log(
                        null,
                        "Pengajuan Tanggal Rencana Pembangunan",
                        "User Mengajukan Tanggal Rencana Pembangunan dengan kode isian lanjutan " . $model->kode_unik,
                        [
                            "controller" => "isian-lanjutan/view",
                            "android_route" => null,
                            [
                                "id" => $model->id,
                            ]
                        ]
                    );
                    return [
                        "success" => true,
                        "data" => $model,
                        "message" => "Data berhasil disimpan"
                    ];
                endif;
                return [
                    "success" => false,
                    "message" => "Data Gagal Disimpan"
                ];
            endif;
        } catch (\Throwable $th) {
            throw new \yii\web\HttpException($th->statusCode ?? 500, $th->getMessage());
        }
    }
}
