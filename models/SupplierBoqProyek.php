<?php

namespace app\models;

use Yii;
use \app\models\base\SupplierBoqProyek as BaseSupplierBoqProyek;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_supplier_boq_proyek".
 * Modified by Defri Indra M
 */
class SupplierBoqProyek extends BaseSupplierBoqProyek
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
