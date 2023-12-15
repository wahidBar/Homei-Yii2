<?php

namespace app\models;

use Yii;
use \app\models\base\SupplierMaterial as BaseSupplierMaterial;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_supplier_material".
 * Modified by Defri Indra M
 */
class SupplierMaterial extends BaseSupplierMaterial
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

    function rawRumus()
    {
        if (is_array($this->rumus)) $this->rumus = json_encode($this->rumus);
        if ($this->rumus == false) return "(rumus belum diatur)";
        return implode(" ", json_decode($this->rumus));
    }
}
