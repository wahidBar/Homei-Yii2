<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "SmarthomeController".
 * Modified by Defri Indra
 */

use app\models\Smarthome;
use app\models\SmarthomeKontrol;
use app\models\SmarthomeLog;
use app\models\SmarthomeMasterProduk;
use app\models\SmarthomeSirkuit;
use Yii;

class SmarthomeController extends \app\controllers\api\BaseController
{
    public $modelClass = 'app\models\Smarthome';

    public function actionDataKontrol($device_id)
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

        if ($smarthome) {
            $data = SmarthomeKontrol::find()->where(['id_smarthome' => $smarthome->id, 'id_sirkuit' => $sirkuit->id])->active()->asArray()->select(['nama as Nama', 'pin as Pin', 'value as Value'])->all();
            return $data;
        } else {
            return [];
        }
    }


    public function actionSyncData($device_id)
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

        if ($smarthome) {

            // if kontrol is not exist, forbidden
            $controller_count = SmarthomeKontrol::find()->where(['id_smarthome' => $smarthome->id, 'id_sirkuit' => $sirkuit->id])->active()->count();
            if ($controller_count == 0) {
                return [[
                    "success" => false,
                    "message" => "Kontrol tidak ditemukan"
                ]];
            }

            $old_daya = $smarthome->daya;
            $old_ampere = $smarthome->ampere;

            $smarthome->kelembapan = Yii::$app->request->get('kelembapan');
            $smarthome->suhu = Yii::$app->request->get('suhu');
            $smarthome->tegangan = Yii::$app->request->get('tegangan');
            $smarthome->daya = Yii::$app->request->get('daya');
            $smarthome->ampere = Yii::$app->request->get('ampere');

            SmarthomeLog::record($smarthome, $sirkuit->id);
            $smarthome->daya_sebelumnya = $old_daya;
            $smarthome->ampere_sebelumnya = $old_ampere;
            $smarthome->daya = $smarthome->totalDayaTerakhir();
            $smarthome->ampere = $smarthome->totalAmpereTerakhir();
            $smarthome->save();

            return [[
                "success" => true,
                "message" => "Data berhasil diubah"
            ]];
        } else {
            return [[
                "success" => false,
                "message" => "Token tidak ditemukan"
            ]];
        }
    }
}
