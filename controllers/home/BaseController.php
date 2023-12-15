<?php

namespace app\controllers\home;

use app\components\Constant;
use app\models\AccessLog;
use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
    public function afterAction($action, $result)
    {

        // other custom code here
        $log = new AccessLog();
        $log->ip = Yii::$app->request->userIP;
        $log->controller = get_called_class();
        $log->request = json_encode(Yii::$app->request->bodyParams);
        $log->method = Yii::$app->request->method;
        $log->type = 'web';

        $user = Constant::getUser();
        if ($user) {
            $log->user_id = $user->id;
            $log->username = $user->username;
            $log->role = $user->role->name;
        } else {
            $log->user_id = null;
            $log->username = null;
            $log->role = null;
        }

        $log->save();


        return parent::afterAction($action, $result); // or false to not run the action
    }
}
