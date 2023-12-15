<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "SiteSettingController".
 * Modified by Defri Indra
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class SiteSettingController extends \app\controllers\api\BaseController
{
    public $modelClass = 'app\models\SiteSetting';

    public function behaviors()
    {
        $parent = parent::behaviors();
        unset($parent['authentication']);

        return $parent;
    }

    public function actionIndex()
    {
        $data = \app\models\SiteSetting::find()->all();
        return [
            "success" => true,
            "data" => $data
        ];
    }
}
