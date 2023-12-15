<?php

namespace app\models;

use Yii;
use \app\models\base\ProyekKeuanganKeluarDetail as BaseProyekKeuanganKeluarDetail;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_proyek_keuangan_keluar_detail".
 * Modified by Defri Indra M
 */
class ProyekKeuanganKeluarDetail extends BaseProyekKeuanganKeluarDetail
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
