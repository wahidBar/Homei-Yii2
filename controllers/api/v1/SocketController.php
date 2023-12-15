<?php

namespace app\controllers\api\v1;

use Yii;

/**
 * This is the class for REST controller "BannerController".
 * Modified by Defri Indra
 */

class SocketController extends \app\controllers\api\BaseController
{
    public $modelClass = false;

    public function behaviors()
    {
        $parent = parent::behaviors();
        unset($parent['authentication']);

        return $parent;
    }

    public function actionIndex()
    {
        $list = ["socket_host", "socket_protocol_asset", "socket_protocol", "socket_notification_sound"];

        $template = [];
        foreach ($list as $item) $template[$item] = Yii::$app->params[$item];

        return  $template;
    }
}
