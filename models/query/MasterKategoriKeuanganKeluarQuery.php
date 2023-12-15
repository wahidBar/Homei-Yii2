<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\MasterKategoriKeuanganKeluar]].
 *
 * @see \app\models\MasterKategoriKeuanganKeluar
 */
class MasterKategoriKeuanganKeluarQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\MasterKategoriKeuanganKeluar[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\MasterKategoriKeuanganKeluar|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
