<?php

namespace app\models;

use Yii;
use \app\models\base\TentangHomei as BaseTentangHomei;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tentang_homei".
 * Modified by Defri Indra M
 */
class TentangHomei extends BaseTentangHomei
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
