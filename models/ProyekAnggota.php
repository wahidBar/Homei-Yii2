<?php

namespace app\models;

use Yii;
use \app\models\base\ProyekAnggota as BaseProyekAnggota;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_proyek_anggota".
 * Modified by Defri Indra M
 */
class ProyekAnggota extends BaseProyekAnggota
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
