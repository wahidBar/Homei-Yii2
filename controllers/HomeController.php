<?php

namespace app\controllers;

use app\components\Constant;
use app\components\HttpHelper;
use app\components\UploadFile;
use app\controllers\home\BaseController;
use app\models\BalasanKonsultasi;
use app\models\base\GoogleAuth;
use app\models\ContohProduk;
use app\models\DataDiri;
use app\models\DealPelanggan;
use app\models\DetailContohProduk;
use app\models\FormKonsultasi;
use app\models\Galeri;
use app\models\Home;
use app\models\IsianLanjutan;
use app\models\Konsultasi;
use app\models\KonsultasiChat;
use app\models\LoginGoogle;
use app\models\MasterKonsepDesain;
use app\models\Model;
use app\models\Notification;
use app\models\Partner;
use app\models\Penawaran;
use app\models\Popup;
use app\models\Portofolio;
use app\models\PortofolioGambar;
use app\models\Proyek;
use app\models\search\IsianLanjutanSearch;
use app\models\SiteSetting;
use app\models\Slides;
use app\models\SupplierOrder;
use app\models\TabHome;
use app\models\TentangHomei;
use app\models\TentangHomeiDetail;
use app\models\Testimonials;
use app\models\User;
use dmstr\bootstrap\Tabs;
use Symfony\Component\DomCrawler\Form;
use Yii;
use yii\data\Pagination;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\web\UploadedFile;


