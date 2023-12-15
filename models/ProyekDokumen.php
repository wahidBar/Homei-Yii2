<?php

namespace app\models;

use Yii;
use \app\models\base\ProyekDokumen as BaseProyekDokumen;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_proyek_dokumen".
 * Modified by Defri Indra M
 */
class ProyekDokumen extends BaseProyekDokumen
{
    const TYPE_DOCUMENTS = [
        "Desain",
        "Dokumen"
    ];

    public static function showAtIndex($id_proyek)
    {
        $query = ProyekDokumen::find()
            ->where(['id_proyek' => $id_proyek])
            ->andWhere(['deleted_at' => null, 'flag' => 1])
            ->orderBy(['id' => SORT_DESC])
            ->select(['id', 'nama_file', 'type', 'pathfile', 'created_at']);
        return $query;
    }
}
