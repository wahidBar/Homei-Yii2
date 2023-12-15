<?php

namespace app\models;

use Yii;
use \app\models\base\PenawaranDetail as BasePenawaranDetail;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_penawaran_detail".
 * Modified by Defri Indra M
 */
class PenawaranDetail extends BasePenawaranDetail
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
