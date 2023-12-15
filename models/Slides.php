<?php

namespace app\models;

use Yii;
use \app\models\base\Slides as BaseSlides;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "slides".
 * Modified by Defri Indra M
 */
class Slides extends BaseSlides
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

    public function getType()
    {
        if ($this->type == 0) {
            return "Tipe Web";
        } else if ($this->type == 1) {
            return "Tipe Android";
        }
        return "-";
    }

    public function getRedirectType()
    {
        if ($this->redirect_type == 0) {
            return "Component";
        } else if ($this->redirect_type == 1) {
            return "Link";
        }
        return "-";
    }
}
