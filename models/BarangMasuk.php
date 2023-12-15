<?php

namespace app\models;

use Yii;
use \app\models\base\BarangMasuk as BaseBarangMasuk;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_barang_masuk".
 * Modified by Defri Indra M
 */
class BarangMasuk extends BaseBarangMasuk
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
