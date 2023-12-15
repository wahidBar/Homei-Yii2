<?php

namespace app\models;

use Yii;
use \app\models\base\SupplierOrderCart as BaseSupplierOrderCart;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_supplier_order_cart".
 * Modified by Defri Indra M
 */
class SupplierOrderCart extends BaseSupplierOrderCart
{
    const LAYANAN_RITEL = 0;
    const LAYANAN_PROYEK = 1;

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


    public function checkTombolSpk()
    {
        if ($this->valid_spk) return false;
        if (($this->no_spk && $this->keterangan_proyek) != false) return false;
        if ($this->jumlah < $this->supplierBarang->minimal_beli_satuan) return false;
        if ($this->supplierBarang->minimal_beli_satuan == 0) return false;
        return true;
    }

    public function labelTampilkanTombolSpk()
    {
        if ($this->checkTombolSpk() == false) return "d-none";
        return "";
    }
}
