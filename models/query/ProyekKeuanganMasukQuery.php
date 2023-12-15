<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\ProyekKeuanganMasuk]].
 *
 * @see \app\models\ProyekKeuanganMasuk
 */
class ProyekKeuanganMasukQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere(['t_proyek_keuangan_masuk.flag' => 1]);
        $this->andWhere(['is', 't_proyek_keuangan_masuk.deleted_at', null]);
        return $this;
    }

    public function all($db = null)
    {
        $this->andWhere(['t_proyek_keuangan_masuk.flag' => 1]);
        $this->andWhere(['is', 't_proyek_keuangan_masuk.deleted_at', null]);
        return parent::all($db);
    }

    public function one($db = null)
    {
        $this->andWhere(['t_proyek_keuangan_masuk.flag' => 1]);
        $this->andWhere(['is', 't_proyek_keuangan_masuk.deleted_at', null]);
        return parent::one($db);
    }

    public function count($q = '*', $db = null)
    {
        $this->andWhere(['t_proyek_keuangan_masuk.flag' => 1]);
        $this->andWhere(['is', 't_proyek_keuangan_masuk.deleted_at', null]);
        return parent::count($q, $db);
    }
    public function sum($q, $db = null)
    {
        $this->andWhere(['t_proyek_keuangan_masuk.flag' => 1]);
        $this->andWhere(['is', 't_proyek_keuangan_masuk.deleted_at', null]);
        return parent::sum($q, $db);
    }
    public function average($q, $db = null)
    {
        $this->andWhere(['t_proyek_keuangan_masuk.flag' => 1]);
        $this->andWhere(['is', 't_proyek_keuangan_masuk.deleted_at', null]);
        return parent::average($q, $db);
    }

    public function min($q, $db = null)
    {
        $this->andWhere(['t_proyek_keuangan_masuk.flag' => 1]);
        $this->andWhere(['is', 't_proyek_keuangan_masuk.deleted_at', null]);
        return parent::min($q, $db);
    }

    public function max($q, $db = null)
    {
        $this->andWhere(['t_proyek_keuangan_masuk.flag' => 1]);
        $this->andWhere(['is', 't_proyek_keuangan_masuk.deleted_at', null]);
        return parent::max($q, $db);
    }

    public function scalar($db = null)
    {
        $this->andWhere(['t_proyek_keuangan_masuk.flag' => 1]);
        $this->andWhere(['is', 't_proyek_keuangan_masuk.deleted_at', null]);
        return parent::scalar($db);
    }

    public function column($db = null)
    {
        $this->andWhere(['t_proyek_keuangan_masuk.flag' => 1]);
        $this->andWhere(['is', 't_proyek_keuangan_masuk.deleted_at', null]);
        return parent::column($db);
    }

    public function exists($db = null)
    {
        $this->andWhere(['t_proyek_keuangan_masuk.flag' => 1]);
        $this->andWhere(['is', 't_proyek_keuangan_masuk.deleted_at', null]);
        return parent::exists($db);
    }
}
