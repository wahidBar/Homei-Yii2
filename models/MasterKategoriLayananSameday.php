<?php

namespace app\models;

use Yii;
use \app\models\base\MasterKategoriLayananSameday as BaseMasterKategoriLayananSameday;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_master_kategori_layanan_sameday".
 * Modified by Defri Indra M
 */
class MasterKategoriLayananSameday extends BaseMasterKategoriLayananSameday
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
