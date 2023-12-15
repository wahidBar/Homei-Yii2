<?php

namespace app\models;

use Yii;
use \app\models\base\SupplierOrderDetail as BaseSupplierOrderDetail;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_supplier_order_detail".
 * Modified by Defri Indra M
 */
class SupplierOrderDetail extends BaseSupplierOrderDetail
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
