<?php

namespace app\models;

use \app\models\base\ProyekCctv as BaseProyekCctv;

/**
 * This is the model class for table "t_proyek_cctv".
 * Modified by Defri Indra M
 */
class ProyekCctv extends BaseProyekCctv
{
    const INTERNAL_LINK = 0;
    const EKSTERNAL_LINK = 1;

    public static function showAtIndex($id_project)
    {
        $query = static::find()
            ->where(['id_proyek' => $id_project])
            ->andWhere(['flag' => 1])
            ->orderBy(['id' => SORT_DESC])
            ->select(['id', 'lokasi', 'link']);

        return $query;
    }

    public static function getTipes()
    {
        return [
            static::INTERNAL_LINK => 'Internal Link',
            static::EKSTERNAL_LINK => 'Eksternal Link',
        ];
    }

    public function getTipeLabel()
    {
        return static::getTipes()[$this->tipe];
    }
}
