<?php

namespace app\models;

use Yii;
use \app\models\base\Galeri as BaseGaleri;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "galeri".
 * Modified by Defri Indra M
 */
class Galeri extends BaseGaleri
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
