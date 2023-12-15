<?php

namespace app\models;

use Yii;
use \app\models\base\UserOtp as BaseUserOtp;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user_otp".
 * Modified by Defri Indra M
 */
class UserOtp extends BaseUserOtp
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
