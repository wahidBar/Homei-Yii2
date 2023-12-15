<?php

namespace app\controllers\api;

/**
 * This is the class for REST controller "SupplierController".
 * Modified by Defri Indra
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class SupplierController extends \app\controllers\api\BaseController
{
    public $modelClass = 'app\models\Supplier';

    public function actionIndex()
    {
        $query = $this->modalClass::find();
        return $this->dataProvider($query);
    }

    public function actionCreate()
    {
        $model = new $this->modalClass;
        $model->scenario = $model::SCENARIO_CREATE;

        try {
            if ($model->load(\Yii::$app->request->post(), '')) {
                if ($model->validate()) {
                    $model->save();

                    return [
                        "success" => true,
                        "message" => "Data berhasil dihapus"
                    ];
                }

                throw new \yii\web\HttpException(422);
            }
            throw new \yii\web\HttpException(400);
        } catch (\Throwable $th) {
            throw new \yii\web\HttpException($th->statusCode ?? 500, $th->getMessage());
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = $model::SCENARIO_UPDATE;

        try {
            if ($model->load(\Yii::$app->request->post(), '')) {
                if ($model->validate()) {
                    $model->save();

                    return [
                        "success" => true,
                        "message" => "Data berhasil dihapus"
                    ];
                }

                throw new \yii\web\HttpException(422);
            }
            throw new \yii\web\HttpException(400);
        } catch (\Throwable $th) {
            throw new \yii\web\HttpException($th->statusCode ?? 500, $th->getMessage());
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        try {
            $model->delete();
            return [
                "success" => true,
                "message" => "Data berhasil dihapus"
            ];
        } catch (\Throwable $th) {
            throw new \yii\web\HttpException($th->statusCode ?? 500, $th->getMessage());
        }
    }
}
