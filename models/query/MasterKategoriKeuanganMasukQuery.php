<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\MasterKategoriKeuanganMasuk]].
 *
 * @see \app\models\MasterKategoriKeuanganMasuk
 */
class MasterKategoriKeuanganMasukQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\MasterKategoriKeuanganMasuk[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['t_master_kategori_keuangan_masuk.flag' => 1]);
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\MasterKategoriKeuanganMasuk|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['t_master_kategori_keuangan_masuk.flag' => 1]);
        return parent::one($db);
    }
}
