<?php

namespace app\models;

use Yii;
use \app\models\base\Penawaran as BasePenawaran;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_penawaran".
 * Modified by Defri Indra M
 */
class Penawaran extends BasePenawaran
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
