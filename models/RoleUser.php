<?php

namespace app\models;

use Yii;
use \app\models\base\RoleUser as BaseRoleUser;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "role_user".
 * Modified by Defri Indra M
 */
class RoleUser extends BaseRoleUser
{
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
            ]
        );
    }
    
}
