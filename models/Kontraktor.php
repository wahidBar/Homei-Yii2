<?php

namespace app\models;

use Yii;
use \app\models\base\Kontraktor as BaseKontraktor;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_kontraktor".
 * Modified by Defri Indra M
 */
class Kontraktor extends BaseKontraktor
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
