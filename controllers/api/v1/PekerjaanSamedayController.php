<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "PekerjaanSamedayController".
 * Modified by Defri Indra
 */

use app\components\Constant;
use app\models\SiteSetting;
use Yii;
use yii\web\HttpException;

class PekerjaanSamedayController extends \app\controllers\api\BaseController
{
    use \app\components\UploadFile;

    public $modelClass = 'app\models\PekerjaanSameday';

    /**
     * Setting verb for each CRUD action
     * @inheritdoc
     */
    public function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'konfirmasi-pekerjaan' => ['POST'],
            'pembayaran-dp' => ['POST'],
            'delete' => ['DELETE'],
        ];
    }

    /**
     * actionIndex
     * action-id: pekerjaan-sameday/index
     * action-desc: Get all pekerjaan sameday
     * action-input:
     * action-author: Defri Indra
     * action-type: GET
     * action-url-path: pekerjaan-sameday
     * action-success-code: 200
     * action-return:
     */
    public function actionIndex()
    {
        $query = $this->modelClass::find()->andWhere(['id_pelanggan' => \Yii::$app->user->id])->orderBy(['id' => SORT_DESC]);
        return $this->dataProvider($query);
    }

    /**
     * actionView
     * action-id: pekerjaan-sameday/view
     * action-desc: Get pekerjaan sameday by id
     * action-input:
     * action-author: Defri Indra
     * action-type: GET
     * action-url-path: pekerjaan-sameday/view?id={id}
     * action-success-code: 200
     * action-return:
     * */
    public function actionView($id)
    {
        $model = $this->modelClass::find()->andWhere(['id_pelanggan' => \Yii::$app->user->id, "kode_unik" => $id])->one();
        if ($model == null) throw new HttpException(404, 'Data tidak ditemukan');
        return [
            "success" => true,
            "data" => $model
        ];
    }

    /**
     * actionCreate
     * action-id: pekerjaan-sameday/create
     * action-desc: Create pekerjaan sameday
     * action-input:
     * action-author: Defri Indra
     * action-type: POST
     * action-url-path: pekerjaan-sameday
     * action-success-code: 200
     * action-return:
     */
    public function actionCreate()
    {
        $model = new $this->modelClass;
        $model->scenario = $model::SCENARIO_CREATE;
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->load(\Yii::$app->request->post(), '')) {
                $model->kode_unik = Yii::$app->security->generateRandomString(30);
                $model->id_pelanggan = \Yii::$app->user->id;
                $model->status = $model::STATUS_PELENGKAPAN_DATA;

                // upload foto_lokasi to folder sameday/foto-lokasi and allow can empty
                $file = \yii\web\UploadedFile::getInstanceByName("foto_lokasi");
                if ($file) {
                    $response = $this->uploadImage($file, "sameday/foto-lokasi");
                    if ($response->success == false)  throw new HttpException(400, "Gagal mengunggah gambar");
                    $model->foto_lokasi = $response->filename;
                }

                if ($model->validate()) {
                    $model->save();
                    $transaction->commit();
                    // notifikasi ke admin
                    \app\components\Notif::log(
                        null,
                        "Pekerjaan Sameday Baru",
                        "Pelanggan {$model->pelanggan->name} mengajukan pekerjaan sameday",
                        [
                            "controller" => "pekerjaan-sameday/view",
                            "android_route" => null,
                            "params" => [
                                "id" => $model->id
                            ]
                        ]
                    );

                    return [
                        "success" => true,
                        "message" => "Tunggu survei dari admin ya...",
                        "data" => $model,
                    ];
                }

                throw new \yii\web\HttpException(422, Constant::flattenError($model->getErrors()));
            }
            throw new \yii\web\HttpException(400);
        } catch (\Throwable $th) {
            if ($transaction->isActive) $transaction->rollBack();
            throw new \yii\web\HttpException($th->statusCode ?? 500, $th->message ?? $th->getMessage());
        }
    }

    /**
     * ActionPembayaranDp
     * action-id: pekerjaan-sameday/pembayaran-dp
     * action-desc: Pembayaran pekerjaan sameday
     * action-input:{
     * "id": "",
     * "bukti_dp": ""
     * "keterangan_pembayaran_dp": ""
     * }
     * action-author: Defri Indra
     * action-type: POST
     * action-url-path: pekerjaan-sameday/pembayaran-dp?id={id}
     * action-success-code: 200
     * action-return: {
     * "success": true,
     * "message": "Pembayaran DP Berhasil dilakukan"
     * }
     * action-error-code: 422
     * action-error-message: {
     * "success": false,
     * "message": "Pembayaran DP Gagal dilakukan"
     * }
     */
    public function actionPembayaranDp($id)
    {
        $model = $this->modelClass::find()->andWhere(['id_pelanggan' => \Yii::$app->user->id, "kode_unik" => $id])->one();
        if ($model == null) throw new HttpException(404, 'Data tidak ditemukan');
        $model->scenario = $model::SCENARIO_BAYAR_DP;

        // validasi jika sudah ada pembayaran DP dan tidak ada revisi
        if ($model->bukti_dp != null && $model->revisi_pembayaran_dp == null) throw new HttpException(422, 'Pembayaran DP sudah dilakukan');

        // validasi jika status bukan menunggu pembayaran DP
        if ($model->status != $model::STATUS_PEMBAYARAN_DP) throw new HttpException(422, 'Status tidak sesuai');

        try {
            if ($model->load(\Yii::$app->request->post(), '')) {
                $model->tanggal_pembayaran_dp = date("Y-m-d H:i:s");
                // hapus bukti_dp jika ada dengan fungsi deleteOne
                if ($model->bukti_dp != null) $this->deleteOne($model->bukti_dp);

                // upload bukti_dp to folder sameday/bukti-dp dan file tidak boleh kosong
                $file = \yii\web\UploadedFile::getInstanceByName("bukti_dp");
                if ($file) {
                    $response = $this->uploadImage($file, "sameday/bukti-dp");
                    if ($response->success == false)  throw new HttpException(400, "Gagal mengunggah gambar");
                    $model->bukti_dp = $response->filename;
                } else {
                    throw new HttpException(400, "Bukti DP tidak boleh kosong");
                }

                if ($model->validate()) {
                    $model->status = $model::STATUS_PEMBAYARAN_DP;
                    $model->revisi_pembayaran_dp = null;
                    $model->save();

                    // notifikasi ke pelanggan bahwa pembayaran dp telah di konfirmasi
                    // \app\components\Notif::log(
                    //     $model->id_pelanggan,
                    //     "Pembayaran DP",
                    //     "Pembayaran DP telah dikonfirmasi",
                    //     [
                    //         "controller" => "pekerjaan-sameday/view",
                    //         "android_route" => null,
                    //         "params" => [
                    //             "id" => $model->kode_unik
                    //         ]
                    //     ]
                    // );

                    \app\components\Notif::log(
                        null,
                        "Pembayaran DP",
                        "Pembayaran DP menunggu konfirmasi",
                        [
                            "controller" => "pekerjaan-sameday/view",
                            "android_route" => null,
                            "params" => [
                                "id" => $model->id
                            ]
                        ]
                    );

                    return [
                        "success" => true,
                        "message" => "Pembayaran DP Berhasil dilakukan",
                    ];
                }

                throw new \yii\web\HttpException(422, Constant::flattenError($model->getErrors()));
            }
            throw new \yii\web\HttpException(400);
        } catch (\Throwable $th) {
            throw new \yii\web\HttpException($th->statusCode ?? 500, $th->message ?? $th->getMessage());
        }
    }

    /**
     * actionRevisiPengerjaan
     * action-id: pekerjaan-sameday/revisi-pengerjaan
     * action-desc: Revisi pekerjaan sameday menggunakan status revisi
     * action-input:
     * action-author: Defri Indra
     * action-type: POST
     * action-url-path: pekerjaan-sameday/revisi-pengerjaan?id={id}
     * action-success-code: 200
     * action-return: {
     * "success": true,
     * "message": "Pekerjaan Sameday Berhasil direvisi"
     * }
     * action-error-code: 422
     * action-error-message: {
     * "success": false,
     * "message": "Pekerjaan Sameday Gagal direvisi"
     * }
     * action-error-code: 404
     * action-error-message: {
     * "success": false,
     * "message": "Data tidak ditemukan"
     * }
     */
    public function actionRevisiPengerjaan($id)
    {
        $model = $this->modelClass::find()->andWhere(['id_pelanggan' => \Yii::$app->user->id, "kode_unik" => $id])->one();
        $model->scenario = $model::SCENARIO_REVISI;
        if ($model == null) throw new HttpException(404, 'Data tidak ditemukan');

        // validasi jika status bukan menunggu konfirmasi
        if ($model->status != $model::STATUS_DIAJUKAN) throw new HttpException(422, 'Status tidak sesuai');

        try {
            $model->status = $model::STATUS_PENGERJAAN;
            if ($model->load(Yii::$app->request->post(), '')) {

                if ($model->validate()) {
                    $model->save();

                    // notifikasi ke tukang bahwa pekerjaan direvisi
                    \app\components\Notif::log(
                        $model->id_tukang,
                        "Pekerjaan Sameday",
                        "Pekerjaan Sameday telah direvisi",
                        [
                            "controller" => "pekerjaan-sameday/view",
                            "android_route" => null,
                            "params" => [
                                "id" => $model->id
                            ]
                        ]
                    );

                    // notifikasi ke admin bahwa pekerjaan direvisi
                    \app\components\Notif::log(
                        null,
                        "Pekerjaan Sameday",
                        "Pekerjaan Sameday telah direvisi",
                        [
                            "controller" => "pekerjaan-sameday/view",
                            "android_route" => null,
                            "params" => [
                                "id" => $model->id
                            ]
                        ]
                    );

                    return [
                        "success" => true,
                        "message" => "Pekerjaan Sameday Berhasil direvisi",
                    ];
                }

                throw new \yii\web\HttpException(422, Constant::flattenError($model->getErrors()));
            }
            throw new \yii\web\HttpException(400);
        } catch (\Throwable $th) {
            throw new \yii\web\HttpException($th->statusCode ?? 500, $th->message ?? $th->getMessage());
        }
    }

    /**
     * actionPembayaranTotal
     * action-id: pekerjaan-sameday/pembayaran-total
     * action-desc: Pembayaran total pekerjaan sameday
     * action-input: {
     * "bukti_pembayaran": \yii\web\UploadedFile::class,
     * "keterangan": "Keterangan pembayaran"
     * }
     * action-author: Defri Indra
     * action-type: POST
     * action-url-path: pekerjaan-sameday/pembayaran-total?id={id}
     * action-success-code: 200
     * action-return: {
     * "success": true,
     * "message": "Pembayaran total berhasil dikonfirmasi"
     * }
     */
    public function actionKonfirmasiPembayaran($id)
    {
        $model = $this->modelClass::find()->andWhere(['id_pelanggan' => \Yii::$app->user->id, "kode_unik" => $id])->one();
        $model->scenario = $model::SCENARIO_BAYAR_TOTAL;
        if ($model == null) throw new HttpException(404, 'Data tidak ditemukan');

        // validasi jika status bukan menunggu konfirmasi & belum dibayar
        if ($model->status != $model::STATUS_DIAJUKAN) {
            if (($model->status != $model::STATUS_PEMBAYARAN)) {
                throw new HttpException(422, 'Status tidak sesuai');
            }
        }
        // if ($model->status == $model::STATUS_DIAJUKAN && ($model->status != $model::STATUS_PEMBAYARAN)) throw new HttpException(422, 'Status tidak sesuai');
        // if ($model->status == $model::STATUS_DIAJUKAN || ($model->status != $model::STATUS_PEMBAYARAN)) throw new HttpException(422, 'Status tidak sesuai');
        if ($model->revisi_pembayaran == null && $model->bukti_pembayaran != null) throw new HttpException(422, 'Pembayaran menunggu konfirmasi admin');

        try {
            if ($model->load(Yii::$app->request->post(), '')) {
                $model->status = $model::STATUS_PEMBAYARAN;
                $model->revisi_pembayaran = null;

                // hapus file lama
                if ($model->bukti_pembayaran != null) $this->deleteOne($model->bukti_pembayaran);

                // upload bukti pembayaran ke folder bukti pembayaran
                $bukti = \yii\web\UploadedFile::getInstanceByName('bukti_pembayaran');
                if ($bukti) {
                    $instance = $this->uploadFile($bukti, 'bukti_pembayaran');
                    if ($instance->success == false) {
                        throw new \yii\web\HttpException(422, $instance->message);
                    }
                } else {
                    throw new \yii\web\HttpException(400, 'Bukti pembayaran tidak boleh kosong');
                }

                $model->bukti_pembayaran = $instance->filename;
                if ($model->validate()) {
                    $model->tanggal_pembayaran = date("Y-m-d H:i:s");
                    $model->revisi_pembayaran = null;
                    $model->save();

                    // notifikasi ke tukang bahwa pekerjaan menunggu pembayaran total
                    \app\components\Notif::log(
                        $model->id_tukang,
                        "Pekerjaan Sameday",
                        "Pekerjaan Sameday menunggu pembayaran total",
                        [
                            "controller" => "pekerjaan-sameday/view",
                            "android_route" => null,
                            "params" => [
                                "id" => $model->id
                            ]
                        ]
                    );

                    // notifikasi ke admin bahwa pekerjaan menunggu pembayaran total
                    \app\components\Notif::log(
                        null,
                        "Pekerjaan Sameday",
                        "Pekerjaan Sameday menunggu pembayaran total",
                        [
                            "controller" => "pekerjaan-sameday/view",
                            "android_route" => null,
                            "params" => [
                                "id" => $model->id
                            ]
                        ]
                    );

                    return [
                        "success" => true,
                        "message" => "Pembayaran total berhasil dilakukan. Menunggu konfirmasi dari admin.",
                    ];
                }
                throw new \yii\web\HttpException(422, Constant::flattenError($model->getErrors()));
            }
            throw new \yii\web\HttpException(400);
        } catch (\Throwable $th) {
            throw new \yii\web\HttpException($th->statusCode ?? 500, $th->message ?? $th->getMessage());
        }
    }

    /**
     * actionPembayaranTotal
     * action-id: pekerjaan-sameday/pembayaran-total
     * action-desc: Pembayaran total pekerjaan sameday
     * action-input: {
     * "bukti_pembayaran": \yii\web\UploadedFile::class,
     * "keterangan": "Keterangan pembayaran"
     * }
     * action-author: Defri Indra
     * action-type: POST
     * action-url-path: pekerjaan-sameday/pembayaran-total?id={id}
     * action-success-code: 200
     * action-return: {
     * "success": true,
     * "message": "Pembayaran total berhasil dikonfirmasi"
     * }
     */
    public function actionKonfirmasiPengerjaan($id)
    {
        $model = $this->modelClass::find()->andWhere(['id_pelanggan' => \Yii::$app->user->id, "kode_unik" => $id])->one();
        if ($model == null) throw new HttpException(404, 'Data tidak ditemukan');

        // validasi jika status bukan menunggu konfirmasi & belum dibayar
        if ($model->status != $model::STATUS_DIAJUKAN) {
            throw new HttpException(422, 'Status tidak sesuai');
        }

        try {
            $model->status = $model::STATUS_PEMBAYARAN;

            if ($model->validate()) {
                $model->save();

                // notifikasi ke tukang bahwa pekerjaan menunggu pembayaran total
                // \app\components\Notif::log(
                //     $model->id_tukang,
                //     "Pekerjaan Sameday",
                //     "Pekerjaan Sameday menunggu pembayaran total",
                //     [
                //         "controller" => "pekerjaan-sameday/view",
                //         "android_route" => null,
                //         "params" => [
                //             "id" => $model->kode_unik
                //         ]
                //     ]
                // );

                // notifikasi ke admin bahwa pekerjaan menunggu pembayaran total
                \app\components\Notif::log(
                    null,
                    "Pekerjaan Sameday",
                    "Pekerjaan Sameday menunggu pembayaran total",
                    [
                        "controller" => "pekerjaan-sameday/view",
                        "android_route" => null,
                        "params" => [
                            "id" => $model->id
                        ]
                    ]
                );

                return [
                    "success" => true,
                    "message" => "Menunggu Pembayaran total.",
                ];
            }
            throw new \yii\web\HttpException(422, Constant::flattenError($model->getErrors()));
        } catch (\Throwable $th) {
            throw new \yii\web\HttpException($th->statusCode ?? 500, $th->message ?? $th->getMessage());
        }
    }


    public function actionCetakInvoice($id)
    {
        $model = $this->modelClass::find()->andWhere(['id_pelanggan' => \Yii::$app->user->id, "kode_unik" => $id])->one();
        if ($model == null) throw new HttpException(404, 'Data tidak ditemukan');

        $setting = SiteSetting::find()->one();


        $content = $this->renderPartial('/home/cari-tukang/pdf/_reportView', [
            'model' => $model,
            'setting' => $setting,
        ]);

        // setup kartik\mpdf\Pdf component
        $pdf = new \kartik\mpdf\Pdf([
            // set to use core fonts only
            'mode' => \kartik\mpdf\Pdf::MODE_UTF8,
            // A4 paper format
            'format' => \kartik\mpdf\Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => \kartik\mpdf\Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => \kartik\mpdf\Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            'marginHeader' => 0,
            'marginFooter' => 1,
            'marginTop' => 5,
            'marginBottom' => 5,
            'marginLeft' => 5,
            'marginRight' => 3,
            'cssFile' => [
                'homepage/css/invoice.css',
                'homepage/vendor/bootstrap4/bootstrap.min.css',
                'homepage/css/style.css',
                'homepage/css/style-print.css',
            ],
            'options' => [
                'defaultheaderline' => 0,  //for header
                'defaultfooterline' => 0,  //for footer
            ],
        ]);

        // return the pdf output as per the destination setting
        $data = base64_encode($pdf->render());
        return [
            "success" => true,
            "data" => $data
        ];
    }

    public function actionLaporanPekerjaan($id)
    {
        $model = $this->modelClass::find()->andWhere(['id_pelanggan' => \Yii::$app->user->id, "kode_unik" => $id])->one();
        if ($model == null) throw new HttpException(404, 'Data tidak ditemukan');

        $setting = SiteSetting::find()->one();
        $content = $this->renderPartial('/home/cari-tukang/pdf/_reportPekerjaan', [
            'model' => $model,
            'setting' => $setting,
        ]);

        if ($model->bukti_pembayaran == null) :
            $status = "belum-bayar.png";
        // elseif ($model->status == $model::STATUS_MENUNGGU_KONFIRMASI_ADMIN) :
        //   $status = "pengecekan.png";
        elseif ($model->bukti_pembayaran != null) :
            $status = "selesai.png";
        // elseif ($model->status == $model::STATUS_PEMBAYARAN_DIBATALKAN) :
        //   $status = "batal.png";
        // elseif ($model->status == $model::STATUS_PEMBAYARAN_EXPIRED) :
        //   $status = "expired.png";
        endif;

        // setup kartik\mpdf\Pdf component
        $pdf = new \kartik\mpdf\Pdf([
            // set to use core fonts only
            'mode' => \kartik\mpdf\Pdf::MODE_UTF8,
            // A4 paper format
            'format' => \kartik\mpdf\Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => \kartik\mpdf\Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => \kartik\mpdf\Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            'marginHeader' => 0,
            'marginFooter' => 1,
            'marginTop' => 5,
            'marginBottom' => 5,
            'marginLeft' => 5,
            'marginRight' => 3,
            'cssFile' => [
                'homepage/css/invoice.css',
                'homepage/vendor/bootstrap4/bootstrap.min.css',
                'homepage/css/style.css',
                'homepage/css/style-print.css',
            ],
            'cssInline' => "@page :first {background-image: url('" . Yii::getAlias("@link/pdf-status/$status") . "');background-repeat: no-repeat;background-position: center center}",
            'options' => [
                'defaultheaderline' => 0,  //for header
                'defaultfooterline' => 0,  //for footer
            ],
        ]);

        // return the pdf output as per the destination setting
        $data = base64_encode($pdf->render());
        return [
            "success" => true,
            "data" => $data
        ];
    }
}
