<?php

namespace app\controllers\home;

date_default_timezone_set("Asia/Jakarta");

use app\components\HttpHelper;
use app\components\UploadFile;
use app\models\ApprovalSebelumPekerjaan;
use app\models\MasterPembayaran;
use app\models\Notification;
use app\models\Proyek;
use app\models\ProyekCctv;
use app\models\ProyekCicilan;
use app\models\ProyekDp;
use app\models\ProyekGaleri;
use app\models\ProyekKemajuan;
use app\models\ProyekKemajuanTarget;
use app\models\ProyekKeuanganKeluar;
use app\models\ProyekTermin;
use app\models\search\ProyekSearch;
use app\models\User;
use dmstr\bootstrap\Tabs;
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

/**
 * This is the class sc controller "BeritaController".
 */
class ProyekSayaController extends BaseController
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'profile', 'pengajuan-design-bangunan', 'detail-proyek', 'keuangan', 'pantau-proyek', 'lihat-cctv', 'pembayaran', 'pembayaran-termin', 'bayar-termin', 'detail-keuangan-keluar', 'detail-keuangan-masuk', 'proyek-selesai', 'revisi-proyek', 'setuju-approval-proyek', 'revisi-approval-proyek'], // add all actions to take guest to login page
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],

            ],

        ];
    }

    public function actionIndex()
    {
        $this->view->title = 'Home i - Proyek Saya';
        $proyeks = Proyek::find()->where(['id_user' => \Yii::$app->user->identity->id])->andWhere(['flag' => 1])->orderBy(['created_at' => SORT_DESC])->all();
        return $this->render('proyek-saya', [
            'proyeks' => $proyeks
        ]);
    }

    public function actionPantauProyek($id)
    {
        $this->view->title = 'Home i - CCTV Proyek';
        // $this->view->title = 'Home i - Detail Proyek';
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember();
        Tabs::rememberActiveState();

        $model = Proyek::find()->where(['kode_unik' => $id])->andWhere(['id_user' => \Yii::$app->user->identity->id])->andWhere(['flag' => 1])->one();
        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        // dd($model->proyekCctvs);
        return $this->render('cctv', [
            'model' => $model,
        ]);
    }

    public function actionLihatCctv($id)
    {
        $model = ProyekCctv::find()->where(['id' => $id])->andWhere(['flag' => 1])->one();
        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        return $this->renderAjax('lihat-cctv', [
            'model' => $model,
        ]);
    }

    public function actionDetailProyek($id)
    {
        date_default_timezone_set('Asia/Jakarta');
        // $this->view->title = 'Home i - Detail Proyek';
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember();
        Tabs::rememberActiveState();
        $model = Proyek::find()->where(['kode_unik' => $id])->andWhere(['id_user' => \Yii::$app->user->identity->id])->one();
        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        $model_targets = ProyekKemajuanTarget::find()->where(['kode_proyek' => $model->kode_unik])->all();

        $target_perminggu = [];
        foreach ($model_targets as $target) {
            $target_perminggu[] = number_format($target->jumlah_target, 2);
        }

        $dari = $model->tanggal_awal_kontrak;
        $akhir = $model->tanggal_akhir_kontrak;
        $sampai = date('Y-m-d', strtotime('+7 day', strtotime($akhir)));
        $daftar_tanggal = \app\components\Tanggal::dateRange($dari, $sampai, '+8 day');

        $last_input = \app\models\ProyekKemajuanHarian::find()
            ->where(['id_proyek' => $model->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->select('created_at')
            ->one();

        $progress_perminggu = array();
        $progress_minggu_ini = array();
        $total_progress_mingguan = 0;
        $progress_perminggu[] = 0; // start from 0
        foreach ($daftar_tanggal as $key => $tanggal) {
            $next_week = date('Y-m-d', strtotime('+7 day', strtotime($tanggal)));
            $awal =  $model->getRealisasiByRangeDate($tanggal, $next_week);
            $total_progress_mingguan = $total_progress_mingguan + $awal;

            if ($last_input != null && strtotime($last_input->created_at) >= strtotime($tanggal)) {
                $progress_perminggu[] =  number_format($total_progress_mingguan, 2);
            } else {
                // $progress_perminggu[] = 0;
            }

            $check_date = \app\components\Tanggal::checkBetweenDate($tanggal, $next_week);

            if ($check_date == true) {
                $progress_minggu_ini['data'] = number_format($model->getRealisasiByRangeDate($tanggal, $next_week), 2);
                $target = ProyekKemajuanTarget::find()
                    ->where(['kode_proyek' => $model->kode_unik])
                    ->andWhere(['between', 'tanggal_awal', $tanggal, $next_week])->one();
                $progress_minggu_ini['deviasi'] = end($progress_perminggu) - $target->jumlah_target;
                $progress_minggu_ini['tanggal_awal'] = $tanggal;
                $progress_minggu_ini['tanggal_akhir'] = $next_week;
            }
        }

        $daftar_tanggal = \app\components\Tanggal::dateRange($dari, $sampai, '+7 day');

        if ($model) :
            return $this->render('detail-proyek', [
                'model' => $model,
                'daftar_tanggal' => json_encode($daftar_tanggal),
                'target_perminggu' => json_encode($target_perminggu),
                'progress_perminggu' => json_encode($progress_perminggu),
                'progress_minggu_ini' => $progress_minggu_ini,
            ]);
        else :
            throw new ForbiddenHttpException();
        endif;
    }

    public function actionDetailKeuanganKeluar($id)
    {
        if (Yii::$app->request->isAjax) {
            $render = "renderAjax";
            $view = "keuangan/keuangan-keluar_ajax";
        } else {
            $render = "render";
            $view = "keuangan/keuangan-keluar";
        }

        return $this->$render($view, [
            'model' => $this->findKeuanganKeluar($id),
        ]);
    }


    private function progressHarian($first, $last, $step = '+1 day', $format = 'Y-m-d')
    {
        date_default_timezone_set('Asia/Jakarta');
        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);

        while ($current <= $last) {
            $dates[] = date($format, $current);
            $current = strtotime($step, $current);
        }
        return $dates;
    }


    public function actionKeuangan($id)
    {
        $model = Proyek::find()->where(['kode_unik' => $id])->andWhere(['id_user' => \Yii::$app->user->identity->id])->one();
        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        if ($model == null) throw new HttpException(404);
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember();
        Tabs::rememberActiveState();

        $total_anggaran = $model->nilai_kontrak;
        $total_pemasukkan = intval($model->getProyekKeuanganMasuks()
            ->andWhere(['is', 't_proyek_keuangan_masuk.deleted_at', null])
            ->sum('jumlah'));
        $total_pengeluaran = (intval($model->getProyekKeuanganKeluars()
            ->andWhere(['and', ['is', 't_proyek_keuangan_keluar.deleted_at', null], ['t_proyek_keuangan_keluar.tipe' => 0]])
            ->sum('total_jumlah'))
            + intval($model->getProyekKeuanganKeluars()
                ->andWhere(['and', ['is', 't_proyek_keuangan_keluar.deleted_at', null], ['t_proyek_keuangan_keluar.tipe' => 1]])
                ->sum('total_dibayarkan')));
        $sisa_anggaran = $total_pemasukkan - $total_pengeluaran;
        $total_hutang = intval($model->getProyekKeuanganKeluars()
            ->andWhere(['and', ['is', 't_proyek_keuangan_keluar.deleted_at', null], ['t_proyek_keuangan_keluar.tipe' => 1]])
            ->sum('total_jumlah')) - intval($model->getProyekKeuanganKeluars()
            ->andWhere(['and', ['is', 't_proyek_keuangan_keluar.deleted_at', null], ['t_proyek_keuangan_keluar.tipe' => 1]])
            ->sum('total_dibayarkan'));


        return $this->render(
            'keuangan/view',
            compact('model', 'total_anggaran', 'sisa_anggaran', 'total_pemasukkan', 'total_pengeluaran', 'total_hutang')
        );
    }

    public function actionPembayaran($id)
    {
        $model = Proyek::find()->where(['kode_unik' => $id])->andWhere(['id_user' => \Yii::$app->user->identity->id])->one();
        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        Url::remember();
        Tabs::rememberActiveState();

        return $this->render('pembayaran/view', compact('model'));
    }

    public function actionUploadPembayaranDp($id)
    {
        $this->view->title = 'Pembayaran DP';
        $model = Proyek::find()->where(['kode_unik' => $id])->andWhere(['id_user' => \Yii::$app->user->identity->id])->one();
        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        if ($model->status_pembayaran == 1 || $model->status_pembayaran == 2) {
            \Yii::$app->getSession()->setFlash(
                'error',
                'DP Telah Dibayar'
            );
            return $this->redirect(['pembayaran', 'id' => $model->kode_unik]);
        }
        if ($model->nilai_dp == null) {
            \Yii::$app->getSession()->setFlash(
                'error',
                'DP Belum Ditentukan oleh Admin'
            );
            return $this->redirect(['pembayaran', 'id' => $model->kode_unik]);
        }
        if ($model == null) throw new HttpException(404);
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();

        if ($model->status_pembayaran != 3) {
            $model->scenario = $model::SCENARIO_BAYARDP;
        } else {
            $model->scenario = $model::SCENARIO_BAYARDPULANG;
        }
        $oldbukti = $model->bukti_pembayaran;

        try {
            if ($model->load($_POST)) :

                if ($model->status_pembayaran == 3) {
                    unlink(Yii::getAlias("@app/web/uploads/") . $oldbukti);
                }

                $instance = UploadedFile::getInstance($model, 'bukti_pembayaran');

                $ext = end(explode(".", $instance->name));
                $response = $this->uploadFile($instance, "project/pembayaran_dp/{$model->kode_unik}");
                if ($response->success == false) {
                    toastError("Dokumen gagal diunggah");
                    goto end;
                }
                $model->bukti_pembayaran = $response->filename;
                $oldtotal = $model->total_pembayaran;
                $model->total_pembayaran = $oldtotal + $model->nilai_dp;
                // if ($model->setuju_tor != 1) {
                //     \Yii::$app->getSession()->setFlash(
                //         'error',
                //         'Term of Reference Belum Disetujui'
                //     );
                //     return $this->redirect(['pembayaran', 'id' => $model->kode_unik]);
                // }

                // dd($model->bulan);

                // $jumlah_bayar = ($model->nilai_kontrak - $model->nilai_dp) / $model->bulan;

                // if ($model->status_pembayaran != 3) {


                //     for ($i = 1; $i <= $model->bulan; $i++) {

                //         $cicilan = new ProyekCicilan();
                //         $cicilan->scenario = $cicilan::SCENARIO_GENERATE;
                //         $cicilan->kode_unik = Yii::$app->security->generateRandomString(30);
                //         $cicilan->id_proyek = $model->id;
                //         $cicilan->kode_proyek = $model->kode_unik;
                //         $cicilan->id_user = $model->id_user;
                //         $cicilan->cicilan_ke = "Cicilan ke-" . $i;
                //         $cicilan->jumlah_bayar = $jumlah_bayar;
                //         $cicilan->save();
                //     }
                // }
                $model->status_pembayaran = 1;



                if ($model->validate()) :
                    $model->save();
                    \Yii::$app->getSession()->setFlash(
                        'success',
                        'Data Tersimpan'
                    );
                    return $this->redirect(['pembayaran', 'id' => $model->kode_unik]);
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
        return $this->render('pembayaran/bayar-dp', $model->render());
    }

    public function actionBayarTermin($id)
    {
        date_default_timezone_set("Asia/Jakarta");
        $this->view->title = 'Pembayaran Cicilan';
        $model = ProyekTermin::find()->where(['kode_unik' => $id])->andWhere(['user_id' => \Yii::$app->user->identity->id])->one();
        $pembayarans = MasterPembayaran::find()->where(['status' => 1])->all();

        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }

        $status = $model->status;
        if ($status == 1  || $status == 2) {
            \Yii::$app->getSession()->setFlash(
                'error',
                'Termin Telah Dibayar'
            );
            return $this->redirect(['pembayaran', 'id' => $model->kode_proyek]);
        }
        if ($model->proyek->total_pembayaran == $model->proyek->nilai_kontrak) {
            \Yii::$app->getSession()->setFlash(
                'error',
                'Pembayaran Tidak Dapat Melebihi Nilai Kontrak'
            );
            return $this->redirect(['pembayaran', 'id' => $model->kode_proyek]);
        }

        $proyek = Proyek::find()->where(['kode_unik' => $model->kode_proyek])->andWhere(['id_user' => \Yii::$app->user->identity->id])->one();
        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        $proyek->scenario = $proyek::SCENARIO_BAYAR_TERMIN;

        $model->scenario = $model::SCENARIO_BAYAR_TERMIN;
        $oldbukti = $model->bukti_pembayaran;

        try {
            if ($model->load($_POST)) :
                if ($model->status == 3) {
                    unlink(Yii::getAlias("@app/web/uploads/") . $oldbukti);
                    // $this->deleteOne($oldbukti);
                }
                $instance = UploadedFile::getInstance($model, 'bukti_pembayaran');

                $ext = end(explode(".", $instance->name));
                $response = $this->uploadFile($instance, "project/pembayaran_termin/{$model->kode_unik}");
                if ($response->success == false) {
                    toastError("Dokumen gagal diunggah");
                    goto end;
                }
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

                if ($model->validate()) :
                    $proyek->save();

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


                    $model->save();
                    \Yii::$app->getSession()->setFlash(
                        'success',
                        'Data Tersimpan'
                    );
                    return $this->redirect(['pembayaran', 'id' => $model->kode_proyek]);
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
        return $this->render(
            'pembayaran/bayar-termin',
            // $model->render()
            [
                'model' => $model,
                'pembayarans' => $pembayarans
            ]
        );
    }

    public function actionRevisiProyek($id)
    {
        $model = Proyek::find()->where(['kode_unik' => $id])->andWhere(['id_user' => \Yii::$app->user->identity->id])->one();
        if ($model->status != 1) {
            toastError("Validasi gagal");
            goto end;
        }
        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        $model->scenario = $model::SCENARIO_PENGAJUAN_REVISI;

        if ($model->load(Yii::$app->request->post())) {
            $model->status = $model::STATUS_REVISI_PROYEK;
            if ($model->validate() == false) {
                \Yii::$app->getSession()->setFlash(
                    'error',
                    'Data Tidak Tersimpan'
                );
                goto end;
            }
            $model->save();
            \app\components\Notif::log(
                null,
                "{$model->user->name} Telah Merevisi proyek",
                "{$model->user->name} Telah Merevisi proyek",
                [
                    "controller" => "proyek/view",
                    "android_route" => null,
                    "params" => [
                        "id" => $model->id
                    ]
                ]
            );
            \Yii::$app->getSession()->setFlash(
                'success',
                'Data Tersimpan'
            );
            return $this->redirect(['detail-proyek', 'id' => $id]);
        }

        end:
        return $this->render('form-revisi', $model->render());
    }

    public function actionProyekSelesai($id)
    {
        $model = Proyek::find()->where(['kode_unik' => $id])->andWhere(['id_user' => \Yii::$app->user->identity->id])->one();
        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        if ($model->status != 1) {
            \Yii::$app->getSession()->setFlash(
                'error',
                'Data Tidak Tersimpan'
            );
            return $this->redirect(['detail-proyek', 'id' => $id]);
        }
        $model->scenario = $model::SCENARIO_PROYEK_SELESAI;

        $model->catatan_revisi = null;
        $model->status = $model::STATUS_SELESAI;
        if ($model->validate() == false) {
            \Yii::$app->getSession()->setFlash(
                'error',
                'Data Tidak Tersimpan'
            );
            return $this->redirect(['detail-proyek', 'id' => $id]);
        }
        $model->save();
        \app\components\Notif::log(
            null,
            "{$model->user->name} Telah Me-konfirmasi Proyek Selesai",
            "{$model->user->name} Telah Me-konfirmasi Proyek Selesai",
            [
                "controller" => "proyek/view",
                "android_route" => null,
                "params" => [
                    "id" => $model->id
                ]
            ]
        );
        \Yii::$app->getSession()->setFlash(
            'success',
            'Data Tersimpan'
        );
        return $this->redirect(['detail-proyek', 'id' => $id]);
        // end:
        // return $this->render('form_survey', $model->render());
    }

    public function actionRevisiApprovalProyek($id)
    {
        $model = ApprovalSebelumPekerjaan::findOne($id);
        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        if ($model->status != 0 || $model->proyek->id_user != \Yii::$app->user->identity->id) {
            toastError("Validasi gagal");
            goto end;
        }
        $model->scenario = $model::SCENARIO_UPDATE;

        if ($model->load(Yii::$app->request->post())) {
            $model->status = $model::STATUS_REJECTED;
            if ($model->validate() == false) {
                \Yii::$app->getSession()->setFlash(
                    'error',
                    'Data Tidak Tersimpan'
                );
                goto end;
            }
            $model->save();
            \app\components\Notif::log(
                null,
                "{$model->proyek->user->name} Telah Merevisi Approval proyek",
                "{$model->proyek->user->name} Telah Merevisi Approval proyek",
                [
                    "controller" => "proyek/view",
                    "android_route" => null,
                    "params" => [
                        "id" => $model->proyek->id
                    ]
                ]
            );
            \Yii::$app->getSession()->setFlash(
                'success',
                'Data Tersimpan'
            );
            return $this->redirect(['detail-proyek', 'id' => $model->proyek->kode_unik]);
        }

        end:
        return $this->render('form-revisi-approval', [
            'model' => $model,
            'modelProyek' => $model->proyek
        ]);
    }

    public function actionSetujuApprovalProyek($id)
    {
        $model = ApprovalSebelumPekerjaan::findOne($id);
        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        if ($model->status != 0 || $model->proyek->id_user != \Yii::$app->user->identity->id) {
            toastError("Validasi gagal");
            return $this->redirect(['detail-proyek', 'id' => $model->proyek->kode_unik]);
        }
        $model->scenario = $model::SCENARIO_UPDATE;

        $model->revisi = null;
        $model->status = $model::STATUS_APPROVED;
        if ($model->validate() == false) {
            \Yii::$app->getSession()->setFlash(
                'error',
                'Data Tidak Tersimpan'
            );
            return $this->redirect(['detail-proyek', 'id' => $model->proyek->kode_unik]);
        }
        $model->save();
        \app\components\Notif::log(
            null,
            "{$model->proyek->user->name} Telah Menyetujui Approval proyek",
            "{$model->proyek->user->name} Telah Menyetujui Approval proyek",
            [
                "controller" => "proyek/view",
                "android_route" => null,
                "params" => [
                    "id" => $model->proyek->id
                ]
            ]
        );
        \Yii::$app->getSession()->setFlash(
            'success',
            'Data Tersimpan'
        );
        return $this->redirect(['detail-proyek', 'id' => $model->proyek->kode_unik]);
        // end:
        // return $this->render('form_survey', $model->render());
    }

    protected function findKeuanganKeluar($id)
    {
        if (($model = ProyekKeuanganKeluar::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
