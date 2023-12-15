<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "PopupController".
 * Modified by Defri Indra
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class PopupController extends \app\controllers\api\BaseController
{
    public $modelClass = 'app\models\Popup';

    public function behaviors()
    {
        $parent = parent::behaviors();
        if (isset($parent['authentication']['except'])) {
            $parent['authentication']['except'][] = 'index';
        }

        return $parent;
    }

    public function actionIndex()
    {
        $query = $this->modelClass::find()->one();
        return $query;
    }
}
