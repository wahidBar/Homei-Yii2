<?php

namespace app\models;

use Yii;
use \app\models\base\MasterSatuan as BaseMasterSatuan;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_master_satuan".
 * Modified by Defri Indra M
 */
class MasterSatuan extends BaseMasterSatuan
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
