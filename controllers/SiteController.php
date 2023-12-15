<?php

namespace app\controllers;

//use app\components\NodeLogger;

use app\components\annex\Tabs;
use app\components\Constant;
use app\models\Action;
use app\models\ContactForm;
use app\models\DealPelanggan;
use app\models\IsianLanjutan;
use app\models\Konsultasi;
use app\models\LoginForm;
use app\models\MasterTemplateOrder;
use app\models\Notification;
use app\models\PekerjaanSameday;
use app\models\Proyek;
use app\models\RegisterForm;
use app\models\search\IsianLanjutanSearch;
use app\models\search\ProyekSearch;
use app\models\SiteSetting;
use app\models\Supplier;
use app\models\User;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    use \app\components\UploadFile;

    public function behaviors()
    {
        //NodeLogger::sendLog(Action::getAccess($this->id));
        //apply role_action table for privilege (doesn't apply to super admin)
        return Action::getAccess($this->id);
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'layout' => 'main-error',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => 'testme',
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->title = 'Home i';
        $user = Constant::getUser();
        if ($user->role_id == 1) {
            $isian = IsianLanjutan::find()->count();
            $users = User::find()
                ->where(['role_id' => 3])->orderBy(['last_login' => SORT_DESC])->limit(6)->all();
            $jumlah_user = User::find()
                ->where(['role_id' => 3])->count();
            $jumlah_proyek = Proyek::find()->count();
            $jumlah_deal = IsianLanjutan::find()
                ->where(['status' => 8])->count();

            $searchModelIsian  = new IsianLanjutanSearch();
            $dataProviderIsian = $searchModelIsian->search($_GET);
            $dataProviderIsian->pagination->pageSize = 5;

            $searchModelProyek  = new ProyekSearch;
            $dataProviderProyek = $searchModelProyek->search($_GET);
            $dataProviderProyek->pagination->pageSize = 5;

            Tabs::clearLocalStorage();

            Url::remember();
            \Yii::$app->session['__crudReturnUrl'] = null;
            return $this->render('index', [
                'isian' => $isian,
                'dataProviderIsian' => $dataProviderIsian,
                'searchModelIsian' => $searchModelIsian,
                'dataProviderProyek' => $dataProviderProyek,
                'searchModelProyek' => $searchModelProyek,
                'users' => $users,
                'jumlah_user' => $jumlah_user,
                'jumlah_proyek' => $jumlah_proyek,
                'jumlah_deal' => $jumlah_deal,
            ]);
        } else if ($user->role_id == Constant::ROLE_TUKANG_SAMEDAY) {
            $jumlah_pekerjaan_belum_selesai = PekerjaanSameday::find()
                ->where([
                    "and",
                    ["!=", 'status', PekerjaanSameday::STATUS_SELESAI],
                    [
                        'id_tukang' => $user->id,
                    ]
                ])->count();
            $jumlah_pekerjaan_selesai = PekerjaanSameday::find()
                ->where([
                    'status' => PekerjaanSameday::STATUS_SELESAI,
                    'id_tukang' => $user->id,
                ])->count();
            $jumlah_pekerjaan_bulan_ini = PekerjaanSameday::find()
                ->where([
                    'id_tukang' => $user->id,
                ])
                // filter only last month
                ->andWhere(['>=', 'created_at', date('Y-m-d', strtotime('-1 month'))])
                ->count();
            return $this->render('index_tukang_sameday', compact(
                'jumlah_pekerjaan_belum_selesai',
                'jumlah_pekerjaan_selesai',
                'jumlah_pekerjaan_bulan_ini'
            ));
        } else if ($jumlah = $user->getProyekAnggota()->count()) {
            return $this->render('index_proyek_anggota', compact('jumlah'));
        } else if ($user->role_id == Constant::ROLES['user']) {
            return $this->render('index_user');
        } else {
            return $this->render('index_blank');
        }
    }

    public function actionProfile()
    {
        $this->view->title = 'Home i - Profile';
        $model = User::find()
            ->where(["id" => Yii::$app->user->id])->one();
        $oldMd5Password = $model->password;
        $oldPhotoUrl = $model->photo_url;

        $model->password = "";

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
                if ($response->success == false) {
                    toastError("Gambar gagal diunggah");
                    goto end;
                }
                $model->photo_url = $response->filename;
                $this->deleteOne($oldPhotoUrl);
            } else {
                $model->photo_url = $oldPhotoUrl;
            }

            if ($model->save(false)) {
                Yii::$app->session->addFlash("success", "Profile successfully updated.");
            } else {
                Yii::$app->session->addFlash("danger", "Profile cannot updated.");
            }
            return $this->redirect(["profile"]);
        }
        end:
        return $this->render('profile', [
            'model' => $model,
        ]);
    }

    public function actionRegister()
    {
        $this->view->title = 'Home i - Pendaftaran';
        $this->layout = "main-login";

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $pengaturan = SiteSetting::find()->one();
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->agreeTerm != 1) {
                toastError("Anda harus menyetujui syarat dan ketentuan");
                return $this->redirect(["site/register"]);
            }
            $model->no_hp = Constant::purifyPhone($model->no_hp);
            $cekemail = User::find()
                ->where(['email' => $model->email])->one();
            if ($cekemail != null) {
                toastError("Email Telah Digunakan");
                return $this->redirect(["site/register"]);
            }

            $subjek = "Pendaftaran HOMEi";
            $text = "Pendaftaran HOMEi berhasil. " . $pengaturan->tagline2;

            if ($model->register()) {
                Yii::$app->mailer->compose()
                    ->setTo($model->email)
                    ->setFrom(['no-reply@homei.co.id' => 'HOMEi'])
                    ->setSubject($subjek)
                    ->setHtmlBody($text)
                    ->send();

                Yii::$app->session->setFlash("success", "Pendaftaran Berhasil, Silahkan Login");
                return $this->redirect(["site/login"]);
            } else {
                // dd($model->getErrors());
                toastError("Register gagal : " . Constant::flattenError($model->getErrors()));
                return $this->redirect(["site/register"]);
            }
        }
        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionLogin()
    {
        $this->view->title = 'Home i - Login';
        $this->layout = "main-login";
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect(['home/index']);
        }

        $model = new LoginForm();
        // $modelRegister = new RegisterForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            //log last login column
            $user_id = Yii::$app->user->identity->id;
            $user = User::find()
                ->where(['id' => $user_id])->one();
            $user->scenario = $user::SCENARIO_LOGIN;
            $user->is_active = 1;
            $user->last_login = new Expression("NOW()");

            $user->save();

            $role = \Yii::$app->user->identity->role_id;
            if ($role == 3) {
                return $this->redirect(["home/index/"]);
            } else {
                return $this->redirect(["site/index"]);
            }
        }
        // if ($modelRegister->load(Yii::$app->request->post()) && $modelRegister->register()) {
        //     Yii::$app->session->setFlash("success", "Register success, please login");
        //     return $this->redirect(["site/login"]);
        // }
        return $this->render('login', [
            'model' => $model,
            // 'modelRegister' => $modelRegister,
        ]);
    }

    public function actionLogout()
    {
        try {
            //log last login column
            $user = User::find()
                ->where(['id' => Yii::$app->user->identity->id])->one();
            $user->scenario = $user::SCENARIO_LOGOUT;
            $user->last_logout = new Expression("NOW()");
            $user->is_active = 0;
            $user->save();

            Yii::$app->user->logout();

            return $this->redirect(['site/login']);
        } catch (\Throwable $th) {
            toastError(Yii::t("cruds", "Terjadi kesalahan. Sesi anda telah habis"));
            return $this->redirect(['site/index']);
        }
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }


    public function actionGetKota()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $keys = array_keys($_POST['depdrop_all_params']);
            $selected = $_POST['depdrop_all_params'][$keys[1]];
            if ($parents != null) {
                $cat_id = $parents[0];
                $out = self::getKota($cat_id);
                return ['output' => $out, 'selected' => $selected];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    private static function getKota($provinsi_id)
    {
        $out = [];
        $model = \app\models\WilayahKota::find()
            ->where(['provinsi_id' => $provinsi_id])->all();
        foreach ($model as $row) {
            $out[] = [
                "id" => $row->id,
                "name" => $row->nama,
            ];
        }
        return $out;
    }

    public function actionGetKecamatan()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $keys = array_keys($_POST['depdrop_all_params']);
            $selected = $_POST['depdrop_all_params'][$keys[1]];
            if ($parents != null) {
                $cat_id = $parents[0];
                $out = self::getKecamatan($cat_id);
                return ['output' => $out, 'selected' => $selected];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    private static function getKecamatan($kota_id)
    {
        $out = [];
        $model = \app\models\WilayahKecamatan::find()
            ->where(['kota_id' => $kota_id])->all();
        foreach ($model as $row) {
            $out[] = [
                "id" => $row->id,
                "name" => $row->nama,
            ];
        }
        return $out;
    }

    public function actionGetDesa()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $keys = array_keys($_POST['depdrop_all_params']);
            $selected = $_POST['depdrop_all_params'][$keys[1]];
            if ($parents != null) {
                $cat_id = $parents[0];
                $out = self::getDesa($cat_id);
                return ['output' => $out, 'selected' => $selected];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    private static function getDesa($kecamatan_id)
    {
        $out = [];
        $model = \app\models\WilayahDesa::find()
            ->where(['kecamatan_id' => $kecamatan_id])->all();
        foreach ($model as $row) {
            $out[] = [
                "id" => $row->id,
                "name" => $row->nama,
            ];
        }
        return $out;
    }

    public function actionGetMaterial()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $keys = array_keys($_POST['depdrop_all_params']);
            $selected = $_POST['depdrop_all_params'][$keys[1]];
            if ($parents != null) {
                $cat_id = $parents[0];
                $out = self::getMaterial($cat_id);
                return ['output' => $out, 'selected' => $selected];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    private function getMaterial($id_supplier)
    {
        $out = [];
        $models = \app\models\HargaMaterial::find()
            ->where(['id_supplier' => $id_supplier])->all();
        foreach ($models as $rows) {
            $model = \app\models\MasterMaterial::find()
                ->where(['id' => $rows->id_material])->all();
            foreach ($model as $row) {
                $out[] = [
                    "id" => $row->id,
                    "name" => $row->nama,
                ];
            }
        }
        return $out;
    }

    public function actionGetBarang()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $keys = array_keys($_POST['depdrop_all_params']);
            $selected = $_POST['depdrop_all_params'][$keys[1]];
            if ($parents != null) {
                $cat_id = $parents[0];
                $out = self::getBarang($cat_id);
                return ['output' => $out, 'selected' => $selected];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    private static function getBarang($submaterial_id)
    {
        $out = [];
        $model = \app\models\SupplierBarang::find()
            ->where(['submaterial_id' => $submaterial_id])->all();
        foreach ($model as $row) {
            $out[] = [
                "id" => $row->id,
                "name" => $row->nama_barang,
            ];
        }
        return $out;
    }

    public function actionGetIsian()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $keys = array_keys($_POST['depdrop_all_params']);
            $selected = $_POST['depdrop_all_params'][$keys[1]];
            if ($parents != null) {
                $cat_id = $parents[0];
                $out = self::getIsian($cat_id);
                return ['output' => $out, 'selected' => $selected];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    private function getIsian($id_penawaran)
    {
        $out = [];
        $models = \app\models\Penawaran::find()
            ->where(['id' => $id_penawaran])->one();
        $model = \app\models\IsianLanjutan::find()
            ->where(['id' => $models->id_isian_lanjutan])->all();
        foreach ($model as $row) {
            $out[] = [
                "id" => $row->id,
                "name" => $row->label,
            ];
        }


        return $out;
    }

    public function actionGetSubMaterial()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $keys = array_keys($_POST['depdrop_all_params']);
            $selected = $_POST['depdrop_all_params'][$keys[1]];
            if ($parents != null) {
                $cat_id = $parents[0];
                $out = self::getSubMaterial($cat_id);
                return ['output' => $out, 'selected' => $selected];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    private function getSubMaterial($id_parent)
    {
        $out = [];
        $models = \app\models\SupplierMaterial::find()
            ->where(['id' => $id_parent])->one();
        $model = \app\models\SupplierSubMaterial::find()
            ->where(['material_id' => $models->id])->all();
        foreach ($model as $row) {
            $out[] = [
                "id" => $row->id,
                "name" => $row->nama,
            ];
        }


        return $out;
    }

    public function actionGetTemplatePengiriman()
    {
        $templates = MasterTemplateOrder::find()->all();
        $template = [];
        foreach ($templates as $a) {
            $template[] = $a->nama;
        }
        $template = json_encode($template);
        return $template;
    }

    public function actionGantiPassword($token)
    {
        $this->view->title = 'Home i - Ganti Password';
        $this->layout = "main-login";
        $model = \app\models\User::find()
            ->where(['token' => $token])->one();
        $model->scenario = $model::SCENARIO_PASSWORD;

        if ($model == null) {
            Yii::$app->session->addFlash("error", "Token Tidak Valid");
            return $this->redirect(["site/login"]);
        } else {
            $now = strtotime(date('Y-m-d H:i:s'));
            $validasi = strtotime($model->token_created_at) + (60 * 5);
            if ($now > $validasi) {
                $model->token = null;
                $model->token_created_at = null;
                $model->token_is_used = 0;
                $model->save();

                toastError("Link kadaluwarsa, silahkan masukkan kembali Email Anda.");

                return $this->redirect(["site/lupa-password"]);
            } else {
                if (isset($_POST['Ganti'])) {
                    if ($model->token == $_POST['Ganti']['tokenhid']) {
                        $model->password = \Yii::$app->security->generatePasswordHash($_POST['Ganti']['password']);
                        $model->token = null;
                        $model->save();

                        toastSuccess("Password Telah Diubah, Silahkan Login");
                        return $this->redirect(["site/login"]);
                        $this->refresh();
                    }
                }
                return $this->render('ganti-password', array(
                    'model' => $model,
                ));
            }
        }
    }

    public function actionLupaPassword()
    {
        $this->view->title = 'Home i - Lupa Password';
        $this->layout = "main-login";

        if (isset($_POST['Lupa'])) {
            $getEmail = $_POST['Lupa']['email'];
            if ($getEmail == null || $getEmail == '') {
                toastError("Email Kosong");
                return $this->redirect(["site/lupa-password"]);
            }
            $getModel = \app\models\User::find()
                ->where(['email' => $getEmail])->one();
            if ($getModel == null) {
                toastError("Email Tidak Terdaftar");
                return $this->redirect(["site/lupa-password"]);
            }
            $getModel->scenario = $getModel::SCENARIO_LUPA;

            $getModel->token = null;
            $getModel->token_created_at = null;
            $getModel->token_is_used = 0;
            $getModel->save();

            // $getDate = strtotime($getModel->token_created_at);
            // $dateNow = date('Y-m-d H:i:s');
            // $date = $getDate - strtotime($dateNow);
            // $detik = number_format($date, 0, ",", ".");
            // $selisih = 60 + $detik;
            // $awal  = date_create($getModel->token_created_at);
            //$akhir = date_create(); // waktu sekarang, pukul 06:13
            // $diff  = date_diff($akhir, $awal);
            // $detik = $diff->I;
            // var_dump($detik);
            // die;

            // dd($getModel);

            if ($getModel != null) {
                // if ($detik >= 01 || $getModel->is_used == 0) {
                $getToken = Yii::$app->security->generateRandomString(50);
                $getTime = date("Y-m-d H:i:s");
                $getModel->token = md5($getToken);
                $getModel->token_created_at = $getTime;
                $getModel->token_is_used = 1;
                $subjek = "Reset Password";
                $text = "Klik link berikut untuk mengatur ulang Password. 
                    <b>Harap diperhatikan bahwa link berikut hanya berlaku 5 menit.</b><br>
                    Abaikan jika Anda tidak mengatur ulang password!<br/> 
                    <a href='
                    " . Url::base('https') . "/site/ganti-password?token=" . $getModel->token . "'>Klik Disini</a>
                    <br/> Jika link tidak dapat dibuka salin teks berikut dan tempel di url browser : <br/>
                    " . Url::base('https') . "/site/ganti-password?token=" . $getModel->token;
                if ($getModel->validate()) {
                    // $getModel->token_created_at = null;
                    Yii::$app->mailer->compose()
                        ->setTo($getModel->email)
                        ->setFrom(['no-reply@homei.co.id' => 'Homei'])
                        ->setSubject($subjek)
                        ->setHtmlBody($text)
                        ->send();
                    $getModel->save();

                    toastSuccess("Link untuk mereset Password Anda telah dikirim ke email Anda. Mohon <b>Cek Spam</b> jika tidak ada di kotak masuk!");
                    // mail($getEmail, $subject, $setpesan, $headers);
                    return $this->redirect(["site/lupa-password"]);
                }
                // } else {
                //     \Yii::$app->getSession()->setFlash(
                //         'danger',
                //         'Email Berisi Link Reset Password Telah Dikirimkan. Coba lagi setelah ' . $detik . ' detik.'
                //     );
                //     // mail($getEmail, $subject, $setpesan, $headers);
                //     return $this->redirect(["site/lupa-password"]);
                // }
            } else {
                toastError("Email Tidak Terdaftar");
                return $this->redirect(["site/lupa-password"]);
            }
        }
        return $this->render('lupa-password');
    }

    public function actionCronjobSearchKonsultan($ticket)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $cek_id_teratas = Konsultasi::find()
            ->where(['is not', 'id_konsultan', null], ['ticket' => $ticket])->orderBy('created_at DESC')->one(); // id cari konsultan terakhir yang menangani klien
        $konsultasi = Konsultasi::find()
            ->where(['and', ['is_active' => 1], ['is', 'id_konsultan', null], ['ticket' => $ticket]])->orderBy('created_at ASC')->one(); // cari konsultasi yang belum ditangani

        // dd($cek_id_teratas->id_konsultan);
        if ($konsultasi != null) {
            // jika belum pernah ada konsultan yang menangani konsultasi, cari user aktif saja
            // jika sudah pernah ada , cari user aktif dengan id setelah id konsultan terakhir, jika tidak ada juga maka cari konsultan aktif
            if ($cek_id_teratas == null) {
                $user = User::find()
                    ->where(
                        [
                            'role_id' => Constant::ROLE_KONSULTAN,
                            'is_active' => 1,
                        ]
                    )->one();
            } else {
                $user = User::find()

                    ->where([
                        'and',
                        [
                            'role_id' => Constant::ROLE_KONSULTAN,
                            'is_active' => 1,
                        ],
                        [
                            '>',
                            'id',
                            $konsultasi->id_konsultan
                        ]
                    ])
                    ->orderBy('id ASC')
                    ->one();
                if ($user == null) {
                    $user = User::find()
                        ->where(
                            [
                                'role_id' => Constant::ROLE_KONSULTAN,
                                'is_active' => 1,
                            ]
                        )->one();
                }
            }

            // cek apakah ada konsultan yang sedang aktif
            if ($user != null) {
                $konsultasi->id_konsultan = $user->id;
                $konsultasi->save();
            } else {
                // otomatis matikan konsultasi jika tidak ditanggapi lebih dari 5 mnit
                $waktu = time() - strtotime($konsultasi->created_at);
                $menit = $waktu / 60;
                if ($menit > 5) {
                    $konsultasi->is_active = 0;
                    $konsultasi->save();
                }
            }
        }

        $data = User::find()->select(['id', 'name', 'email', 'photo_url', 'is_active'])
            ->where(['role_id' => 4])->andWhere(['id' => $cek_id_teratas->id_konsultan])->asArray()->one();

        end:
        // return [
        //     'success' => true, 
        //     'message' => 'Berhasil menjalankan cronjob',
        //     'data' => $data
        // ];

        if ($data != null) {
            $result['success'] = true;
            $result['data'] = $data;
        } else {
            $result['success'] = false;
            $result['data'] = null;
            $result['code'] = 404;
        }
        return $result;
    }
    

    public function actionCronjobSearchKonsultanForKonsultasi()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $cek_id_teratas = Konsultasi::find()
            ->where(['is not', 'id_konsultan', null])->orderBy('updated_at DESC')->one(); // id cari konsultan terakhir yang menangani klien
        $konsultasi = Konsultasi::find()
            ->where(['and', ['is_active' => 1], ['is', 'id_konsultan', null]])->orderBy('created_at ASC')->one(); // cari konsultasi yang belum ditangani

        if ($konsultasi != null) {
            // jika belum pernah ada konsultan yang menangani konsultasi, cari user aktif saja
            // jika sudah pernah ada , cari user aktif dengan id setelah id konsultan terakhir, jika tidak ada juga maka cari konsultan aktif
            if ($cek_id_teratas == null) {
                $user = User::find()
                    ->where(
                        [
                            'role_id' => Constant::ROLE_KONSULTAN,
                            'is_active' => 1,
                        ]
                    )->one();
            } else {
                $user = User::find()

                    ->where([
                        'and',
                        [
                            'role_id' => Constant::ROLE_KONSULTAN,
                            'is_active' => 1,
                        ],
                        [
                            '>',
                            'id',
                            $cek_id_teratas->id_konsultan
                        ]
                    ])
                    ->orderBy('id ASC')
                    ->one();
                if ($user == null) {
                    $user = User::find()
                        ->where(
                            [
                                'role_id' => Constant::ROLE_KONSULTAN,
                                'is_active' => 1,
                            ]
                        )->one();
                }
            }

            // cek apakah ada konsultan yang sedang aktif
            if ($user != null) {
                $konsultasi->id_konsultan = $user->id;
                $konsultasi->save();
            } else {
                // otomatis matikan konsultasi jika tidak ditanggapi lebih dari 5 mnit
                $waktu = time() - strtotime($konsultasi->created_at);
                $menit = $waktu / 60;
                if ($menit > 5) {
                    $konsultasi->is_active = 0;
                    $konsultasi->save();
                }
            }
        }

        $data = User::find()->select(['id', 'name', 'email', 'photo_url', 'is_active'])
            ->where(['role_id' => 4])->andWhere(['id' => $cek_id_teratas->id_konsultan])->asArray()->one();

        end:
        return [
            'success' => true, 
            'message' => 'Berhasil menjalankan cronjob',
            'data' => $data
        ];

        // if ($data != null) {
        //     $result['success'] = true;
        //     $result['data'] = $data;
        // } else {
        //     $result['success'] = false;
        //     // $result['data'] = null;
        //     $result['code'] = 404;
        // }
        // return $result;
    }

    public function actionGetSupplier()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = \Yii::$app->request->get('q');
        $out = \app\models\User::find()

            ->where([
                'or',
                ['like', 'user.name', $query],
                ['like', 'user.username', $query],
                ['like', 'user.no_hp', $query],
                ['like', 'user.email', $query],
            ])
            ->join('left join', 't_supplier', 't_supplier.id_user = user.id')
            ->andWhere(['t_supplier.id' => null])
            ->andWhere(['user.flag' => 1])
            ->andWhere(['role_id' => \app\components\Constant::ROLES['supplier']])
            ->limit(25)
            ->select(["user.id", "concat(coalesce(username, ''), ' | ', coalesce(user.name, '')) as text"])
            ->asArray()
            ->all();
        if ($out != []) {
            return ['results' => $out];
        }
        return ['results' => []];
    }

    public function actionNotifikasi()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $user = Constant::getUser();

        if ($user->role_id == \app\components\Constant::ROLES['sa']) {
            $data = Notification::find()

                ->where(['is', 'user_id', null])
                ->andWhere(['read' => 0])
                ->orderBy(['id' => SORT_DESC])
                ->select(['id', 'title', 'description'])
                ->limit(10)
                ->all();
            $jumlah_notif = Notification::find()

                ->where(['is', 'user_id', null])
                ->andWhere(['read' => 0])
                ->count();
        } else {
            $data = Notification::find()

                ->where(['=', 'user_id', $user->id])
                ->andWhere(['read' => 0])
                ->orderBy(['id' => SORT_DESC])
                ->limit(10)
                ->select(['id', 'title', 'description'])
                ->all();
            $jumlah_notif = Notification::find()

                ->where(['=', 'user_id', $user->id])
                ->andWhere(['read' => 0])
                ->count();
        }

        return [
            "data" => $data,
            "jumlah_notif" => $jumlah_notif
        ];
    }
}
