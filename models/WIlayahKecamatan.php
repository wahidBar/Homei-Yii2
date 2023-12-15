<?php

namespace app\models;

use Yii;
use \app\models\base\WilayahKecamatan as BaseWilayahKecamatan;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "wilayah_kecamatan".
 * Modified by Defri Indra M
 */
class WilayahKecamatan extends BaseWilayahKecamatan
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
