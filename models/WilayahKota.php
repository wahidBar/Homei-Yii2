<?php

namespace app\models;

use Yii;
use \app\models\base\WilayahKota as BaseWilayahKota;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "wilayah_kota".
 * Modified by Defri Indra M
 */
class WilayahKota extends BaseWilayahKota
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
