<?php

namespace app\models;

use Yii;
use \app\models\base\KontraktorDetail as BaseKontraktorDetail;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_kontraktor_detail".
 * Modified by Defri Indra M
 */
class KontraktorDetail extends BaseKontraktorDetail
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
