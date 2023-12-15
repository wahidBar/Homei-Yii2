<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\home;

date_default_timezone_set("Asia/Jakarta");

use app\components\Angka;
use app\components\Constant;
use app\components\UploadFile;
use app\models\IsianLanjutan;
use app\models\IsianLanjutanRuangan;
use app\models\MasterPembayaran;
use app\models\MasterRuangan;
use app\models\Notification;
use app\models\Penawaran;
use app\models\PenawaranDetail;
use app\models\Proyek;
use app\models\search\IsianLanjutanSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;

/**
 * IsianLanjutanController implements the CRUD actions for IsianLanjutan model.
 **/
class FormRencanaPembangunanController extends \app\components\productive\DefaultActiveController
{
    use UploadFile;
    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     **/
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
                        'actions' => ['login', 'error', 'valid'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'view', 'tambah-data-pembangunan', 'tanggal-rencana-pembangunan', 'setuju-tor', 'tolak-tor', 'upload-pembayaran-dp'], // add all actions to take guest to login page
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],

            ],

        ];
    }

    /**
     * Aksi untuk menampilkan halaman konfirmasi data rencana pembangunan
     * @return mixed
     */
    public function actionValid($id)
    {
        $model = IsianLanjutan::findOne(["kode_unik" => $id]);
        if ($model == null)
            throw new HttpException(404, 'Halaman yang anda cari tidak ditemukan');
        if ($model->status != $model::STATUS_DEFAULT && $model->status != $model::STATUS_USER_ISI)
            throw new HttpException(403, 'Anda tidak memiliki akses ke halaman ini');

        $data = [];
        $data['nama_awal'] = $model->nama_awal;
        $data['nama_akhir'] = $model->nama_akhir;
        $data['no_hp'] = $model->no_hp;
        $data['list_ruangan'] = implode(", ", MasterRuangan::find()->where(['id' => $model->getListRuangan()])->select('nama')->column());
        $data['konsep_desain'] = $model->konsepDesign->nama_konsep;
        $data['lantai'] = $model->lantai->nama;
        $data['provinsi'] = $model->wilayahProvinsi->nama;
        $data['kota'] = $model->wilayahKota->nama;
        $data['kecamatan'] = $model->wilayahKecamatan->nama;
        $data['desa'] = $model->wilayahDesa->nama;
        $data['detail_alamat_pelanggan'] = $model->alamat_pelanggan;
        $data['detail_alamat_proyek'] = $model->alamat_proyek;
        $data['nama_proyek'] = $model->label;
        $data['anggaran'] = Angka::toReadableHarga($model->budget, false);
        $data['panjang'] = $model->panjang;
        $data['lebar'] = $model->lebar;
        $data['luas'] = $model->luas_tanah;
        $data['keterangan'] = $model->keterangan;

        return $this->render('valid', compact('data'));
    }


    /**
     * Lists all IsianLanjutan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->view->title = 'Homei - Rencana Pembangunan';
        // $rencanas = IsianLanjutan::find()->where(['id_user' => \Yii::$app->user->identity->id])
        // ->andWhere(['not', ['status' => 0]])
        // ->all();

        // SELECT 
        // `t_isian_lanjutan`.`kode_unik` AS `isian_id`, 
        // `t_konsultasi`.`kode_isian_lanjutan`, 
        // `t_konsultasi`.`is_active`, 
        // `t_isian_lanjutan`.`id_user` 
        // FROM `t_isian_lanjutan` 
        // LEFT JOIN `t_konsultasi` 
        // ON t_isian_lanjutan.kode_unik =t_konsultasi.kode_isian_lanjutan 
        // WHERE (`t_isian_lanjutan`.`id_user`=9) AND (`t_konsultasi`.`is_active` = 0)
        // ;

        $query = new Query();
        $query->select([
            't_isian_lanjutan.kode_unik as isian_id',
            't_konsultasi.kode_isian_lanjutan',
            't_konsultasi.is_active',
            't_konsultasi.id_konsultan',
            't_isian_lanjutan.id_user',
            't_isian_lanjutan.label',
            't_isian_lanjutan.luas_tanah',
            't_isian_lanjutan.budget',
            't_isian_lanjutan.status',
            'wilayah_provinsi.nama as provinsi',
            'wilayah_kota.nama as kota',
        ])
            ->from('t_isian_lanjutan')
            ->join(
                'LEFT JOIN',
                't_konsultasi',
                't_isian_lanjutan.kode_unik =t_konsultasi.kode_isian_lanjutan'
            )
            ->join(
                'LEFT JOIN',
                'wilayah_provinsi',
                't_isian_lanjutan.id_wilayah_provinsi =wilayah_provinsi.id'
            )
            ->join(
                'LEFT JOIN',
                'wilayah_kota',
                't_isian_lanjutan.id_wilayah_kota =wilayah_kota.id'
            )
            ->where(['t_isian_lanjutan.id_user' => \Yii::$app->user->identity->id])
            ->andWhere(['t_konsultasi.is_active' => 0])
            ->andWhere(['>=', 't_isian_lanjutan.status', 1])
            ->andWhere(['<', 't_isian_lanjutan.status', 9])
            ->andWhere(['not', ['t_konsultasi.id_konsultan' => null]])
            ->orderBy(['t_isian_lanjutan.created_at' => SORT_DESC]);
        $command = $query->createCommand();
        $rencanas = $command->queryAll();

        // dd($rencanas);

        return $this->render('index', [
            'rencanas' => $rencanas
        ]);
    }

    public function actionView($id)
    {
        $this->view->title = 'Homei - Detail Rencana Pembangunan';
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember();
        Tabs::rememberActiveState();
        // $model = IsianLanjutan::find()->where(['id' => $id])->andWhere(['id_user' => \Yii::$app->user->identity->id])->one();
        $model = $this->findModel($id);
        $model->scenario = $model::SCENARIO_TOLAK;

        $modelBatal = $this->findModel($id);
        $modelBatal->scenario = $modelBatal::SCENARIO_BATAL_PROYEK;
        if ($model == null || $modelBatal == null) {
            throw new ForbiddenHttpException();
        }
        $penawaran = Penawaran::find()->where(['id' => $model->id_penawaran])->one();
        // if ($penawaran == null) {
        //     throw new HttpException(404, "Data tidak ditemukan");
        // }
        $dpenawarans = PenawaranDetail::find()->where(['id_penawaran' => $penawaran->id])->all();

        // dd($model->load($_POST));
        try {
            if ($model->load($_POST)) :
                if ($model->validate()) :
                    // $model->alasan_tolak = $_POST['alasan'];
                    //0 = default
                    //1 = terima
                    //2 = tolak
                    $model->approval_dokumen_tor = 2;
                    $model->status = $model::STATUS_TOR_BUTUH_REVISI;

                    \app\components\Notif::log(
                        null,
                        \Yii::$app->user->identity->name . " telah menolak dokumen TOR",
                        "Hallo Admin, " . \Yii::$app->user->identity->name . " telah menolak dokumen TOR. Silahkan cek data isian lanjutan",
                        [
                            "controller" => "isian-lanjutan/view",
                            "android_route" => null,
                            "params" => [
                                "id" => $model->id
                            ]
                        ]
                    );

                    $model->save();
                    $this->messageUpdateSuccess();
                    return $this->redirect(['view', 'id' => $model->kode_unik]);
                endif;
                $this->messageValidationFailed();
            elseif ($modelBatal->load($_POST)) :
                if ($modelBatal->status < $modelBatal::STATUS_RENCANA_PEMBANGUNAN && $modelBatal->status_pembayaran == 2) {
                    toastError("Tidak Dapat Membatalkan Rencana Pembangunan");
                    return $this->redirect(['view', 'id' => $model->kode_unik]);
                }
                if ($modelBatal->validate()) :
                    $modelBatal->status = $modelBatal::STATUS_TOLAK;

                    \app\components\Notif::log(
                        null,
                        \Yii::$app->user->identity->name . " telah membatalkan proyek",
                        "Hallo Admin, " . \Yii::$app->user->identity->name . " telah membatalkan proyek. Silahkan cek data isian lanjutan",
                        [
                            "controller" => "isian-lanjutan/view",
                            "android_route" => null,
                            "params" => [
                                "id" => $model->id
                            ]
                        ]
                    );

                    $modelBatal->save();
                    $this->messageUpdateSuccess();
                    return $this->redirect(['view', 'id' => $modelBatal->kode_unik]);
                endif;
                $this->messageValidationFailed();
            endif;
            goto end;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }
        end:
        return $this->render('view', [
            'model' => $model,
            'modelBatal' => $modelBatal,
            'penawaran' => $penawaran,
            'dpenawarans' => $dpenawarans
        ]);
    }

    public function actionTambahDataPembangunan()
    {
        $this->view->title = 'Homei - Form Rencana Pembangunan';
        $model = new IsianLanjutan();
        $model->scenario = $model::SCENARIO_CREATE;

        $model->list_ruangan = $oldCategoryIDs = $list_ruangan = $model->getListRuangan();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load($_POST)) :
                $model->budget = str_replace(",", "", $model->budget);
                $model->budget = str_replace("Rp ", "", $model->budget);
                $model->id_user = \Yii::$app->user->identity->id;
                $model->status = $model::STATUS_USER_ISI;
                if ($model->boq_proyek == null && $model->nomor_spk == null && $model->informasi_proyek == null) {
                    toastError("Data Tidak Boleh Kosong!");
                    goto end;
                }
                $model->kode_unik = Yii::$app->security->generateRandomString(30);

                $instance = UploadedFile::getInstance($model, "boq_proyek");
                $response = $this->uploadFile($instance, "boq_proyek/{$model->kode_unik}");
                if ($response->success == false) {
                    toastError("Dokumen gagal diunggah");
                    goto end;
                }
                $model->boq_proyek = $response->filename;
                $model->status_boq = 1;
                $model->is_beli_material = 1;
                if ($model->validate()) :
                    $model->save();
                    // list_ruangan
                    $list_ruangan = Yii::$app->request->post('IsianLanjutan')['list_ruangan'];
                    $deletedCategoryIDs = array_diff($oldCategoryIDs, $list_ruangan);
                    foreach ($list_ruangan as $cat) {
                        $exist = IsianLanjutanRuangan::findOne(['id_isian_lanjutan' => $model->id, 'id_ruangan' => $cat]);
                        if ($exist == false) {
                            $create_category = new IsianLanjutanRuangan();
                            $create_category->id_isian_lanjutan = $model->id;
                            $create_category->id_ruangan = $cat;
                            if ($create_category->validate() == false) {
                                $transaction->rollBack();
                                toastError("Gagal menyimpan data kategori");
                                goto end;
                            }
                            $create_category->save();
                        }
                    }
                    $transaction->commit();
                    $this->messageCreateSuccess();
                    return $this->redirect(['home/bahan-material/keranjang']);
                endif;
                $this->messageValidationFailed();
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }
        end:
        return $this->render('create', $model->render());
    }

    /**
     * Creates a new IsianLanjutan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $this->view->title = 'Homei - Form Rencana Pembangunan';
        $model = $this->findModel($id);
        if ($model->status != 0) {
            return $this->redirect(['view', 'id' => $model->kode_unik]);
        }
        $model->scenario = $model::SCENARIO_CREATE;

        $model->list_ruangan = $oldCategoryIDs = $list_ruangan = $model->getListRuangan();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load($_POST)) :
                $model->budget = str_replace(",", "", $model->budget);
                $model->budget = str_replace("Rp ", "", $model->budget);
                $model->id_user = \Yii::$app->user->identity->id;
                $model->status = $model::STATUS_USER_ISI;
                if ($model->validate()) :
                    $model->save();
                    // list_ruangan
                    $list_ruangan = Yii::$app->request->post('IsianLanjutan')['list_ruangan'];
                    $deletedCategoryIDs = array_diff($oldCategoryIDs, $list_ruangan);
                    foreach ($list_ruangan as $cat) {
                        $exist = IsianLanjutanRuangan::findOne(['id_isian_lanjutan' => $model->id, 'id_ruangan' => $cat]);
                        if ($exist == false) {
                            $create_category = new IsianLanjutanRuangan();
                            $create_category->id_isian_lanjutan = $model->id;
                            $create_category->id_ruangan = $cat;
                            if ($create_category->validate() == false) {
                                $transaction->rollBack();
                                toastError("Gagal menyimpan data kategori");
                                goto end;
                            }
                            $create_category->save();
                        }
                    }
                    $transaction->commit();
                    $this->messageCreateSuccess();
                    return $this->redirect(['view', 'id' => $model->kode_unik]);
                endif;
                $this->messageValidationFailed();
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->render('create', $model->render());
    }

    public function actionTanggalRencanaPembangunan($id)
    {
        $this->view->title = 'Homei - Tanggal Rencana Pembangunan';
        $model = $this->findModel($id);

        if ($model->status_pembayaran != 2) {
            toastError("DP Belum Dibayar atau Diverifikasi");
            return $this->redirect(['home/form-rencana-pembangunan/view', 'id' => $model->kode_unik]);
        }
        $model->scenario = $model::SCENARIO_BANGUN;

        if ($model->status != $model::STATUS_SETUJU_TOR && $model->status != $model::STATUS_TOLAK && $model->status != $model::STATUS_REVISI_RENCANA_PEMBANGUNAN) {
            toastError("TOR Belum Disetujui atau Rencana Pembangunan Ditolak");
            return $this->redirect(['home/form-rencana-pembangunan/view', 'id' => $model->kode_unik]);
        }
        try {
            if ($model->load($_POST)) :

                if (strtotime($model->rencana_pembangunan) < time()) {
                    toastError(Yii::t("cruds", "Tanggal Pembangunan Harus lebih besar dari tanggal sekarang."));
                    return $this->redirect(['home/form-rencana-pembangunan/view', 'id' => $model->kode_unik]);
                }

                if ($model->validate()) :
                    $model->status = $model::STATUS_RENCANA_PEMBANGUNAN;
                    $model->alasan_tolak = null;

                    \app\components\Notif::log(
                        null,
                        \Yii::$app->user->identity->name . " telah mengisi tanggal rencana pembangunan",
                        "Hallo Admin, " . \Yii::$app->user->identity->name . " telah mengisi tanggal rencana pembangunan. Silahkan cek data isian lanjutan",
                        [
                            "controller" => "isian-lanjutan/view",
                            "android_route" => null,
                            "params" => [
                                "id" => $model->id
                            ]
                        ]
                    );

                    $model->save();
                    $this->messageUpdateSuccess();
                    return $this->redirect(['home/form-rencana-pembangunan/view', 'id' => $model->kode_unik]);
                endif;
                $this->messageValidationFailed();
            endif;
            goto end;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->render('rencana-bangun', $model->render());
    }

    // public function actionTolakTor($id)
    // {
    //     $model = $this->findModel($id);
    //     $model->scenario = $model::SCENARIO_TOLAK;
    //     // dd($_POST);
    //     if ($model->status <= $model::STATUS_DEAL_RENCANA_PEMBANGUNAN) {
    //         return $this->redirect(['view', 'id' => $model->kode_unik]);
    //     }
    //     if ($_POST) :
    //         if ($model->validate()) :
    //             $model->alasan_tolak = $_POST['alasan'];
    //             //0 = default
    //             //1 = terima
    //             //2 = tolak
    //             $model->approval_dokumen_tor = 2;
    //             $model->status = $model::STATUS_TOR_BUTUH_REVISI;
    //             // dd($model);
    //             $model->save();
    //             $this->messageUpdateSuccess();
    //             return $this->redirect(['view', 'id' => $model->kode_unik]);
    //         endif;
    //         $this->messageValidationFailed();
    //     endif;
    // }

    public function actionSetujuTor($id)
    {
        $model = $this->findModel($id);
        $model->scenario = $model::SCENARIO_APPROVE_TOR;

        if ($model->id_user != Constant::getUser()->id) {
            toastError(Yii::t("cruds", "Anda tidak diperbolehkan mengakses menu ini"));
            return $this->redirect(['view', 'id' => $model->kode_unik]);
        }

        $model->status = $model::STATUS_SETUJU_TOR;
        if ($model->save()) {

            \app\components\Notif::log(
                null,
                \Yii::$app->user->identity->name . " Telah menyetujui dokumen TOR",
                "Hallo Admin, " . \Yii::$app->user->identity->name . " Telah menyetujui dokumen TOR. Silahkan cek data isian lanjutan",
                [
                    "controller" => "isian-lanjutan/view",
                    "android_route" => null,
                    "params" => [
                        "id" => $model->id
                    ]
                ]
            );

            $this->messageCreateSuccess();
            return $this->redirect(['view', 'id' => $model->kode_unik]);
        } else {
            toastError("Telah terjadi kesalahan");
            return $this->redirect(['view', 'id' => $model->kode_unik]);
        }
    }

    public function actionUploadPembayaranDp($id)
    {
        date_default_timezone_set("Asia/Jakarta");
        $this->view->title = 'Homei - Pembayaran DP';
        $model = IsianLanjutan::find()->where(['kode_unik' => $id])->andWhere(['id_user' => \Yii::$app->user->identity->id])->one();
        $pembayarans = MasterPembayaran::find()->where(['status' => 1])->all();
        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        if ($model->status_pembayaran == 1 || $model->status_pembayaran == 2) {
            \Yii::$app->getSession()->setFlash(
                'error',
                'DP Telah Dibayar'
            );
            return $this->redirect(['view', 'id' => $model->kode_unik]);
        }
        if ($model->dp_pembayaran == null) {
            \Yii::$app->getSession()->setFlash(
                'error',
                'DP Belum Ditentukan oleh Admin'
            );
            return $this->redirect(['view', 'id' => $model->kode_unik]);
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
                $response = $this->uploadFile($instance, "rencana-pembangunan/pembayaran_dp/{$model->kode_unik}");
                if ($response->success == false) {
                    toastError("Dokumen gagal diunggah");
                    goto end;
                }
                $model->bukti_pembayaran = $response->filename;
                $model->tanggal_pembayaran = date('Y-m-d H:i:s');
                // $oldtotal = $model->total_pembayaran;
                // $model->total_pembayaran = $oldtotal + $model->nilai_dp;
                $model->status_pembayaran = 1;
                $model->alasan_tolak = null;

                if ($model->validate()) :
                    \app\components\Notif::log(
                        null,
                        \Yii::$app->user->identity->name . " telah mengunggah bukti pembayaran DP",
                        "Hallo Admin, " . \Yii::$app->user->identity->name . " telah mengunggah bukti pembayaran DP. Silahkan cek data isian lanjutan",
                        [
                            "controller" => "isian-lanjutan/view",
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
        return $this->render(
            'pembayaran/bayar-dp',
            // $model->render()
            [
                'model' => $model,
                'pembayarans' => $pembayarans
            ]
        );
    }

    /**
     * Finds the IsianLanjutan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IsianLanjutan the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IsianLanjutan::find()->where(['kode_unik' => $id])->andWhere(['id_user' => \Yii::$app->user->identity->id])->one()) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