class HomeController extends BaseController
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
                        'actions' => ['login', 'error', 'index', 'google', 'portofolio', 'detail-portofolio', 'kebijakan-privasi', 'syarat-ketentuan', 'pages', 'register-tukang'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'profile', 'konsultasi', 'daftar-penawaran-project', 'pilih-penawaran', 'detail-penawaran-project', 'formulir-data-diri', 'konsep-design', 'formulir-konsultasi',  'akhiri-konsultasi', 'notifikasi', 'jumlah-notifikasi', 'daftar-notifikasi'], // add all actions to take guest to login page
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],

            ],

        ];
    }

    public function actionRegisterTukang()
    {
        $config = \app\models\Config::find()->where(['name' => 'form-registrasi-user'])->one();
        if ($config == null) throw new HttpException(404);
        if (intval($config->value) == 0) throw new HttpException(404);
        $this->view->title = "Pendaftaran Tukang";

        try {
            $model = new \app\models\RegisterTukangForm();
            $model->type = 'web';
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate() == false) {
                    Yii::$app->session->setFlash('error', 'Terjadi kesalahan pada pengisian formulir');
                    goto end;
                }

                if ($model->register()) {
                    Yii::$app->session->setFlash('success', 'Registrasi berhasil, akun berhasil dibuat');
                    return $this->redirect(['/home/register-tukang']);
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal melakukan registrasi');
                    goto end;
                }
            }
        } catch (\Throwable $th) {
            Yii::$app->session->setFlash('error', $th->getMessage());
        }

        end:
        return $this->render('register-tukang', [
            'model' => $model,
        ]);
    }

    public function actionPages($id)
    {
        $page = \app\models\Page::find()->where(['slug' => $id])->one();
        if ($page == null) {
            throw new HttpException(404, 'The requested page does not exist.');
        }

        $page->incrementViewCount();
        $this->view->title = $page->title;
        $setting = SiteSetting::find()->one();
        return $this->render('pages', [
            'setting' => $setting,
            'page' => $page,
        ]);
    }

    public function actionKebijakanPrivasi()
    {
        $this->view->title = 'Homei - Kebijakan Privasi';
        $setting = SiteSetting::find()->one();
        return $this->render('privacy-policy', [
            'setting' => $setting,
        ]);
    }

    public function actionSyaratKetentuan()
    {
        $this->view->title = 'Homei - Syarat & Ketentuan';
        $setting = SiteSetting::find()->one();
        return $this->render('syarat-ketentuan', [
            'setting' => $setting,
        ]);
    }


    public function actionIndex()
    {
        /**
         * Langsung Lempar Ke Dashboard Admin
         */
        $user = Constant::getUser();
        if ($user && $user->role_id != Constant::ROLES["user"] && is_int(stripos(Yii::$app->request->url, "/web/home")) == false) return $this->redirect(['/site/index']);

        $this->view->title = 'Homei';
        $galleries = Galeri::find()->limit(10)->all();
        $hp_galleries = Galeri::find()->limit(4)->all();
        $cproduks = ContohProduk::find()->all();
        // $dproduks = DetailContohProduk::find()->where(['id_contoh_produk' => $cproduk->id])->all();
        $thomei = TentangHomei::find()->one();
        $dhomeis = TentangHomeiDetail::find()->where(['id_tentang_homei' => $thomei->id])->all();
        $slides = Slides::find()->all();
        $testimonials = Testimonials::find()->all();
        $partners = Partner::find()->all();

        if (Yii::$app->session->has('popup_time')) {
            $popup_time = Yii::$app->session->get('popup_time');
            $now = time();
            if ($popup_time > $now) {
                $popup = Popup::find()->one();
                Yii::$app->session->set('popup_time', $now + (3600 * 5));
            } else {
                $popup = null;
            }
        } else {
            $popup = Popup::find()->one();
        }

        return $this->render('index', [
            'galleries' => $galleries,
            'slides' => $slides,
            'hp_galleries' => $hp_galleries,
            'cproduks' => $cproduks,
            // 'dproduks' => $dproduks,
            'thomei' => $thomei,
            'dhomeis' => $dhomeis,
            'testimonials' => $testimonials,
            'partners' => $partners,
            'popup' => $popup,
        ]);
    }

    public function actionLogout()
    {
        //log last login column
        $user = Yii::$app->user->identity;
        if ($user == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        $user->last_logout = new Expression("NOW()");
        // $user->save();

        Yii::$app->user->logout();

        return $this->redirect(['site/login']);
    }

    // public function actionProfile()
    // {
    //     $this->view->title = 'Homei - Profile';
    //     $model = User::find()->where(["id" => Yii::$app->user->id])->one();

    //     return $this->render('profile', [
    //         'model' => $model,
    //     ]);
    // }

    public function actionProfile()
    {
        $this->view->title = 'Homei - Profile';
        $model = User::find()->where(["id" => Yii::$app->user->id])->one();
        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        $jumlah_deal = IsianLanjutan::find()->where(['status' => 8])->andWhere(['id_user' => Yii::$app->user->id])->count();
        $jumlah_proyek = Proyek::find()->where(['id_user' => Yii::$app->user->id])->count();
        $jumlah_order_barang = SupplierOrder::find()->where(['user_id' => Yii::$app->user->id])->count();
        $model->scenario = $model::SCENARIO_USEREDIT;

        $oldMd5Password = $model->password;
        $model->password = "";
        $oldPhotoUrl = $model->photo_url;


        if ($model->load($_POST)) {
            //password
            if ($model->password != "") {
                $model->password = \Yii::$app->security->generatePasswordHash($model->password);
            } else {
                $model->password = $oldMd5Password;
            }

            # get the uploaded file instance
            $image = UploadedFile::getInstance($model, 'photo_url');
            if ($image != null) {
                $response = $this->uploadImage($image, "user_image");

                // dd($response);
                if ($response->success == false) {
                    toastError("Gambar gagal diunggah");
                    goto end;
                }
                if ($model->photo_url != null) {
                    unlink(Yii::getAlias("@app/web/uploads/") . $oldPhotoUrl);
                }
                $this->deleteOne($oldPhotoUrl);
                $model->photo_url = $response->filename;
            } else {
                $model->photo_url = $oldPhotoUrl;
            }

            // if ($model->validate()) {
            if ($model->save(false)) {
                toastSuccess("Profile berhasil diubah");
                // }
            } else {
                toastError("Profile gagal diubah");
            }
            return $this->redirect(["profile"]);
        }
        end:
        $model->password = "";
        return $this->render('profile', [
            'model' => $model,
            'jumlah_deal' => $jumlah_deal,
            'jumlah_proyek' => $jumlah_proyek,
            'jumlah_order_barang' => $jumlah_order_barang
        ]);
    }

    public function actionFormulirKonsultasi()
    {
        $this->view->title = 'Homei - Formulir Konsultasi';

        $konsultasi_aktif = Konsultasi::find()->andWhere(['id_user' => Constant::getUser()->id, 'is_active' => 1])->one();
        //get date from datetime
        $date = date('Y-m-d', strtotime($konsultasi_aktif->created_at));
        //get current date
        $current_date = date('Y-m-d');
        //calculate date
        $difference = strtotime($current_date) - strtotime($date);
        //convert to days
        $days = floor($difference / (60 * 60 * 24));
        // echo $days; die;

        if ($konsultasi_aktif) {
            if ($days > 1) {
                $konsultasi_aktif->scenario = Konsultasi::SCENARIO_NONAKTIFKAN_CHAT;
                $konsultasi_aktif->is_active = 0;
                $konsultasi_aktif->save();
                return $this->redirect(['/home/formulir-konsultasi']);
            }
            return $this->redirect(['/home/konsultasi', 'ticket' => $konsultasi_aktif->ticket]);
        }

        $model = new Konsultasi();
        $model->scenario = $model::SCENARIO_CREATE;

        $konsultan = User::find()->where(['role_id' => 4])->andWhere(['is_active' => 1])->one();
        $model->id_user = \Yii::$app->user->identity->id;
        $model->id_konsultan = $konsultan->id;
        $ticket = "Ticket-" . Yii::$app->security->generateRandomString(12);
        $model->ticket = $ticket;

        $isian = new IsianLanjutan();
        $isian->scenario = $isian::SCENARIO_INITIAL_CREATE;
        $isian->id_user = \Yii::$app->user->identity->id;
        $isian->nama_awal = \Yii::$app->user->identity->name;
        $isian->kode_unik = Yii::$app->security->generateRandomString(30);

        try {
            if ($model->validate() && $isian->validate()) :
                $isian->save();

                $model->id_isian_lanjutan = $isian->id;
                $model->kode_isian_lanjutan = $isian->kode_unik;

                $model->save();
                \app\components\Notif::log(
                    $model->id_konsultan,
                    "{$isian->nama_awal} ingin Melakukan Konsultasi",
                    "Hallo Admin, {$isian->nama_awal} ingin Melakukan Konsultasi.",
                    [
                        "controller" => "konsultasi",
                        "android_route" => "app-konsultasi",
                        "params" => [
                            "ticket" => $model->ticket
                        ]
                    ]
                );

                // notif user jika belum ada konsultan nya
                if ($model->id_konsultan == null) {
                    toastSuccess("Mohon tunggu sebentar ya, Sampai ada konsultan yang aktif");
                }
                return $this->redirect(['/home/konsultasi', 'ticket' => $model->ticket]);
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->render('form-konsultasi', $model->render());
    }


    public function actionKonsultasi($ticket)
    {
        $this->view->title = 'Homei - Konsultasi';
        $galleries = Galeri::find()->limit(10)->all();
        $hp_galleries = Galeri::find()->limit(4)->all();


        $user = Constant::getUser();
        $params['ticket'] = $ticket;
        $params['id_user'] = $user->id;

        $model = Konsultasi::findOne($params);
        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        } else if ($model->is_active == 0 && $model->id_konsultan == null) {
            throw new HttpException(404, "Konsultasi telah dinonaktifkan oleh sistem, Silahkan registrasi kembali dengan mengunjungi halaman konsultasi");
        }

        $list_chat = $model->getKonsultasiChats()->all();
        $konsultan = $model->konsultan;
        $user = $model->user;

        return $this->render('konsultasi', compact('model', 'konsultan', 'user', 'galleries', 'list_chat', 'hp_galleries'));
    }

    public function actionAkhiriKonsultasi($ticket)
    {
        // $end = $_GET['end'];
        // var_dump($end);die;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $this->layout = false;
        $user = Yii::$app->user->identity;

        if ($user->role_id === Constant::ROLE_KONSULTAN) {
            $chat_active = Konsultasi::find()->where(['ticket' => $ticket, 'id_konsultan' => $user->id, 'is_active' => 1])->one();
        } else {
            $chat_active = Konsultasi::find()->where(['ticket' => $ticket, 'id_user' => $user->id, 'is_active' => 1])->one();
        }

        // var_dump($chat_active->is_active);die;

        if ($chat_active->is_active == 1) {
            $chat_active->is_active = 0;
            if ($chat_active->validate()) {
                $chat_active->save();
                \app\components\Notif::log(
                    $chat_active->id_konsultan,
                    "{$user->name} telah mengakhiri konsultasi",
                    "Hallo Admin, {$user->name} telah mengakhiri konsultasi.",
                    [
                        "controller" => "konsultasi",
                        "android_route" => "app-konsultasi",
                        "params" => [
                            "ticket" => $chat_active->ticket
                        ]
                    ]
                );
                \app\components\Notif::log(
                    null,
                    "{$user->name} telah mengakhiri konsultasi",
                    "Hallo Admin, {$user->name} telah mengakhiri konsultasi.",
                    [
                        "controller" => "isian-lanjutan/view",
                        "android_route" => null,
                        "params" => [
                            "id" => $chat_active->id_isian_lanjutan
                        ]
                    ]
                );
                if ($chat_active->isianLanjutan->status == 0) {
                    toastSuccess("Konsultasi Berhasil Diakhiri");
                    return $this->redirect(['home/konsultasi/' . $chat_active->ticket]);
                } else {
                    toastSuccess("Data berhasil disimpan");
                    return $this->redirect(['home/form-rencana-pembangunan/view?id=' . $chat_active->kode_isian_lanjutan]);
                }
            }
            // return ["message" => Constant::flattenError($chat_active->getErrors()), "success" => false];
            toastError("Telah terjadi kesalahan");
            return $this->redirect(['formulir-konsultasi']);
        } else {
            toastSuccess("Memulai Konsultasi Baru");
            return $this->redirect(['formulir-konsultasi']);
        }
        // return ["message" => Constant::flattenError($chat_active->getErrors()), "success" => false];
        // toastError("Telah terjadi kesalahan");
    }

    public function actionPortofolio()
    {
        $this->view->title = 'Homei - Portofolio';
        $models = Portofolio::find()->all();
        return $this->render('portofolio-project', [
            'models' => $models
        ]);
    }

    public function actionDetailPortofolio($id)
    {
        $this->view->title = 'Homei - Detail Portofolio';
        $model = Portofolio::find()->where(['kode_unik' => $id])->one();
        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        return $this->render('detail-portofolio', [
            'model' => $model
        ]);
    }


    public function actionKonsepDesign()
    {
        $this->view->title = 'Homei - Konsep Design';
        $designs = MasterKonsepDesain::find()->where(['flag' => 1])->all();
        $galleries = Galeri::find()->limit(10)->all();
        $hp_galleries = Galeri::find()->limit(4)->all();
        return $this->render('konsep-design', [
            'designs' => $designs,
            'galleries' => $galleries,
            'hp_galleries' => $hp_galleries
        ]);
    }

    public function actionPilihPenawaran($id)
    {
        $penawaran = Penawaran::find()->where(['kode_unik' => $id])->one();
        $isian = IsianLanjutan::find()->where(['kode_unik' => $penawaran->kode_isian_lanjutan])->one();

        if ($penawaran == null || $isian == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        $isian->scenario = $isian::SCENARIO_DEAL;
        if ($isian->validate()) :
            $isian->id_penawaran = $penawaran->id;
            $isian->status = $isian::STATUS_DEAL_USER;

            \app\components\Notif::log(
                null,
                \Yii::$app->user->identity->name . " telah memilih penawaran",
                "Hallo Admin, " . \Yii::$app->user->identity->name . " telah memilih penawaran. Silahkan cek data isian lanjutan",
                [
                    "controller" => "isian-lanjutan/view",
                    "android_route" => null,
                    "params" => [
                        "id" => $isian->id
                    ]
                ]
            );


            $isian->save();
            toastSuccess("Data berhasil disimpan");
            return $this->redirect(['/home/form-rencana-pembangunan/tanggal-rencana-pembangunan', 'id' => $isian->kode_unik]);
        else :
            toastError("Data tidak berhasil disimpan");
            // dd('error');
            // return $this->redirect(['home/daftar-penawaran-project', 'id' => $penawaran->id]);
            return $this->redirect(["home/detail-penawaran-project", "id" => $penawaran->kode_unik]);
        endif;
    }

    public function actionDaftarPenawaranProject($id)
    {
        $this->view->title = 'Homei - Daftar Penawaran Project';

        $query = new Query;
        $query->select([
            't_penawaran.kode_unik as penawaran_id', 't_penawaran.kode_isian_lanjutan',
            't_penawaran.estimasi_waktu', 't_penawaran.tgl_transaksi',
            't_penawaran.harga_penawaran', 't_penawaran.total_harga_penawaran',
            't_isian_lanjutan.kode_unik',
            't_isian_lanjutan.status', 't_isian_lanjutan.id_user'
        ])
            ->from('t_penawaran')
            ->join(
                'LEFT JOIN',
                't_isian_lanjutan',
                't_isian_lanjutan.kode_unik =t_penawaran.kode_isian_lanjutan'
            )
            ->where(['id_user' => \Yii::$app->user->identity->id])
            ->andWhere(['kode_isian_lanjutan' => $id])
            ->andWhere(['<', 'status', 5]);
        $command = $query->createCommand();
        $count = $command->execute();
        $models = $command->queryAll();

        // var_dump($models);die;
        if ($models != null) {
            return $this->render('daftar-penawaran-project', [
                'models' => $models,
                'count' => $count
            ]);
        } else {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        return $this->redirect(["home/index"]);
    }

    public function actionDetailPenawaranProject($id)
    {
        $this->view->title = 'Homei - Detail Penawaran Project';

        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember();
        Tabs::rememberActiveState();
        // $model = IsianLanjutan::find()->where(['id'=>$id])->one();

        $model = Penawaran::find()->where(['kode_unik' => $id])->one();

        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }

        if ($model != null) {
            return $this->render('detail-penawaran-project', [
                'model' => $model,
            ]);
        }
        return $this->redirect(["home/index"]);
    }

    // public function actionDealProject2($id)
    // {
    //     $this->view->title = 'Homei - Deal Project';
    //     $model = new DealPelanggan();
    //     $model->scenario = $model::SCENARIO_CREATE;

    //     $query = new Query();
    //     $query->select(
    //         [
    //             't_penawaran.id as id_penawaran',
    //             't_isian_lanjutan.id AS id_isian',
    //         ]
    //     )
    //         ->from('t_isian_lanjutan')
    //         ->join(
    //             'INNER JOIN',
    //             't_penawaran',
    //             't_isian_lanjutan.id = t_penawaran.id_isian_lanjutan'
    //         )
    //         ->where('t_penawaran.id = ' . $id . '');

    //     $command = $query->createCommand();
    //     $data = $command->queryOne();

    //     $model->id_penawaran = $data['id_penawaran'];
    //     $model->id_isian_lanjutan = $data['id_isian'];
    // SELECT t_isian_lanjutan.id as 'id_isian', t_penawaran.id as 'id_penawaran'
    // FROM t_isian_lanjutan INNER JOIN t_penawaran
    // ON t_isian_lanjutan.id = t_penawaran.id_isian_lanjutan
    // where t_penawaran.id = 3

    //     try {

    //         if ($model->load($_POST)) :

    //             if ($model->validate()) :
    //                 $model->created_by = \Yii::$app->user->identity->id;
    //                 $model->save();
    //                 toastSuccess("Data berhasil disimpan");
    //                 return $this->redirect(['detail-form-project', 'id' => $model->id]);
    //             endif;
    //             toastError("Telah terjadi kesalahan");
    //         elseif (!\Yii::$app->request->isPost) :
    //             $model->load($_GET);
    //         endif;
    //     } catch (\Exception $e) {
    //         $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
    //         $model->addError('_exception', $msg);
    //     }

    //     end:
    //     return $this->render('deal-project', $model->render());
    // }

    public function actionGoogle()
    {
        //lokal
        // $GCLIENT_ID = "451233796463-ng74m66j8fgc0v2cp5fdbcfg6s2i9v3f.apps.googleusercontent.com";
        // $GCLIENT_SECRET = "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";

        //server
        $GCLIENT_ID = "192180383913-l6cvmkl7m5oq2bfo61to3iv6vnn8vukn.apps.googleusercontent.com";
        $GCLIENT_SECRET = "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";

        $response = HttpHelper::postApi("https://www.googleapis.com/oauth2/v4/token", [
            "code" => $_GET['code'],
            "client_id" => $GCLIENT_ID,
            "client_secret" => $GCLIENT_SECRET,
            // "redirect_uri" => 'localhost/homei/web/home/google',
            "redirect_uri" => 'http://homei.co.id/web/home/google',
            "grant_type" => 'authorization_code'
        ]);

        if (isset($response->error)) {
            return $this->redirect(['/site/login']);
            die;
        }

        // $response = (object) ["access_token" => "ya29.a0ARrdaM9ld_7bYHHznJKiZOYjkhALDGcvT4CFy9I_ZekqsplcLwSxG3nGojqGWR_PccIf4ZnuFy1bjXn_M8IoyCMDoCCHuRbV67E-DyPtAD-YPE8tNk0c-fHnLS4nhU62xiea7RqjbMWVVxsyEdBAg6UMyniz"];

        $info = HttpHelper::getApi("https://www.googleapis.com/oauth2/v1/userinfo", [
            "access_token" => $response->access_token,
        ], [
            'Authorization' => "Bearer " . $response->access_token,
        ]);


        if (isset($response->error)) {
            echo "Terjadi kesalahan ketika mengambil data";
            die;
        }

        $check = GoogleAuth::find()->where([
            "gid" => $info->id,
        ])
            ->andWhere(['role_id' => 3])
            ->one();

        if ($check->gid != null) {
            $gid = $check->gid;
            $getUser = User::find()->where(['gid' => $gid, 'status' => 1])->one();

            Yii::$app->user->login($getUser);
            return $this->redirect(["home/index"]);
        } else {

            $g_user = new GoogleAuth();
            $g_user->scenario = $g_user::SCENARIO_CREATE;
            try {
                $g_user->gid = $info->id;
                $g_user->name = $info->name;
                $g_user->photo_url = $info->picture;

                file_get_contents($g_user->photo_url);

                $g_user->email = $info->email;
                $g_user->role_id = 3;
                $g_user->is_active = 1;

                $g_user->save(false);

                $gid = $g_user->gid;
                $getUser = User::find()->where(['gid' => $gid])->one();

                Yii::$app->user->login($getUser);
                return $this->redirect(["home/index"]);
            } catch (\Exception $e) {
                $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
                $g_user->addError('_exception', $msg);
            }
        }

        echo "Terjadi kesalahan";
        die;
    }

    public function actionDaftarNotifikasi()
    {
        $this->view->title = 'Homei - Daftar Notifikasi';
        // $models = Notification::find()
        //     ->where(['user_id' => \Yii::$app->user->identity->id])
        //     ->orderBy(['id' => SORT_DESC])->all();
        // $pagination = Model::pagination($models);

        $query = Notification::find()
            ->where(['user_id' => \Yii::$app->user->identity->id])
            ->orderBy(['id' => SORT_DESC]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $models = $query->offset($pages->offset)->limit($pages->limit)->all();

        $jumlah_order_barang = SupplierOrder::find()->where(['user_id' => Yii::$app->user->id])->count();
        $jumlah_proyek = Proyek::find()->where(['id_user' => Yii::$app->user->id])->count();

        return $this->render('daftar-notifikasi', [
            'models' => $models,
            'pages' => $pages,
            'jumlah_order' => $jumlah_order_barang,
            'jumlah_proyek' => $jumlah_proyek,
        ]);
    }

    // public function actionCekKonsultan($ticket)
    // {
    //     \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    //     $konsultasi = Konsultasi::find()->where(['ticket' => $ticket])->one();
    //     $konsultasi->scenario = $konsultasi::SCENARIO_UPDATE_KONSULTAN;

    //     if ($konsultasi->id_konsultan == null) :
    //         $data = User::find()->select(['id','name','email','photo_url','is_active'])->where(['role_id' => 4])->andWhere(['is_active' => 1])->all();

    //         if ($data != null):
    //             $konsultasi->id_konsultan = $data->id;
    //             $konsultasi->save();
    //         endif;
    //     else :
    //         $data = User::find()->select(['id','name','email','photo_url','is_active'])->where(['role_id' => 4])->andWhere(['id' => $konsultasi->id_konsultan])->all();
    //     endif;

    //     // dd($data);

    //     if ($data != null) {
    //         $result['status'] = true;
    //         $result['message'] = "success";
    //         $result['data'] = $data;
    //     } else {
    //         $result['status'] = "false";
    //         $result['message'] = "false";
    //         $result['data'] = null;
    //         $result['code'] = 404;
    //     }
    //     return $result;
    // }
}
