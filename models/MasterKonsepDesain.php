<?php

namespace app\models;

use Yii;
use \app\models\base\MasterKonsepDesain as BaseMasterKonsepDesain;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_master_konsep_desain".
 * Modified by Defri Indra M
 */
class MasterKonsepDesain extends BaseMasterKonsepDesain
{

    const DEFAULT_FILE_VALIDATION = [
        'MAX_SIZE' => 1024 * 1024 * 5,
        'ALLOWED_EXTENSION' => ['jpg', 'png', 'jpeg', 'gif', 'bmp'],
        'ALLOWED_MIMES' => 'image/*',
    ];

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
