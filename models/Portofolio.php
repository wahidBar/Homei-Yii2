<?php

namespace app\models;

use Yii;
use \app\models\base\Portofolio as BasePortofolio;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "portofolio".
 * Modified by Defri Indra M
 */
class Portofolio extends BasePortofolio
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
