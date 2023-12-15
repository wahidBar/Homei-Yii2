<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\ProyekKeuanganKeluar]].
 *
 * @see \app\models\ProyekKeuanganKeluar
 */
class ProyekKeuanganKeluarQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\ProyekKeuanganKeluar[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['t_proyek_keuangan_keluar.flag' => 1]);
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\ProyekKeuanganKeluar|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['t_proyek_keuangan_keluar.flag' => 1]);
        return parent::one($db);
    }
}
