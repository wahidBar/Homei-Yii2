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
use app\models\TutorialPemakaian;
use app\models\TutorialPemakaianKategori;
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
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\UploadedFile;

class TutorialController extends BaseController
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
                        'actions' => ['login', 'error', 'index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index'], // add all actions to take guest to login page
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],

            ],

        ];
    }

    public function actionIndex()
    {
        $this->view->title = 'Home i - Tutorial';
        $kategories = TutorialPemakaianKategori::find()->all();
        $id = $_GET['id'];
        if ($id != null) {
            $query = TutorialPemakaian::find()->where(['id_kategori' => $id]);
        } else {
            $query = TutorialPemakaian::find();
        }
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $models = $query->offset($pages->offset)->limit(8)->all();

        return $this->render('index', [
            'models' => $models,
            'kategories' => $kategories,
            'pages' => $pages
        ]);
    }
}
