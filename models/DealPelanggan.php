<?php

namespace app\models;

use Yii;
use \app\models\base\DealPelanggan as BaseDealPelanggan;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_deal_pelanggan".
 * Modified by Defri Indra M
 */
class DealPelanggan extends BaseDealPelanggan
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
