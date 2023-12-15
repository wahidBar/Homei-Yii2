<?php

namespace app\models;

use Yii;
use \app\models\base\SmarthomeMasterProduk as BaseSmarthomeMasterProduk;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_smarthome_master_produk".
 * Modified by Defri Indra M
 */
class SmarthomeMasterProduk extends BaseSmarthomeMasterProduk
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

    public static function findByActiveDeviceID($device_id)
    {
        $product = SmarthomeMasterProduk::find()->where(['kode_produk' => $device_id])->active()->one();
        if ($product) {
            return $product;
        }
        return null;
    }

    public function generatePairingCode()
    {
        SmarthomeMasterProdukPair::generatePairFrom($this->id);
    }

    public function deletePairingCode()
    {
        SmarthomeMasterProdukPair::deletePairingFrom($this->id);
    }

    public function getHasActivePairingCode()
    {
        return SmarthomeMasterProdukPair::find()->where(['id_master_produk' => $this->id, 'flag' => 1])->exists();
    }


    public function getActivePairingCode($withmodel = false, $nullable = false)
    {
        $model = SmarthomeMasterProdukPair::find()->where(['id_master_produk' => $this->id, 'flag' => 1])->one();
        if ($model) {
            if ($withmodel)
                return $model;
            else
                return $model->kode_pairing;
        }
        if ($nullable)
            return null;
        return "-";
    }

    public function getHasNotUsedPairingCode()
    {
        return SmarthomeMasterProdukPair::find()->where(['id_master_produk' => $this->id, 'flag' => 0])->exists();
    }

    public function getNotUsedPairingCode($withmodel = false, $nullable = false)
    {
        self::updateNotUsedPairingCode(); // handle if expired_at is expired
        $model = SmarthomeMasterProdukPair::find()->where(['id_master_produk' => $this->id, 'flag' => 0])->one();
        if ($model) {
            if ($withmodel)
                return $model;
            else
                return $model->kode_pairing;
        }

        if ($nullable)
            return null;
        return "-";
    }

    public function getKodePairing()
    {
        $kode_pairing = "-";

        if ($this->hasActivePairingCode) {
            $kode_pairing = $this->activePairingCode;
        } else if ($this->hasNotUsedPairingCode) {
            $kode_pairing = $this->notUsedPairingCode;
        }

        return $kode_pairing;
    }

    public function updateNotUsedPairingCode()
    {
        if ($this->hasNotUsedPairingCode) {
            $model = SmarthomeMasterProdukPair::find()->where(['id_master_produk' => $this->id, 'flag' => 0])->one();
            // check if expired_at is not expired
            if (strtotime($model->expired_at) < time()) {
                // re generate random pairing code
                $model->updatePairingCode();
            }
        }
    }

    public function checkPairingCode()
    {
        try {
            if ($this->hasActivePairingCode) {
                return;
            } else if ($this->hasNotUsedPairingCode === false) {
                $this->generatePairingCode();
            } else {
                $this->updateNotUsedPairingCode();
            }
        } catch (\Throwable $th) {
            // log error
            Yii::error($th->getMessage());
        }
        return;
    }

    public function activateProduct()
    {

        $pairs = $this->getNotUsedPairingCode(true, true);
        $pairs->activatePairingCode();

        $this->digunakan = 1;
        return $this->save();
    }

    public function nonActivateProduct()
    {
        $this->deletePairingCode();

        $this->digunakan = 0;
        return $this->save();
    }

    public function resetProduct()
    {
        // change active sirkuit to non active
        $sirkuit = SmarthomeSirkuit::find()->where(['id_produk' => $this->id, 'flag' => 1])->active()->one();
        if ($sirkuit) {
            $sirkuit->nonActivateSirkuit();
        }

        $this->deletePairingCode();
        $this->generatePairingCode();

        $this->digunakan = 0;
        $this->reset     = 1;
        return $this->save();
    }
}
