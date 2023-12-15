<?php

namespace app\controllers\home;

use app\components\Constant;
use app\models\AccessLog;
use app\models\User;
use Yii;
use yii\rest\Controller;
use yii\web\HttpException;

class ApiController extends Controller
{
    public function afterAction($action, $result)
    {

        // other custom code here
        $log = new AccessLog();
        $log->ip = Yii::$app->request->userIP;
        $log->controller = get_called_class();
        $log->request = json_encode(Yii::$app->request->bodyParams);
        $log->method = Yii::$app->request->method;

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

    public function actionGetSubMaterial()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['id'])) {
            $parents = $_POST['id'];
            $selected = $_POST['selected'];
            if ($parents != null) {
                $cat_id = $parents[0];
                $out = self::getSubMaterial($cat_id);
                return ['output' => $out, 'selected' => $selected];
            }
        }
        return ['output' => [], 'selected' => ''];
    }

    private static function getSubMaterial($id_parent)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        $models = \app\models\SupplierMaterial::find()->where(['id' => $id_parent])->one();
        $model = \app\models\SupplierSubMaterial::find()->where(['material_id' => $models->id])->all();
        foreach ($model as $row) {
            $out[] = [
                "id" => $row->id,
                "name" => $row->nama,
            ];
        }


        return $out;
    }

    public function actionGetUser($id)
    {
        $user = Constant::getUser();
        if ($user == null) throw new HttpException(403);

        $data = User::findOne($id);
        if ($user == null) throw new HttpException(404);
        return $data;
    }
}
