<?php

namespace app\controllers;

use app\models\Penawaran;
use Yii;

/**
 * This is the class for controller "PenawaranController".
 * Modified by Defri Indra
 */
class PenawaranController extends \app\controllers\base\PenawaranController
{
    public function actionDetail($id)
    {
        $model = Penawaran::findOne(['id' => $id]);
        if ($model == null) return "Tidak ditemukan";
        return $this->renderPartial('_view_detail_2', compact('model'));
    }

    public function actionGetHarga()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = Yii::$app->request->post('material');
        $model = \app\models\SupplierBarang::findOne(['id' => $id]);
        if ($model == null) return 0;

        return $model->harga_proyek;
    }
}
