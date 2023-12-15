<?php

namespace app\models;

use Yii;
use \app\models\base\AccessLog as BaseAccessLog;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "access_log".
 * Modified by Defri Indra M
 */
class AccessLog extends BaseAccessLog
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
