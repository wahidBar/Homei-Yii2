<?php

namespace app\models;

use Yii;
use \app\models\base\ProyekGaleri as BaseProyekGaleri;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_proyek_galeri".
 * Modified by Defri Indra M
 */
class ProyekGaleri extends BaseProyekGaleri
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

    function getUploadedPath()
    {
        return "proyek_galeri/" . $this->id_proyek . "/";
    }
}
