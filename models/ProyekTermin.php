<?php

namespace app\models;

use Yii;
use \app\models\base\ProyekTermin as BaseProyekTermin;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_proyek_termin".
 * Modified by Defri Indra M
 */
class ProyekTermin extends BaseProyekTermin
{
    const STATUS_BELUM_BAYAR = 0;
    const STATUS_SUDAH_BAYAR = 1;
    const STATUS_PEMBAYARAN_DITERIMA = 2;
    const STATUS_PEMBAYARAN_DITOLAK = 3;

    public static function getStatuses()
    {
        return [
            "Belum Bayar",
            "Sudah Membayar",
            "Pembayaran Dikonfirmasi",
            "Pembayaran Ditolak",
        ];
    }

    public static function showAtIndex($id_project)
    {
        $query = static::find()
            ->where(['proyek_id' => $id_project])
            ->andWhere(['flag' => 1])
            ->orderBy(['id' => SORT_DESC])
            ->select([
                'id',
                'kode_unik',
                // 'kode_proyek',
                // 'proyek_id',
                'id_proyek',
                // 'user_id',
                'termin',
                'penyelesaian_pekerjaan',
                'nilai_pembayaran',
                'jadwal_pembayaran',
                // 'keterangan_pembayaran',
                // 'tanggal_pembayaran',
                // 'alasan_tolak_pembayaran',
                'status',
                'created_at',
                // 'updated_at',
                // 'created_by',
                // 'updated_by',
                // 'flag'
            ]);

        return $query;
    }
}
