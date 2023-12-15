<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "ConfigController".
 * Modified by Defri Indra
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class ConfigController extends \app\controllers\api\BaseController
{
    public $modelClass = '\app\models\Config';

    public function behaviors()
    {
        $parent = parent::behaviors();
        $parent['authentication']['except'] = ['index', 'view'];
        return $parent;
    }

    public function actionIndex()
    {
        $query = $this->modelClass::find();
        return $this->dataProvider($query);
    }

    public function actionView($id)
    {
        $data = $this->findModel($id);
        return [
            "success" => true,
            "data" => $data
        ];
    }

    public function findModel($query, $customClass = null)
    {
        try {
            if ($customClass) {
                $model = $customClass::findOne($query);
            } else {
                $model = $this->modelClass::findOne($query);
            }
        } catch (\Throwable $th) {
            throw new \yii\web\HttpException(400);
        }

        if ($model == null) {
            throw new \yii\web\HttpException(404);
        }

        if ($model->is_active == 0) {
            throw new \yii\web\HttpException(404);
        }

        return $model;
    }
}
