<?php

namespace app\models;

use Yii;
use \app\models\base\SupplierOrder as BaseSupplierOrder;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_supplier_order".
 * Modified by Defri Indra M
 */
class SupplierOrder extends BaseSupplierOrder
{

    // -1 => "Pembayaran Expired",
    // "Belum bayar",
    // "Menunggu Konfirmasi Admin",
    // "Pembayaran Lunas",
    // "Pembayaran Dibatalkan",
    // "Barang telah diterima customer",
    const STATUS_PEMBAYARAN_EXPIRED = -1;
    const STATUS_BELUM_BAYAR = 0;
    const STATUS_MENUNGGU_KONFIRMASI_ADMIN = 1;
    const STATUS_LUNAS = 2;
    const STATUS_PEMBAYARAN_DIBATALKAN = 3;
    const STATUS_SELESAI = 4;

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

    public static function getStatuses()
    {
        // 0:default;1:bayar;2:konfirmasi;3:tolak;4:batal;5:pengiriman;6:diterima;
        return [
            -1 => "Pembayaran Expired",
            "Belum bayar",
            "Menunggu Konfirmasi Admin",
            "Pembayaran Lunas",
            "Pembayaran Dibatalkan",
            "Barang telah diterima customer",
        ];
    }

    public function relativeUploadPath()
    {
        return "order/pembayaran/{$this->kode_unik}";
    }
}
