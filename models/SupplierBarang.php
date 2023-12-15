<?php

namespace app\models;

use Yii;
use \app\models\base\SupplierBarang as BaseSupplierBarang;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_supplier_barang".
 * Modified by Defri Indra M
 */
class SupplierBarang extends BaseSupplierBarang
{
    const DEFAULT_FILE_VALIDATION = [
        'MAX_SIZE' => 1024 * 1024 * 5,
        'ALLOWED_EXTENSION' => ['jpg', 'png', 'jpeg', 'gif', 'bmp'],
        'ALLOWED_MIMES' => 'image/*',
    ];

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

    function getStatuses()
    {
        return [
            "Non Aktif",
            "Aktif"
        ];
    }

    function getJumlahPervolume()
    {
        $volume = 1;
        $panjang = $this->panjang / 100; // convert to m
        $lebar = $this->lebar / 100; // convert to m
        $tebal = $this->tebal / 100; // convert to m

        if ($panjang == null || $lebar == null || $tebal == null) {
            return -1;
        }

        $hitung_pcs = $volume / ($panjang * $lebar * $tebal);
        return $hitung_pcs;
    }

    function getHargaPermeter($type = "harga_ritel")
    {
        $volume = 1;
        $harga = $this->$type;
        $panjang = $this->panjang / 100; // convert to m
        $lebar = $this->lebar / 100; // convert to m
        $tebal = $this->tebal / 100; // convert to m

        if ($panjang == null || $lebar == null || $tebal == null) {
            return -1;
        }

        $hitung_pcs = $volume / ($panjang * $lebar * $tebal);
        $harga_perbiji = $harga / $hitung_pcs;
        $hitung_meter_kuadrat = $volume / ($panjang * $lebar);
        $hasil_akhir = $hitung_meter_kuadrat * $harga_perbiji;

        return $hasil_akhir; // harga per meternya
    }

    function getHargaPerbiji($type = "harga_ritel")
    {
        $volume = 1;
        $panjang = $this->panjang / 100; // convert to m
        $lebar = $this->lebar / 100; // convert to m
        $tebal = $this->tebal / 100; // convert to m
        $harga = $this->$type;

        if ($panjang == null || $lebar == null || $tebal == null) {
            return -1;
        }

        $hitung_pcs = $volume / ($panjang * $lebar * $tebal);
        $harga_perbiji = $harga / $hitung_pcs;

        return $harga_perbiji;
    }

    function getJumlahPervolumeProyek()
    {
        $volume = 1;
        $panjang = $this->panjang / 100; // convert to m
        $lebar = $this->lebar / 100; // convert to m
        $tebal = $this->tebal / 100; // convert to m

        if ($panjang == null || $lebar == null || $tebal == null) {
            return -1;
        }

        $hitung_pcs = $volume / ($panjang * $lebar * $tebal);
        return $hitung_pcs;
    }

    function getHargaPermeterProyek($type = "harga_proyek")
    {
        $volume = 1;
        $harga = $this->$type;
        $panjang = $this->panjang / 100; // convert to m
        $lebar = $this->lebar / 100; // convert to m
        $tebal = $this->tebal / 100; // convert to m

        if ($panjang == null || $lebar == null || $tebal == null) {
            return -1;
        }

        $hitung_pcs = $volume / ($panjang * $lebar * $tebal);
        $harga_perbiji = $harga / $hitung_pcs;
        $hitung_meter_kuadrat = $volume / ($panjang * $lebar);
        $hasil_akhir = $hitung_meter_kuadrat * $harga_perbiji;

        return $hasil_akhir; // harga per meternya
    }

    function getHargaPerbijiProyek($type = "harga_proyek")
    {
        $volume = 1;
        $panjang = $this->panjang / 100; // convert to m
        $lebar = $this->lebar / 100; // convert to m
        $tebal = $this->tebal / 100; // convert to m
        $harga = $this->$type;

        if($tebal == 0)
        {
            $tebal = 1;
        }

        if ($panjang == null || $lebar == null || $tebal == null) {
            return -1;
        }

        $hitung_pcs = $volume / ($panjang * $lebar * $tebal);
        $harga_perbiji = $harga / $hitung_pcs;

        return $harga_perbiji;
    }
}
