<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "MasterPembayaranController".
 * Modified by Defri Indra
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class MasterPembayaranController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\MasterPembayaran';

    public function actionIndex()
    {
        $model = $this->modelClass::find()->where(['status' => 1]);
        return $model->all();
    }
}
