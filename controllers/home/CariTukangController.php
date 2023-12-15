<?php

namespace app\controllers\home;

date_default_timezone_set("Asia/Jakarta");

use app\components\HttpHelper;
use app\components\UploadFile;
use app\models\MasterKategoriLayananSameday;
use app\models\MasterPembayaran;
use app\models\Notification;
use app\models\Partner;
use app\models\PekerjaanSameday;
use app\models\search\ProyekSearch;
use app\models\SiteSetting;
use app\models\Testimonials;
use app\models\User;
use dmstr\bootstrap\Tabs;
use kartik\mpdf\Pdf;
use Symfony\Component\DomCrawler\Form;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\UploadedFile;

class CariTukangController extends BaseController
{
    use UploadFile;

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        $this->layout = '@app/views/layouts-home/main';
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                // 'only' => ['logout', 'design-bangunan'],
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'index', 'form-keperluan', 'check-valid'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'tukang-saya', 'form-keperluan', 'view', 'bayar-dp', 'bayar-total', 'form-revisi', 'cetak-invoice', 'laporan-pekerjaan', 'batal-layanan'], // add all actions to take guest to login page
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],

            ],

        ];
    }

    public function actionCheckValid($id)
    {
        if (($model = PekerjaanSameday::find()->where(['kode_unik' => $id])->one()) == null) {
            throw new HttpException(404, 'The requested page does not exist.');
        }

        return $this->render('check-valid', ["model" => $model]);
    }

    public function actionIndex()
    {
        $this->view->title = 'Home i - Cari Tukang';
        $testimonials = Testimonials::find()->all();
        $partners = Partner::find()->all();
        $models = MasterKategoriLayananSameday::find()->all();
        return $this->render('index', [
            'testimonials' => $testimonials,
            'partners' => $partners,
            'models' => $models
        ]);
    }

    public function actionTukangSaya()
    {
        $this->view->title = 'Home i - Tukang Saya';
        $models = PekerjaanSameday::find()->where(['id_pelanggan' => \Yii::$app->user->identity->id])->orderBy(['created_at' => SORT_DESC])->all();

        return $this->render('tukang-saya', [
            'models' => $models
        ]);
    }

    public function actionFormKeperluan($id)
    {
        $this->view->title = 'Home i - Form Keperluan';
        date_default_timezone_set("Asia/Jakarta");
        $testimonials = Testimonials::find()->all();
        $partners = Partner::find()->all();
        $kategori = MasterKategoriLayananSameday::find()->where(['slug' => $id])->one();
        // $pembayarans = MasterPembayaran::find()->where(['status' => 1])->all();
        if ($kategori == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }

        $model = new PekerjaanSameday();
        $model->scenario = $model::SCENARIO_CREATE;

        try {
            if ($model->load($_POST)) :
                $file = UploadedFile::getInstance($model, "foto_lokasi");
                if ($file) {
                    $response = $this->uploadImage($file, "layanan-sameday");
                    if ($response->success == false) {
                        throw new HttpException(419, "Gambar gagal diunggah");
                    }
                    $model->foto_lokasi = $response->filename;
                    $model->id_pelanggan = Yii::$app->user->identity->id;
                    $model->id_kategori = $kategori->id;
                }
                $model->kode_unik = Yii::$app->security->generateRandomString(30);
                if ($model->validate()) :
                    $model->save();
                    \app\components\Notif::log(
                        null,
                        \Yii::$app->user->identity->name . " sedang mencari tukang",
                        "Hallo Admin, " . \Yii::$app->user->identity->name . " sedang mencari tukang. Silahkan cek data layanan sameday.",
                        [
                            "controller" => "pekerjaan-sameday/view",
                            "android_route" => null,
                            "params" => [
                                "id" => $model->id
                            ]
                        ]
                    );
                    toastSuccess("Data Berhasil Disimpan");
                    return $this->redirect(['view', 'id' => $model->kode_unik]);
                endif;
                toastError("Data Tidak Berhasil Disimpan");
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->render('form-keperluan', [
            'testimonials' => $testimonials,
            'partners' => $partners,
            'kategori' => $kategori,
            'model' => $model,
            // 'pembayarans' => $pembayarans
        ]);
    }

    public function actionView($id)
    {
        $this->view->title = 'Home i - Detail Keperluan';
        $model = $this->findTukang($id);
        if (($model->status == $model::STATUS_PEMBAYARAN_DP && $model->deadline_pembayaran_dp != null) && time() > strtotime($model->deadline_pembayaran_dp)) {
            $model->status = $model::STATUS_PEMBAYARAN_DP_EXPIRED;
            $model->save();
        }
        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionBayarDp($id)
    {
        $this->view->title = 'Home i - Bayar DP';
        date_default_timezone_set("Asia/Jakarta");
        $model = $this->findTukang($id);
        $model->scenario = $model::SCENARIO_BAYAR_DP;
        $pengaturan = SiteSetting::find()->one();
        $pembayarans = MasterPembayaran::find()->where(['status' => 1])->all();
        if ($model->bukti_dp != null && $model->revisi_pembayaran_dp == null) {
            \Yii::$app->getSession()->setFlash(
                'error',
                'DP Telah Dibayar'
            );
            return $this->redirect(['view', 'id' => $model->kode_unik]);
        }
        if ($model->nominal_dp == null) {
            \Yii::$app->getSession()->setFlash(
                'error',
                'DP Belum Ditentukan oleh Admin'
            );
            return $this->redirect(['view', 'id' => $model->kode_unik]);
        }

        $oldbukti = $model->bukti_dp;

        try {
            if ($model->load($_POST)) :
                //set deadline bayar berdasarkan waktu dibuat
                // $waktu_awal = strtotime($model->created_at);
                // $deadline_bayar = date("Y-m-d H:i:s", strtotime("+$pengaturan->batas_pembayaran minutes", $waktu_awal));
                if ($model->deadline_pembayaran_dp != null) {
                    $to_time = strtotime($model->deadline_pembayaran_dp);
                    $from_time = strtotime(date('Y-m-d H:i:s'));
                    $minute = round(abs($to_time - $from_time) / 60, 2);

                    if ($minute > $pengaturan->batas_pembayaran) {
                        toastError("Order Telah Kadaluarsa. Silahkan Order kembali.");
                        $model->status = $model::STATUS_PEMBAYARAN_DP_EXPIRED;
                        if ($model->validate()) {
                            $model->save();
                        }
                        return $this->redirect(['view', 'id' => $model->kode_unik]);
                    }
                }

                if ($model->status == $model::STATUS_PEMBAYARAN) {
                    $this->deleteOne($oldbukti);
                }

                $instance = UploadedFile::getInstance($model, 'bukti_dp');

                $ext = end(explode(".", $instance->name));
                $response = $this->uploadFile($instance, "layanan_sameday/pembayaran_dp/{$model->kode_unik}");
                if ($response->success == false) {
                    toastError("Dokumen gagal diunggah");
                    goto end;
                }
                $model->bukti_dp = $response->filename;
                $model->tanggal_pembayaran_dp = date('Y-m-d H:i:s');
                $model->revisi_pembayaran_dp = null;
                $model->status = $model::STATUS_PEMBAYARAN_DP;
                $model->deadline_pembayaran_dp = null;

                if ($model->validate()) :
                    \app\components\Notif::log(
                        null,
                        \Yii::$app->user->identity->name . " telah mengunggah bukti pembayaran DP Layanan Tukang",
                        "Hallo Admin, " . \Yii::$app->user->identity->name . " telah mengunggah bukti pembayaran DP Layanan Tukang. Silahkan cek data layanan tukang sameday",
                        [
                            "controller" => "pekerjaan-sameday/view",
                            "android_route" => null,
                            "params" => [
                                "id" => $model->id
                            ]
                        ]
                    );

                    \app\components\Notif::log(
                        $model->id_tukang,
                        \Yii::$app->user->identity->name . " telah mengunggah bukti pembayaran DP Layanan Tukang",
                        "Hallo Admin, " . \Yii::$app->user->identity->name . " telah mengunggah bukti pembayaran DP Layanan Tukang. Silahkan cek data layanan tukang sameday",
                        [
                            "controller" => "pekerjaan-sameday/view",
                            "android_route" => null,
                            "params" => [
                                "id" => $model->id
                            ]
                        ]
                    );

                    $model->save();
                    \Yii::$app->getSession()->setFlash(
                        'success',
                        'Data Tersimpan'
                    );
                    return $this->redirect(['view', 'id' => $model->kode_unik]);
                endif;
                \Yii::$app->getSession()->setFlash(
                    'error',
                    'Terjadi Kesalahan'
                );
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:

        return $this->render('bayar-dp', [
            'model' => $model,
            'pembayarans' => $pembayarans
        ]);
    }

    public function actionFormRevisi($id)
    {
        $this->view->title = 'Home i - Form Revisi';
        date_default_timezone_set("Asia/Jakarta");
        $model = $this->findTukang($id);
        $model->scenario = $model::SCENARIO_REVISI;

        if ($model->status < $model::STATUS_PEMBAYARAN_DP && $model->status > $model::STATUS_PEMBAYARAN_DP && $model->status < $model::STATUS_DIAJUKAN || $model->revisi_pembayaran != null) {
            \Yii::$app->getSession()->setFlash(
                'error',
                'Belum Dapat Merevisi'
            );
            return $this->redirect(['view', 'id' => $model->kode_unik]);
        }
        try {
            if ($model->load($_POST)) :

                if ($model->status == $model::STATUS_PEMBAYARAN_DP && $model->catatan_revisi != null) {
                    $model->status = $model::STATUS_PEMBAYARAN_DP;
                } else {
                    $model->status = $model::STATUS_DIAJUKAN;
                }

                if ($model->validate()) :
                    if ($model->status == $model::STATUS_PEMBAYARAN_DP && $model->catatan_revisi != null) {
                        \app\components\Notif::log(
                            null,
                            \Yii::$app->user->identity->name . " memberi revisi pada layanan proyeknya",
                            "Hallo Admin, " . \Yii::$app->user->identity->name . " memberi revisi pada layanan proyeknya di Layanan Tukang. Silahkan cek data layanan tukang sameday",
                            [
                                "controller" => "pekerjaan-sameday/view",
                                "android_route" => null,
                                "params" => [
                                    "id" => $model->id
                                ]
                            ]
                        );
                    } else {
                        \app\components\Notif::log(
                            null,
                            \Yii::$app->user->identity->name . " memberi revisi pada proyeknya",
                            "Hallo Admin, " . \Yii::$app->user->identity->name . " memberi revisi pada proyeknya di Layanan Tukang. Silahkan cek data layanan tukang sameday",
                            [
                                "controller" => "pekerjaan-sameday/view",
                                "android_route" => null,
                                "params" => [
                                    "id" => $model->id
                                ]
                            ]
                        );

                        \app\components\Notif::log(
                            $model->id_tukang,
                            \Yii::$app->user->identity->name . " memberi revisi pada proyeknya",
                            "Hallo Admin, " . \Yii::$app->user->identity->name . " memberi revisi pada proyeknya di Layanan Tukang. Silahkan cek data layanan tukang sameday",
                            [
                                "controller" => "pekerjaan-sameday/view",
                                "android_route" => null,
                                "params" => [
                                    "id" => $model->id
                                ]
                            ]
                        );
                    }

                    $model->save();
                    \Yii::$app->getSession()->setFlash(
                        'success',
                        'Data Tersimpan'
                    );
                    return $this->redirect(['view', 'id' => $model->kode_unik]);
                endif;
                \Yii::$app->getSession()->setFlash(
                    'error',
                    'Terjadi Kesalahan'
                );
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:

        return $this->render('form-revisi', [
            'model' => $model
        ]);
    }

    public function actionBayarTotal($id)
    {
        $this->view->title = 'Home i - Bayar Total';
        date_default_timezone_set("Asia/Jakarta");
        $model = $this->findTukang($id);
        $pembayarans = MasterPembayaran::find()->where(['status' => 1])->all();
        $model->scenario = $model::SCENARIO_BAYAR_TOTAL;
        // if ($model->bukti_pembayaran != null) {
        //     \Yii::$app->getSession()->setFlash(
        //         'error',
        //         'DP Telah Dibayar'
        //     );
        //     return $this->redirect(['view', 'id' => $model->kode_unik]);
        // }
        if (in_array($model->status, [PekerjaanSameday::STATUS_DIAJUKAN, PekerjaanSameday::STATUS_PEMBAYARAN]) == false) {
            \Yii::$app->getSession()->setFlash(
                'error',
                'Belum Dapat Membayar'
            );
            return $this->redirect(['view', 'id' => $model->kode_unik]);
        }
        // if ($model->revisi_pembayaran == null) {
        //     \Yii::$app->getSession()->setFlash(
        //         'error',
        //         'Menunggu Konfirmasi'
        //     );
        //     return $this->redirect(['view', 'id' => $model->kode_unik]);
        // }

        $oldbukti = $model->bukti_pembayaran;

        try {
            if ($model->load($_POST)) :

                if ($model->status == $model::STATUS_PEMBAYARAN && $model->revisi_pembayaran != null) {
                    unlink(Yii::getAlias("@app/web/uploads/") . $oldbukti);
                }

                $instance = UploadedFile::getInstance($model, 'bukti_pembayaran');

                $ext = end(explode(".", $instance->name));
                $response = $this->uploadFile($instance, "layanan_sameday/pembayaran_pembayaran/{$model->kode_unik}");
                if ($response->success == false) {
                    toastError("Dokumen gagal diunggah");
                    goto end;
                }
                $model->bukti_pembayaran = $response->filename;
                $model->tanggal_pembayaran = date('Y-m-d H:i:s');
                $model->revisi_pembayaran = null;

                $model->status = $model::STATUS_PEMBAYARAN;

                if ($model->validate()) :
                    \app\components\Notif::log(
                        null,
                        \Yii::$app->user->identity->name . " telah mengunggah bukti pembayaran Layanan Tukang",
                        "Hallo Admin, " . \Yii::$app->user->identity->name . " telah mengunggah bukti pembayaran Layanan Tukang. Silahkan cek data layanan tukang sameday",
                        [
                            "controller" => "pekerjaan-sameday/view",
                            "android_route" => null,
                            "params" => [
                                "id" => $model->id
                            ]
                        ]
                    );

                    \app\components\Notif::log(
                        $model->id_tukang,
                        \Yii::$app->user->identity->name . " pekerjaan sudah dikonfirmasi pemilik di Layanan Tukang",
                        "Hallo Admin, " . \Yii::$app->user->identity->name . " pekerjaan sudah dikonfirmasi pemilik di Layanan Tukang. Silahkan cek data layanan tukang sameday",
                        [
                            "controller" => "pekerjaan-sameday/view",
                            "android_route" => null,
                            "params" => [
                                "id" => $model->id
                            ]
                        ]
                    );

                    $model->save();
                    \Yii::$app->getSession()->setFlash(
                        'success',
                        'Data Tersimpan'
                    );
                    return $this->redirect(['view', 'id' => $model->kode_unik]);
                endif;
                \Yii::$app->getSession()->setFlash(
                    'error',
                    'Terjadi Kesalahan'
                );
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:

        return $this->render('bayar-total', [
            'model' => $model,
            'pembayarans' => $pembayarans
        ]);
    }

    public function actionCetakInvoice($id)
    {
        $model = $this->findTukang($id);


        $setting = SiteSetting::find()->one();


        $content = $this->renderPartial('pdf/_reportView', [
            'model' => $model,
            'setting' => $setting,
        ]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            'marginHeader' => 0,
            'marginFooter' => 1,
            'marginTop' => 5,
            'marginBottom' => 5,
            'marginLeft' => 5,
            'marginRight' => 3,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => [
                // 'homepage/css/invoice.css',
                'homepage/vendor/bootstrap4/bootstrap.min.css',
                'homepage/css/style.css',
                'homepage/css/style-print.css',
            ],
            // any css to be embedded if required
            'cssInline' => '.text-terbilang{text-align:left ;font-style: italic;font-weight:500}',
            // set mPDF properties on the fly
            //  'options' => ['title' => 'Krajee Report Title'],
            'options' => [
                'defaultheaderline' => 0,  //for header
                'defaultfooterline' => 0,  //for footer
            ],
            // call mPDF methods on the fly
            // 'methods' => [

            //     'SetHeader' => $this->renderPartial('pdf/header'),
            //     'SetFooter' => $this->renderPartial('pdf/footer'),
            // ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    public function actionLaporanPekerjaan($id)
    {
        $model = $this->findTukang($id);


        $setting = SiteSetting::find()->one();


        $content = $this->renderPartial('pdf/_reportPekerjaan', [
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

        // dd("@page {background-image: url('" . Yii::getAlias("@link/pdf-status/$status") . "');background-repeat: no-repeat;background-position: center center}");

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            'marginHeader' => 0,
            'marginFooter' => 1,
            'marginTop' => 5,
            'marginBottom' => 5,
            'marginLeft' => 5,
            'marginRight' => 3,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => [
                'homepage/css/invoice.css',
                'homepage/vendor/bootstrap4/bootstrap.min.css',
                'homepage/css/style.css',
                'homepage/css/style-print.css',
            ],
            'cssInline' => "@page :first {background-image: url('" . Yii::getAlias("@link/pdf-status/$status") . "');background-repeat: no-repeat;background-position: center center}",
            // any css to be embedded if required
            // 'cssInline' => '.kv-heading-1{font-size:18px;}',
            // set mPDF properties on the fly
            //  'options' => ['title' => 'Krajee Report Title'],
            'options' => [
                'defaultheaderline' => 0,  //for header
                'defaultfooterline' => 0,  //for footer
            ],
            // call mPDF methods on the fly
            // 'methods' => [

            //     'SetHeader' => $this->renderPartial('pdf/header'),
            //     'SetFooter' => $this->renderPartial('pdf/footer'),
            // ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    public function actionBatalLayanan($id)
    {
        $this->view->title = 'Home i - Batalkan Layanan';

        $model = $this->findTukang($id);
        $model->scenario = $model::SCENARIO_BATAL_LAYANAN;

        try {
            // if ($model->load($_POST)) :

            $model->status = $model::STATUS_PELENGKAPAN_DATA;

            if ($model->validate()) :
                \app\components\Notif::log(
                    null,
                    \Yii::$app->user->identity->name . " telah membatalkan Layanan Tukang",
                    "Hallo Admin, " . \Yii::$app->user->identity->name . " telah membatalkan Layanan Tukang. Silahkan cek data layanan tukang sameday",
                    [
                        "controller" => "pekerjaan-sameday/view",
                        "android_route" => null,
                        "params" => [
                            "id" => $model->id
                        ]
                    ]
                );

                $model->save();
                \Yii::$app->getSession()->setFlash(
                    'success',
                    'Data Tersimpan'
                );
                return $this->redirect(['view', 'id' => $model->kode_unik]);
            endif;
            \Yii::$app->getSession()->setFlash(
                'error',
                'Terjadi Kesalahan'
            );
            // elseif (!\Yii::$app->request->isPost) :
            //     $model->load($_GET);
            // endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->redirect(['view', 'id' => $model->kode_unik]);
    }

    protected function findTukang($id)
    {
        if (($model = PekerjaanSameday::find()->where(['kode_unik' => $id])->andWhere(['id_pelanggan' => \Yii::$app->user->identity->id])->one()) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
