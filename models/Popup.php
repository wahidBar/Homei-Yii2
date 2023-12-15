<?php

namespace app\models;

use Yii;
use \app\models\base\Popup as BasePopup;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_popup".
 * Modified by Defri Indra M
 */
class Popup extends BasePopup
{

    const DEFAULT_FILE_VALIDATION = [
        'MAX_SIZE' => 1024 * 1024 * 5,
        'ALLOWED_EXTENSION' => ['jpg', 'png', 'jpeg', 'gif', 'bmp'],
        'ALLOWED_MIMES' => 'image/*',
    ];

    const DROPDOWN_REDIRECT_TYPE = [
        '0' => 'Link',
        '1' => 'Component',
    ];

    const DROPDOWN_ANDROID_SHOW = [
        '0' => 'Tidak',
        '1' => 'Ya',
    ];

    const DROPDOWN_WEB_SHOW = [
        '0' => 'Tidak',
        '1' => 'Ya',
    ];

    public function getAndroidRedirectTypeLabel()
    {
        return self::DROPDOWN_REDIRECT_TYPE[$this->android_redirect_type];
    }

    public function getAndroidShowLabel()
    {
        return self::DROPDOWN_ANDROID_SHOW[$this->android_show];
    }

    public function getWebShowLabel()
    {
        return self::DROPDOWN_WEB_SHOW[$this->web_show];
    }

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
