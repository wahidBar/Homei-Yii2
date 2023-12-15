<?php

namespace app\models;

use Yii;
use \app\models\base\IsianLanjutanRuangan as BaseIsianLanjutanRuangan;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_isian_lanjutan_ruangan".
 * Modified by Defri Indra M
 */
class IsianLanjutanRuangan extends BaseIsianLanjutanRuangan
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
