<?php

namespace app\models;

use Yii;
use \app\models\base\MasterGudang as BaseMasterGudang;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_master_gudang".
 * Modified by Defri Indra M
 */
class MasterGudang extends BaseMasterGudang
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
