<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\ProyekKeuanganKeluarBayar]].
 *
 * @see \app\models\ProyekKeuanganKeluarBayar
 */
class ProyekKeuanganKeluarBayarQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\ProyekKeuanganKeluarBayar[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['t_proyek_keuangan_keluar_bayar.flag' => 1]);
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\ProyekKeuanganKeluarBayar|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['t_proyek_keuangan_keluar_bayar.flag' => 1]);
        return parent::one($db);
    }
}
