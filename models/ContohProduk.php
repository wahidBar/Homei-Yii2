<?php

namespace app\models;

use Yii;
use \app\models\base\ContohProduk as BaseContohProduk;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_contoh_produk".
 * Modified by Defri Indra M
 */
class ContohProduk extends BaseContohProduk
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
