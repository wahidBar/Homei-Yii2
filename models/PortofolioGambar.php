<?php

namespace app\models;

use Yii;
use \app\models\base\PortofolioGambar as BasePortofolioGambar;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "portofolio_gambar".
 * Modified by Defri Indra M
 */
class PortofolioGambar extends BasePortofolioGambar
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
