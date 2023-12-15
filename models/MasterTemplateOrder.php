<?php

namespace app\models;

use Yii;
use \app\models\base\MasterTemplateOrder as BaseMasterTemplateOrder;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_master_template_order".
 * Modified by Defri Indra M
 */
class MasterTemplateOrder extends BaseMasterTemplateOrder
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
