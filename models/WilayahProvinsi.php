<?php

namespace app\models;

use Yii;
use \app\models\base\WilayahProvinsi as BaseWilayahProvinsi;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "wilayah_provinsi".
 * Modified by Defri Indra M
 */
class WilayahProvinsi extends BaseWilayahProvinsi
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
