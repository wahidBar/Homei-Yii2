<?php

namespace app\models;

use Yii;
use \app\models\base\MasterMaterial as BaseMasterMaterial;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_master_material".
 * Modified by Defri Indra M
 */
class MasterMaterial extends BaseMasterMaterial
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
