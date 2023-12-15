<?php

namespace app\models;

use Yii;
use \app\models\base\MasterKategoriKeuanganMasuk as BaseMasterKategoriKeuanganMasuk;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_master_kategori_keuangan_masuk".
 * Modified by Defri Indra M
 */
class MasterKategoriKeuanganMasuk extends BaseMasterKategoriKeuanganMasuk
{
    public static function showAtIndex($id_proyek)
    {
        $query = static::find()
        ->where(['id_proyek' => $id_proyek])
        ->andWhere(['flag' => 1])
        ->orderBy(['nama_kategori' => SORT_ASC])
        ->select(['id', 'nama_kategori']);
        
        $_search = Yii::$app->request->get('_search');
        if ($_search) {
            $query->andFilterWhere(['like', 'nama_kategori', $_search]);
        }

        return $query;
    }
}
