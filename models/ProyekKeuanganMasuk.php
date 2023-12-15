<?php

namespace app\models;

use Yii;
use \app\models\base\ProyekKeuanganMasuk as BaseProyekKeuanganMasuk;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_proyek_keuangan_masuk".
 * Modified by Defri Indra M
 */
class ProyekKeuanganMasuk extends BaseProyekKeuanganMasuk
{
    public static function showAtIndex($id_proyek)
    {
        $query = static::find()
            ->where(['id_proyek' => $id_proyek])
            ->andWhere(['flag' => 1])
            ->orderBy(['id' => SORT_DESC])
            ->select(['id', 'item', 'jumlah', 'tanggal', 'keterangan']);
        $_search = Yii::$app->request->get('_search');
        if ($_search) {
            $query->andFilterWhere(['like', 'item', $_search]);
        }
        return $query;
    }
}
