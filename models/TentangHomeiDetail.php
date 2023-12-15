<?php

namespace app\models;

use Yii;
use \app\models\base\TentangHomeiDetail as BaseTentangHomeiDetail;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tentang_homei_detail".
 * Modified by Defri Indra M
 */
class TentangHomeiDetail extends BaseTentangHomeiDetail
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
