<?php

namespace app\models;

use Yii;
use \app\models\base\Config as BaseConfig;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_config".
 * Modified by Defri Indra M
 */
class Config extends BaseConfig
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
