<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "NotificationController".
 * Modified by Defri Indra
 */

use app\components\Constant;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;

class NotificationController extends \app\controllers\api\BaseController
{
    public $modelClass = 'app\models\Notification';

    function verbs()
    {
        return [
            'index' => ['GET'],
            'read' => ['POST'],
        ];
    }

    public function actionJumlahNotifBelumDibaca()
    {
        $count_not_read = $this->modelClass::find()
            ->andWhere(['user_id' => Constant::getUser()->id])
            ->andWhere(['read' => 0])
            ->select(['id'])
            ->count();

        return [
            "success" => true,
            "data" => intval($count_not_read),
        ];
    }

    public function actionIndex()
    {
        $query = $this->modelClass::find()
            ->andWhere(['user_id' => Constant::getUser()->id])
            ->select(['id', 'android_route', 'params', 'title', 'description', 'read'])
            ->orderBy(['read' => SORT_ASC, 'id' => SORT_DESC]);
        $dataProvider = $this->dataProvider($query);

        return $dataProvider;
    }

    public function actionRead($id)
    {
        $one = $this->modelClass::find()
            ->andWhere(['user_id' => Constant::getUser()->id, 'id' => $id])
            ->andWhere(['read' => 0])
            ->one();

        if ($one == null) {
            throw new HttpException(404);
        }

        $one->read = 1;
        $one->save();

        return [
            "success" => true,
            "message" => "Successfully read notification",
            "data" => $one,
        ];
    }
}
