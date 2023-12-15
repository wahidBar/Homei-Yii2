<?php

namespace app\models;

use Yii;
use \app\models\base\ProyekKemajuanTarget as BaseProyekKemajuanTarget;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_proyek_kemajuan_target".
 * Modified by Defri Indra M
 */
class ProyekKemajuanTarget extends BaseProyekKemajuanTarget
{
    public static function showAtIndex()
    {
        $query = static::find()
            ->where(['deleted_by' => null])
            ->orderBy(['id' => SORT_DESC])
            ->select([
                'id',
                // 'id_proyek',
                'kode_proyek',
                'nama_target',
                'nilai_target',
                'jumlah_target',
                'tanggal_awal',
                'tanggal_akhir',
                'created_at',
                // 'updated_at',
                // 'deleted_at',
                // 'created_by',
                // 'updated_by',
                // 'deleted_by',
            ]);

        return $query;
    }
}
