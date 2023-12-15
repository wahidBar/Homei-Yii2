<?php

namespace app\models;

use app\components\ConstantHomeis;
use Yii;
use \app\models\base\SmarthomeKontrol as BaseSmarthomeKontrol;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_smarthome_kontrol".
 * Modified by Defri Indra M
 */
class SmarthomeKontrol extends BaseSmarthomeKontrol
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


    public function getPinLabel()
    {
        return ConstantHomeis::PIN[$this->pin];
    }

    public function nonActivateControl()
    {
        $this->flag = 0;
        $this->save();
    }
}
