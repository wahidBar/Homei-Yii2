<?php

namespace app\models;

use Yii;
use \app\models\base\ProyekKeuanganKeluar as BaseProyekKeuanganKeluar;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_proyek_keuangan_keluar".
 * Modified by Defri Indra M
 */
class ProyekKeuanganKeluar extends BaseProyekKeuanganKeluar
{
    const STATUS_BELUM_DIBAYAR = 0;
    const STATUS_DIBAYAR_SEBAGIAN = 1;
    const STATUS_LUNAS = 2;

    const TIPE_PO = 1;
    const TIPE_DEFAULT = 0;


    public static function showAtIndex($id_proyek, $is_po)
    {
        $query = static::find()
            ->where(['id_proyek' => $id_proyek])
            ->andWhere(['flag' => 1])
            ->orderBy(['id' => SORT_DESC]);

        if ($is_po) {
            $query->andWhere(['tipe' => static::TIPE_PO])
                ->select([
                    'id',
                    // 'id_proyek',
                    'id_kategori',
                    'no_po',
                    'dokumen_po',
                    'no_invoice',
                    // 'keterangan',
                    'tanggal',
                    // 'total_jumlah',
                    'vendor',
                    // 'tipe',
                    'status',
                    // 'created_at',
                    // 'created_by',
                    // 'updated_at',
                    // 'updated_by',
                    // 'deleted_at',
                    // 'deleted_by',
                ]);
        } else {
            $query->andWhere(['tipe' => static::TIPE_DEFAULT])
                ->select([
                    'id',
                    // 'id_proyek',
                    'id_kategori',
                    // 'no_po',
                    // 'dokumen_po',
                    'no_invoice',
                    // 'keterangan',
                    'tanggal',
                    // 'total_jumlah',
                    'vendor',
                    // 'tipe',
                    'status',
                    // 'created_at',
                    // 'created_by',
                    // 'updated_at',
                    // 'updated_by',
                    // 'deleted_at',
                    // 'deleted_by',
                ]);
        }

        $_search = Yii::$app->request->get('_search');
        if ($_search) {
            $query->andFilterWhere([
                "or",
                ['like', 'keterangan', $_search],
                ['like', 'no_invoice', $_search],
                ['like', 'vendor', $_search],
            ]);
        }
        return $query;
    }

    public static function getStatuses()
    {
        return [
            static::STATUS_BELUM_DIBAYAR => 'Belum Dibayar',
            static::STATUS_DIBAYAR_SEBAGIAN => 'Dibayar Sebagian',
            static::STATUS_LUNAS => 'Lunas',
        ];
    }

    public function getStatus()
    {
        return static::getStatuses()[$this->status];
    }

    public static function getTipes()
    {
        return [
            static::TIPE_DEFAULT => 'Pengeluaran',
            static::TIPE_PO => 'Purchase Order',
        ];
    }

    public function getTipe()
    {
        return static::getTipes()[$this->tipe];
    }
}
