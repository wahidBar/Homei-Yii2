<?php

namespace app\models;

use Yii;
use \app\models\base\MasterVariableHitungan as BaseMasterVariableHitungan;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_master_variable_hitungan".
 * Modified by Defri Indra M
 */
class MasterVariableHitungan extends BaseMasterVariableHitungan
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
