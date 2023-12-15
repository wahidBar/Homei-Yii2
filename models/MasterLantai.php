<?php

namespace app\models;

use Yii;
use \app\models\base\MasterLantai as BaseMasterLantai;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_master_lantai".
 * Modified by Defri Indra M
 */
class MasterLantai extends BaseMasterLantai
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
