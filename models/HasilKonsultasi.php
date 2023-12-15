<?php

namespace app\models;

use Yii;
use \app\models\base\HasilKonsultasi as BaseHasilKonsultasi;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_hasil_konsultasi".
 * Modified by Defri Indra M
 */
class HasilKonsultasi extends BaseHasilKonsultasi
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
