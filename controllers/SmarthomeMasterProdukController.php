<?php

namespace app\controllers;

use app\models\SmarthomeMasterProduk;
use app\models\SmarthomeMasterProdukPair;

/**
 * This is the class for controller "SmarthomeMasterProdukController".
 * Modified by Defri Indra
 */
class SmarthomeMasterProdukController extends \app\controllers\base\SmarthomeMasterProdukController
{
    public function actionCronjob()
    {
        // response json
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // change kode_pairing if expired
        $active_product = SmarthomeMasterProdukPair::find()->where(['flag' => 1])->select('id_master_produk'); // produk sedang digunakan
        $list_product = SmarthomeMasterProduk::find()->where(['not in', 'id', $active_product])->all(); // produk yang tidak sedang digunakan

        foreach ($list_product as $product) $product->checkPairingCode();

        // return
        return [
            'success' => true,
            'message' => 'cronjob executed',
        ];
    }
}
