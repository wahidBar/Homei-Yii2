<?php

namespace app\models;

use Yii;
use \app\models\base\SupplierPengiriman as BaseSupplierPengiriman;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_supplier_pengiriman_log".
 * Modified by Defri Indra M
 */
class SupplierPengiriman extends BaseSupplierPengiriman
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
