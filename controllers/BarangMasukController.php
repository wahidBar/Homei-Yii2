<?php

namespace app\controllers;

use app\models\BarangMasuk;

/**
 * This is the class for controller "BarangMasukController".
 * Modified by Defri Indra
 */
class BarangMasukController extends \app\controllers\base\BarangMasukController
{
    public function actionExport()
    {
        $start = $_GET['start'];
        $end = $_GET['end'];
        if ($start != null && $end != null) {
            $models = BarangMasuk::find()
                ->andWhere(['between', 'date(created_at)', $start, $end])
                ->all();
            $export = new \app\components\exports\BarangMasukExport($models);
            return $export->download('Report Barang Masuk - ' . $start . ' - ' . $end);
        } else {
            $models = BarangMasuk::find()->all();
            $export = new \app\components\exports\BarangMasukExport($models);
            return $export->download('Report Barang Masuk - ' . date("d F Y"));
        }
    }
}
