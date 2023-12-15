<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "SmarthomeMasterProdukController".
 * Modified by Defri Indra
 */

use app\models\Smarthome;
use app\models\SmarthomeMasterProduk;
use app\models\SmarthomeSirkuit;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

use function igorw\retry;

class SmarthomeMasterProdukController extends \app\controllers\api\BaseController
{
    public $modelClass = 'app\models\SmarthomeMasterProduk';

    public function actionGetPairingCode()
    {
        // get pairing code from kode_produk
        $kode_produk = \Yii::$app->request->get('device_id');
        $model = \app\models\SmarthomeMasterProduk::find()->where(['kode_produk' => $kode_produk])->one();

        if ($model) {

            if ($model->hasActivePairingCode) {
                return [[
                    'success' => false,
                    'message' => 'Kode produk sudah terdaftar',
                ]];
            }

            $model->generatePairingCode();
            $pairs = $model->getNotUsedPairingCode(true);

            return [[
                'success' => true,
                'message' => 'Menampilkan kode pairing',
                'kode_pairing' => $pairs->kode_pairing,
                'expired_at' => $pairs->expired_at,
            ]];
        } else {
            return [[
                'success' => false,
                'message' => 'Kode produk tidak ditemukan',
            ]];
        }
    }

    public function actionCheck()
    {
        // get pairing code from kode_produk
        $kode_produk = \Yii::$app->request->get('device_id');
        $model = \app\models\SmarthomeMasterProduk::find()->where(['kode_produk' => $kode_produk])->one();

        if ($model->hasActivePairingCode) {
            return [[
                'success' => true,
                'message' => 'Kode produk sudah terdaftar',
            ]];
        } else {
            return [[
                'success' => false,
                'message' => 'Kode produk belum terdaftar',
            ]];
        }
    }


    public function actionCheckReset($device_id)
    {
        // $smarthome = Smarthome::find()->where(['token' => $token])->one();
        $product = SmarthomeMasterProduk::find()->where(['kode_produk' => $device_id])->active()->one();
        if (!$product) {
            return [[
                "success" => false,
                "message" => "Device ID tidak ditemukan"
            ]];
        }

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if ($product->reset) {
                $response = [[
                    "success" => true,
                    "status" => 1
                ]];
                $product->reset = 0;
                $product->save();
            } else {
                $response = [[
                    "success" => true,
                    "status" => 0
                ]];
            }
            $transaction->commit();
            return $response;
        } catch (\Throwable $th) {
            $transaction->rollBack();
            return [[
                "success" => false,
                "message" => "Gagal reset device"
            ]];
        }
    }


    public function actionReset($device_id)
    {
        // $smarthome = Smarthome::find()->where(['token' => $token])->one();
        $product = SmarthomeMasterProduk::findByActiveDeviceID($device_id);
        if (!$product) {
            return [[
                "success" => false,
                "message" => "Device ID tidak ditemukan"
            ]];
        }

        if ($product->getHasActivePairingCode() == false) {
            return [[
                "success" => false,
                "message" => "Device ID tidak terdaftar"
            ]];
        }


        $sirkuit = SmarthomeSirkuit::find()->where(['id_produk' => $product->id])->active()->one();
        if (!$sirkuit) {
            return [[
                "success" => false,
                "message" => "Sirkuit tidak ditemukan"
            ]];
        }

        $smarthome = Smarthome::find()->where(['id' => $sirkuit->id_smarthome])->active()->one();

        if (!$smarthome) {
            return [[
                "success" => false,
                "message" => "Smarthome tidak ditemukan"
            ]];
        }

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $product->resetProduct();
            $transaction->commit();
            return [[
                "success" => true,
                "message" => "Berhasil reset device",
                "device_id" => $device_id,
                "timestamp" => time(),
            ]];
        } catch (\Throwable $th) {
            $transaction->rollBack();
            return [[
                "success" => false,
                "message" => "Gagal reset device"
            ]];
        }
    }
}
