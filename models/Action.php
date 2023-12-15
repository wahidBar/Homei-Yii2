<?php

namespace app\models;

use app\components\Constant;
use Yii;
use \app\models\base\Action as BaseAction;
use app\models\base\RoleUser;

/**
 * This is the model class for table "action".
 */
class Action extends BaseAction
{
    public static function getAccess($controllerId, $access_project = false, $parameter = "id")
    {
        $rules = [];

        $menu = Menu::findOne(['controller' => $controllerId]);
        if ($menu) {
            $rules[] = [
                "allow" => true,
                "actions" => explode(',', $menu->except)
            ];
        }

        if (\Yii::$app->user->identity != null) {
            // $roles = RoleUser::find()->where(['id_user' => \Yii::$app->user->identity->id])->select('id_role')->column();
            $roles[] = Constant::getUser()->role_id;
            if ($access_project) {
                $additional_role = \app\models\ProyekAnggota::find()->where([
                    "id_proyek" => Yii::$app->request->get($parameter),
                    "id_user" => Yii::$app->user->id,
                ])->one();

                if ($additional_role) {
                    $roles[] = $additional_role->id_role;
                }
            }

            $allowed = Action::getAllowedAction($controllerId, $roles);


            if (count($allowed) != 0) {
                $rules[] = [
                    'actions' => $allowed,
                    'allow' => true,
                    'roles' => ['@'],
                ];
            }
        }

        $rules[] = [
            'allow' => false,
        ];



        return [
            'as beforeRequest' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => $rules,
            ],
        ];
    }

    public static function getAccessAPI($controllerId)
    {
        $rules = [];

        if (\Yii::$app->user->identity != null) {
            $roles = RoleUser::find()->where(['id_user' => \Yii::$app->user->identity->id])->select('id_role')->column();
            $allowed = Action::getAllowedAction($controllerId, $roles);

            if (count($allowed) != 0) {
                $rules['only'] = $allowed;
            }
        }

        return [
            "authenticator" => array_merge([
                'class' => '\app\components\CustomAuth',
            ], $rules)
        ];
    }

    public static function getAllowedAction($controllerId, $role_id)
    {
        //TODO: Using cache to speed process
        $output = [];
        foreach (Action::find()->where(["controller_id" => $controllerId])->all() as $action) {
            //bypass for super admin
            foreach ($role_id as $r) {
                if ($r == 1) {
                    $output[] = $action->action_id;
                } else {
                    $roleAction = RoleAction::find()->where([
                        "and",
                        ["action_id" => $action->id],
                        [
                            "in",
                            "role_id",
                            $r
                        ],
                    ])->all();
                    if ($roleAction) {
                        $output[] = $action->action_id;
                    }
                }
            }
        }

        return $output;
    }
}
