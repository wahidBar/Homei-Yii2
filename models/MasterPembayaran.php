<?php

namespace app\models;

use Yii;
use \app\models\base\MasterPembayaran as BaseMasterPembayaran;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_master_pembayaran".
 * Modified by Defri Indra M
 */
class MasterPembayaran extends BaseMasterPembayaran
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
