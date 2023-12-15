<?php

namespace app\controllers;

use app\components\annex\Tabs;
use app\components\Constant;
use app\models\Notification;
use app\models\search\NotificationSearch;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * This is the class for controller "NotificationController".
 * Modified by Defri Indra
 */
class NotificationController extends Controller
{

    public function actionIndex()
    {
        if (Constant::getUser("id") == null) throw new HttpException(403, "Halaman tidak ditemukan");
        $searchModel  = new NotificationSearch();
        $dataProvider = $searchModel->search($_GET);

        Tabs::clearLocalStorage();

        Url::remember();
        \Yii::$app->session['__crudReturnUrl'] = null;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionRedirect($id)
    {
        $user = Constant::getUser();

        $model = Notification::findOne([
            "id" => $id,
            "user_id" => $user->role_id == Constant::ROLES['sa'] ? null : $user->id,
        ]);
        if ($model == null) throw new HttpException(404, "Data tidak ditemukan");

        $model->scenario = $model::SCENARIO_UPDATE;
        $model->read = 1;
        $model->save();

        $link = ["/" . $model->controller];
        if ($model->params != null) {
            $params = (array)json_decode($model->params);
            $link = array_merge($link, $params);
        }


        return $this->redirect($link);
    }
}
