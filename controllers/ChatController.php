<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\base;

use app\components\Constant;
use app\components\HttpHelper;
use app\models\DataDiri;
use app\models\Konsultasi;
use app\models\search\DataDiriSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\web\Response;

/**
 * DataDiriController implements the CRUD actions for DataDiri model.
 **/
class DataDiriController extends \app\components\productive\DefaultActiveController
{

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     **/
    public $enableCsrfValidation = false;


    /**
     * Lists all DataDiri models.
     * @return mixed
     */
    public function actionUser($username)
    {
        $model  = Konsultasi::find()
            ->innerJoin('user', 'user.id = t_konsultasi.id_user')
            ->andWhere(['=', 'user.username', $username])
            ->orderBy('t_konsultasi.created_at DESC')
            ->all();

        return $this->render('index');
    }


    /**
     * Lists all DataDiri models.
     * @return mixed
     */
    public function actionOnMessage($ticket)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Constant::getUser();
        $model  = Konsultasi::find()->andWhere([
            'ticket' => $ticket,
            'is_active' => 1
        ])->one();
        $data = Yii::$app->request->post();

        if (trim($data['message']) != "") {
            // HttpHelper::
        } else {
            throw new HttpException(400, "Pesan tidak boleh kosong");
        }
    }
}
