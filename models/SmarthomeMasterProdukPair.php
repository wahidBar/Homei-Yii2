<?php

namespace app\models;

use Yii;
use \app\models\base\SmarthomeMasterProdukPair as BaseSmarthomeMasterProdukPair;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_smarthome_master_produk_pair".
 * Modified by Defri Indra M
 */
class SmarthomeMasterProdukPair extends BaseSmarthomeMasterProdukPair
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

    public static function generateRandomKey()
    {
        // generate random 6 pairing code number
        $random = "";
        for ($i = 0; $i < 6; $i++) {
            $random .= random_int(0, 9);
        }
        return $random;
    }

    public static function generatePairFrom(int $id)
    {
        $model = new SmarthomeMasterProdukPair();
        $model->id_master_produk = $id;
        // generate random pairing code
        $model->kode_pairing = self::generateRandomKey();
        // created_at & expired_at
        $model->created_at = date('Y-m-d H:i:s');
        $model->expired_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        return $model->save();
    }

    public function updatePairingCode()
    {
        // generate random pairing code
        $this->kode_pairing = self::generateRandomKey();
        $this->expired_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        return $this->save();
    }

    public static function deletePairingFrom(int $id)
    {
        // bulk update
        SmarthomeMasterProdukPair::updateAll(['flag' => -1], ['id_master_produk' => $id]);
        self::generatePairFrom($id);
    }

    public function activatePairingCode()
    {
        $this->flag = 1;
        return $this->save();
    }
}
