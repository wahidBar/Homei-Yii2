<?php

namespace app\models;

use Yii;
use \app\models\base\UserSocialMedia as BaseUserSocialMedia;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user_social_media".
 * Modified by Defri Indra M
 */
class UserSocialMedia extends BaseUserSocialMedia
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
