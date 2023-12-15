<?php

namespace app\components;

use app\models\Action;
use app\models\Menu;
use app\models\RoleAction;
use Yii;
use yii\filters\auth\AuthMethod;

class CustomAuth extends AuthMethod
{
    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        $response = SSOToken::checkToken();
        if ($response['success'] == 0) {
            return;
        }

        return $response;
    }

    public static function auth($id)
    {
        return $id;
    }

    public static function grantAccess($role_id)
    {
        $actionId = Action::find()->where(['controller_id' => Yii::$app->controller->id, 'action_id' => Yii::$app->controller->action->id])->select('id')->limit(1)->column();
        $grant = RoleAction::find()->where(['role_id' => $role_id, 'action_id' => $actionId])->one();
        return isset($grant) ? true : false;
    }
}
