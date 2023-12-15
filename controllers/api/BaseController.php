<?php

namespace app\controllers\api;

use app\components\CustomAuth;
use app\models\Menu;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;

/**
 * This is the class for REST controller "PlaceController".
 */

class BaseController extends \yii\rest\ActiveController
{
    public function behaviors()
    {
        $except = Menu::findOne(['controller' => Yii::$app->controller->id]);
        if (isset($except)) {
            $except = explode(",", trim($except->except));
        } else {
            $except = [];
        }

        $parent = parent::behaviors();
        $parent['authentication'] = [
            "class" => "\app\components\CustomAuth",
            "except" => $except
        ];

        return $parent;
    }

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'data',
    ];

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $parent = parent::actions();
        // $parent['index']['prepareDataProvider'] = [$this, "prepareDataProvider"];

        unset($parent['index']);
        unset($parent['view']);
        unset($parent['create']);
        unset($parent['update']);
        unset($parent['delete']);

        return $parent;
    }

    public function dataProvider($query, $perpage = null, $withPagination = true)
    {
        $setting = [
            'query' => $query,
            'pagination' => [
                'pageSize' => ($perpage) ? $perpage : 20,
            ],
        ];

        if ($withPagination == false) {
            return $query->all();
        }
        return new ActiveDataProvider($setting);
    }

    public function findModel($query, $customClass = null)
    {
        try {
            if ($customClass) {
                $model = $customClass::findOne($query);
            } else {
                $model = $this->modelClass::findOne($query);
            }
        } catch (Throwable $th) {
            throw new HttpException(400);
        }

        if ($model == null) {
            throw new HttpException(404);
        }

        return $model;
    }

    protected function grantAccess($role_id)
    {
        $grant = CustomAuth::grantAccess($role_id);
        if ($grant) {
            return true;
        }

        throw new HttpException(403, "Forbidden Access this action");
    }
}
