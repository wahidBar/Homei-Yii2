<?php

namespace app\models;

use Yii;
use \app\models\base\Supplier as BaseSupplier;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_supplier".
 * Modified by Defri Indra M
 */
class Supplier extends BaseSupplier
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
