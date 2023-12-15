<?php

namespace app\models;

use Yii;
use \app\models\base\Notification as BaseNotification;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_notification".
 * Modified by Defri Indra M
 */
class Notification extends BaseNotification
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
