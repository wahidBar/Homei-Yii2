<?php

namespace app\models;

use Yii;
use \app\models\base\KonsultasiChat as BaseKonsultasiChat;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_konsultasi_chat".
 * Modified by Defri Indra M
 */
class KonsultasiChat extends BaseKonsultasiChat
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
