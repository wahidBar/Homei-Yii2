<?php

namespace app\models;

use Yii;
use \app\models\base\MasterRuangan as BaseMasterRuangan;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_master_ruangan".
 * Modified by Defri Indra M
 */
class MasterRuangan extends BaseMasterRuangan
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
