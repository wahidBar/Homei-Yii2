<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "BannerController".
 * Modified by Defri Indra
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class BannerController extends \app\controllers\api\BaseController
{
    public $modelClass = 'app\models\Slides';

    public function behaviors()
    {
        $parent = parent::behaviors();
        unset($parent['authentication']);

        return $parent;
    }

    public function actionIndex()
    {
        $model = $this->modelClass::find()
            ->andWhere(['type' => 1])
            ->select(['id', 'image', 'title', 'subtitle', 'component', 'params', 'type', 'redirect_type'])
            ->all();
        return $model;
    }
}
