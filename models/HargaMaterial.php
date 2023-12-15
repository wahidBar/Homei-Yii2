<?php

namespace app\models;

use Yii;
use \app\models\base\HargaMaterial as BaseHargaMaterial;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_harga_material".
 * Modified by Defri Indra M
 */
class HargaMaterial extends BaseHargaMaterial
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
