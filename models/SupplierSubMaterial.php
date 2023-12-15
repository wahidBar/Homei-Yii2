<?php

namespace app\models;

use Yii;
use \app\models\base\SupplierSubMaterial as BaseSupplierSubMaterial;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_supplier_submaterial".
 * Modified by Defri Indra M
 */
class SupplierSubMaterial extends BaseSupplierSubMaterial
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
