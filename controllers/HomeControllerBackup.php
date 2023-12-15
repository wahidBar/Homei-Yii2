<?php

namespace app\controllers;

use app\components\Constant;
use app\components\HttpHelper;
use app\components\UploadFile;
use app\models\BalasanKonsultasi;
use app\models\base\GoogleAuth;
use app\models\DataDiri;
use app\models\DealPelanggan;
use app\models\FormKonsultasi;
use app\models\Galeri;
use app\models\Home;
use app\models\IsianLanjutan;
use app\models\Konsultasi;
use app\models\KonsultasiChat;
use app\models\LoginGoogle;
use app\models\MasterKonsepDesain;
use app\models\Penawaran;
use app\models\Portofolio;
use app\models\PortofolioGambar;
use app\models\search\IsianLanjutanSearch;
use app\models\Slides;
use app\models\TabHome;
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
use yii\web\HttpException;
use yii\web\Response;
use yii\web\UploadedFile;


class HomeController extends Controller
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
                        'actions' => ['login', 'error', 'index', 'google', 'portofolio', 'detail-portofolio'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'profile', 'edit-profile', 'formulir-design-bangunan', 'detail-formulir-design-bangunan', 'getdataprov', 'getdatakab', 'getdatakec', 'getdatades', 'konsultasi', 'daftar-penawaran-project', 'detail-penawaran-project', 'pengajuan-design-bangunan', 'detail-pengajuan-design', 'formulir-data-diri', 'konsep-design', 'formulir-konsultasi', 'edit-formulir-deal-project', 'formulir-deal-project', 'detail-deal-project', 'deal-project', 'akhiri-konsultasi'], // add all actions to take guest to login page
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],

            ],

        ];
    }
    public function actionIndex()
    {
        $this->view->title = 'Home i';
        $galleries = Galeri::find()->limit(10)->all();
        $hp_galleries = Galeri::find()->limit(4)->all();

        return $this->render('index', [
            'galleries' => $galleries,
            'hp_galleries' => $hp_galleries
        ]);
    }

    public function actionLogout()
    {
        //log last login column
        $user = Yii::$app->user->identity;
        $user->last_logout = new Expression("NOW()");
        // $user->save();

        Yii::$app->user->logout();

        return $this->redirect(['site/login']);
    }

    public function actionProfile()
    {
        $this->view->title = 'Home i - Profile';
        $model = User::find()->where(["id" => Yii::$app->user->id])->one();
        $model->password = "";
        return $this->render('profile', [
            'model' => $model
        ]);
    }

    public function actionEditProfile()
    {
        $model = User::find()->where(["id" => Yii::$app->user->id])->one();
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
                toastSuccess("Profile berhasil diubah");
            } else {
                toastError("Profile gagal diubah");
            }
            return $this->redirect(["profile"]);
        }
        end:
        return $this->render('edit-profile', [
            'model' => $model,
        ]);
    }

    public function actionKonsultasi($ticket)
    {
        $this->view->title = 'Home i - Konsultasi';
        $galleries = Galeri::find()->limit(10)->all();
        $hp_galleries = Galeri::find()->limit(4)->all();


        $user = Constant::getUser();
        $params['ticket'] = $ticket;
        $params['id_user'] = $user->id;

        $model = Konsultasi::findOne($params);
        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        $list_chat = $model->getKonsultasiChats()->all();
        $konsultan = $model->konsultan;
        $user = $model->user;

        return $this->render('konsultasi', compact('model', 'konsultan', 'user', 'galleries', 'list_chat', 'hp_galleries'));
    }

    public function actionAkhiriKonsultasi($ticket)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $this->layout = false;
        $user = Yii::$app->user->identity;

        if ($user->role_id === Constant::ROLE_KONSULTAN) {
            $chat_active = Konsultasi::find()->where(['ticket' => $ticket, 'id_konsultan' => $user->id, 'is_active' => 1])->one();
        } else {
            $chat_active = Konsultasi::find()->where(['ticket' => $ticket, 'id_user' => $user->id, 'is_active' => 1])->one();
        }

        if ($chat_active->is_active == 1) {
            $chat_active->is_active = 0;
            if ($chat_active->validate()) {
                $chat_active->save();
                toastSuccess("Data berhasil disimpan");
                return $this->redirect(['formulir-design-bangunan']);
            }
            // return ["message" => Constant::flattenError($chat_active->getErrors()), "success" => false];
            toastError("Telah terjadi kesalahan");
            return $this->redirect(['formulir-konsultasi']);
        }
        toastError("Telah terjadi kesalahan");
        return $this->redirect(['formulir-konsultasi']);
    }

    public function actionPortofolio()
    {
        $this->view->title = 'Home i - Portofolio';
        $models = Portofolio::find()->all();
        return $this->render('portofolio-project', [
            'models' => $models
        ]);
    }

    public function actionDetailPortofolio($id)
    {
        $this->view->title = 'Home i - Detail Portofolio';
        $model = Portofolio::findOne($id);
        return $this->render('detail-portofolio', [
            'model' => $model
        ]);
    }


    public function actionKonsepDesign()
    {
        $this->view->title = 'Home i - Konsep Design';
        $designs = MasterKonsepDesain::find()->where(['flag' => 1])->all();
        $galleries = Galeri::find()->limit(10)->all();
        $hp_galleries = Galeri::find()->limit(4)->all();
        return $this->render('konsep-design', [
            'designs' => $designs,
            'galleries' => $galleries,
            'hp_galleries' => $hp_galleries
        ]);
    }

    public function actionFormulirDesignBangunan()
    {
        $this->view->title = 'Home i - Formulir Desain Bangunan';
        $model = new IsianLanjutan;
        $model->scenario = $model::SCENARIO_CREATE;

        // var_dump(Yii::$app->request->post('id_provinsi'));die;
        try {
            if ($model->load($_POST)) :
                $id_konsep = $_GET['id'];
                if ($id_konsep != null) {
                    $model->id_konsep_design = $id_konsep;
                }
                $u = str_replace(",", "", $model->budget);
                $konsep = MasterKonsepDesain::findOne(['id' => $model->id_konsep_design]);
                $a = \Yii::$app->user->identity->name;
                $b = $konsep->nama_konsep;
                $c = $a . '-' . $b;
                $model->label = $c;
                $model->budget = $u;
                $model->id_user = \Yii::$app->user->identity->id;
                $model->id_wilayah_provinsi = Yii::$app->request->post('id_provinsi');
                $model->id_wilayah_kota = Yii::$app->request->post('id_kota');
                if ($model->validate()) :
                    $model->created_by = \Yii::$app->user->identity->id;
                    $model->save();
                    toastSuccess("Data berhasil disimpan");
                    return $this->redirect(['formulir-konsultasi', 'id' => $model->id]);
                endif;
                toastError("Telah terjadi kesalahan");
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->render('design-bangunan', $model->render());
    }

    public function actionFormulirKonsultasi()
    {
        $this->view->title = 'Home i - Formulir Konsultasi';

        $konsultasi_aktif = Konsultasi::find()->where(['id_user' => Constant::getUser()->id, 'is_active' => 1])->one();
        if ($konsultasi_aktif) {
            return $this->redirect(['/home/konsultasi', 'ticket' => $konsultasi_aktif->ticket]);
        }

        $model = new Konsultasi();
        $model->scenario = $model::SCENARIO_CREATE;

        $model->id_user = \Yii::$app->user->identity->id;
        $model->id_konsultan = 2;
        $ticket = "Ticket-" . Yii::$app->security->generateRandomString(12);
        $model->ticket = $ticket;

        try {
            if ($model->validate()) :
                $model->save();
                toastSuccess("Data berhasil disimpan");
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

    public function actionDetailFormulirDesignBangunan($id)
    {
        $this->view->title = 'Home i - Detail Design Bangunan';

        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember();
        Tabs::rememberActiveState();
        // $model = IsianLanjutan::find()->where(['id'=>$id])->one();

        $model = IsianLanjutan::findOne(['id' => $id, 'created_by' => \Yii::$app->user->identity->id]);

        if ($model != null) {
            return $this->render('detail-design-bangunan', [
                'model' => $model,
            ]);
        }
        return $this->redirect(["home/index"]);
    }

    public function actionPengajuanDesignBangunan()
    {
        $this->view->title = 'Home i - Daftar Pengajuan Design Bangunan';

        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember();
        Tabs::rememberActiveState();
        // $model = IsianLanjutan::find()->where(['id'=>$id])->one();

        $models = IsianLanjutan::find()->where(['created_by' => \Yii::$app->user->identity->id])->all();

        if ($models != null) {
            return $this->render('pengajuan-design-bangunan', [
                'models' => $models,
            ]);
        }
        return $this->redirect(["home/index"]);
    }

    public function actionDetailPengajuanDesign($id)
    {
        $this->view->title = 'Home i - Detail Design Bangunan';

        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember();
        Tabs::rememberActiveState();
        // $model = IsianLanjutan::find()->where(['id'=>$id])->one();

        $model = IsianLanjutan::findOne(['id' => $id, 'created_by' => \Yii::$app->user->identity->id]);

        if ($model != null) {
            return $this->render('detail-design-bangunan', [
                'model' => $model,
            ]);
        }
        return $this->redirect(["home/index"]);
    }

    public function actionDealProject()
    {
        $this->view->title = 'Home i - Daftar Deal Proyek';

        $models = DealPelanggan::find()->where(['id_user' => \Yii::$app->user->identity->id])->all();
        if ($models != null) {
            return $this->render('index-deal-project', [
                'models' => $models,
            ]);
        }
        return $this->redirect(["home/index"]);
    }

    public function actionFormulirDealProject($id)
    {
        $this->view->title = 'Home i - Formulir Deal Project';

        $model = new DealPelanggan();
        $model->scenario = $model::SCENARIO_CREATE;
        $model->id_user = \Yii::$app->user->identity->id;
        $model->id_penawaran = $id;
        $model->nama_pelanggan = \Yii::$app->user->identity->name;

        try {
            if ($model->load($_POST)) :
                if ($model->validate()) :
                    $model->save();
                    toastSuccess("Data berhasil disimpan");
                    return $this->redirect(['detail-deal-project', 'id' => $model->id]);
                endif;
                toastError("Telah terjadi kesalahan");
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->render('formulir-deal-project', $model->render());
    }

    public function actionDetailDealProject($id)
    {
        $this->view->title = 'Home i - Detail Deal Project';

        // $this->view->title = 'Home i - Detail Proyek';
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember();
        Tabs::rememberActiveState();

        return $this->render('detail-deal-project', [
            'model' => DealPelanggan::find()->where(['id' => $id])->andWhere(['id_user' => \Yii::$app->user->identity->id])->one(),
        ]);
    }

    public function actionDaftarPenawaranProject($id)
    {
        $this->view->title = 'Home i - Daftar Penawaran Project';

        $query = new Query;
        $query->select(['t_penawaran.id as penawaran_id', 't_penawaran.id_isian_lanjutan', 't_penawaran.estimasi_waktu', 't_penawaran.tgl_transaksi', 't_penawaran.harga_penawaran', 't_isian_lanjutan.id', 't_isian_lanjutan.id_user'])
            ->from('t_penawaran')
            ->join(
                'LEFT JOIN',
                't_isian_lanjutan',
                't_isian_lanjutan.id =t_penawaran.id_isian_lanjutan'
            )
            ->where(['id_user' => \Yii::$app->user->identity->id])
            ->andWhere(['id_isian_lanjutan' => $id]);
        $command = $query->createCommand();
        $count = $command->execute();
        $models = $command->queryAll();

        // var_dump($models);die;
        if ($models != null) {
            return $this->render('daftar-penawaran-project', [
                'models' => $models,
                'count' => $count
            ]);
        }
        return $this->redirect(["home/index"]);
    }

    public function actionDetailPenawaranProject($id)
    {
        $this->view->title = 'Home i - Detail Penawaran Project';

        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember();
        Tabs::rememberActiveState();
        // $model = IsianLanjutan::find()->where(['id'=>$id])->one();

        $model = Penawaran::findOne($id);

        if ($model != null) {
            return $this->render('detail-penawaran-project', [
                'model' => $model,
            ]);
        }
        return $this->redirect(["home/index"]);
    }

    public function actionDealProject2($id)
    {
        $this->view->title = 'Home i - Deal Project';
        $model = new DealPelanggan();
        $model->scenario = $model::SCENARIO_CREATE;

        $query = new Query();
        $query->select(
            [
                't_penawaran.id as id_penawaran',
                't_isian_lanjutan.id AS id_isian',
            ]
        )
            ->from('t_isian_lanjutan')
            ->join(
                'INNER JOIN',
                't_penawaran',
                't_isian_lanjutan.id = t_penawaran.id_isian_lanjutan'
            )
            ->where('t_penawaran.id = ' . $id . '');

        $command = $query->createCommand();
        $data = $command->queryOne();

        $model->id_penawaran = $data['id_penawaran'];
        $model->id_isian_lanjutan = $data['id_isian'];
        // SELECT t_isian_lanjutan.id as 'id_isian', t_penawaran.id as 'id_penawaran'
        // FROM t_isian_lanjutan INNER JOIN t_penawaran
        // ON t_isian_lanjutan.id = t_penawaran.id_isian_lanjutan
        // where t_penawaran.id = 3

        try {

            if ($model->load($_POST)) :

                if ($model->validate()) :
                    $model->created_by = \Yii::$app->user->identity->id;
                    $model->save();
                    toastSuccess("Data berhasil disimpan");
                    return $this->redirect(['detail-form-project', 'id' => $model->id]);
                endif;
                toastError("Telah terjadi kesalahan");
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->render('deal-project', $model->render());
    }

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
            $getUser = User::find()->where(['gid' => $gid])->one();

            Yii::$app->user->login($getUser);
            return $this->redirect(["home/index"]);
        } else {

            $g_user = new GoogleAuth();
            $g_user->scenario = $g_user::SCENARIO_CREATE;
            try {
                $g_user->gid = $info->id;
                $g_user->name = $info->name;
                $g_user->photo_url = $info->picture;
                $g_user->email = $info->email;
                $g_user->role_id = 3;

                $g_user->save();

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

    // Provinsi
    public function actionGetdataprov()
    {
        $model = new Home();
        $searchTerm = $_POST['searchTerm'];
        $response   = $model->getprov($searchTerm);
        echo json_encode($response);
    }

    // Kabupaten
    public function actionGetdatakab($id_prov)
    {
        $model = new Home();
        $searchTerm = $_POST['searchTerm'];
        $response   = $model->getkab($id_prov, $searchTerm);
        echo json_encode($response);
    }

    // Kecamatan
    public function actionGetdatakec($id_kec)
    {
        $model = new Home();
        $searchTerm = $_POST['searchTerm'];
        $response   = $model->getkec($id_kec, $searchTerm);
        echo json_encode($response);
    }

    // Desa
    public function actionGetdatades($id_desa)
    {
        $model = new Home();
        $searchTerm = $_POST['searchTerm'];
        $response   = $model->getdes($id_desa, $searchTerm);
        echo json_encode($response);
    }
}
