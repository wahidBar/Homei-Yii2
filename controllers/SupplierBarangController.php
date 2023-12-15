<?php

namespace app\controllers;

use app\components\annex\ActiveForm;
use app\components\Constant;
use app\models\SupplierBarang;
use app\models\SupplierMaterial;

/**
 * This is the class for controller "SupplierBarangController".
 * Modified by Defri Indra
 */
class SupplierBarangController extends \app\controllers\base\SupplierBarangController
{
    function getParameter($id)
    {
        $model = SupplierMaterial::findOne(['id' => $id]);
        if ($model == null) {
            return [];
        }

        if ($model->rumus == "") return "";
        $params = json_decode($model->rumus);
        $params = array_diff($params, Constant::calculatorAllowedSymbol()); // remove symbol
        $params = array_unique($params);
        return $params;
    }
    function actionGetParameter($id, $barang_id = null)
    {
        $params = $this->getParameter($id);

        $modelBarang = SupplierBarang::findOne(['id' => $barang_id]);
        if ($modelBarang == null) $modelBarang = new SupplierBarang();

        $form = ActiveForm::begin([
            'id' => 'SupplierBarang',
            'layout' => 'horizontal',
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]);
        return $this->renderPartial('_form_param', ['params' => $params, 'model' => $modelBarang, 'form' => $form]);
    }
}
