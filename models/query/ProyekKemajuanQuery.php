<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\ProyekKemajuan]].
 *
 * @see \app\models\ProyekKemajuan
 */
class ProyekKemajuanQuery extends \yii\db\ActiveQuery
{

    public function all($db = null)
    {
        $this->andWhere(['t_proyek_kemajuan.flag' => 1]);
        $this->andWhere(['is', 't_proyek_kemajuan.deleted_at', null]);
        return parent::all($db);
    }

    public function one($db = null)
    {
        $this->andWhere(['t_proyek_kemajuan.flag' => 1]);
        $this->andWhere(['is', 't_proyek_kemajuan.deleted_at', null]);
        return parent::one($db);
    }

    public function count($q = '*', $db = null)
    {
        $this->andWhere(['t_proyek_kemajuan.flag' => 1]);
        $this->andWhere(['is', 't_proyek_kemajuan.deleted_at', null]);
        return parent::count($q, $db);
    }
    public function sum($q, $db = null)
    {
        $this->andWhere(['t_proyek_kemajuan.flag' => 1]);
        $this->andWhere(['is', 't_proyek_kemajuan.deleted_at', null]);
        return parent::sum($q, $db);
    }
    public function average($q, $db = null)
    {
        $this->andWhere(['t_proyek_kemajuan.flag' => 1]);
        $this->andWhere(['is', 't_proyek_kemajuan.deleted_at', null]);
        return parent::average($q, $db);
    }

    public function min($q, $db = null)
    {
        $this->andWhere(['t_proyek_kemajuan.flag' => 1]);
        $this->andWhere(['is', 't_proyek_kemajuan.deleted_at', null]);
        return parent::min($q, $db);
    }

    public function max($q, $db = null)
    {
        $this->andWhere(['t_proyek_kemajuan.flag' => 1]);
        $this->andWhere(['is', 't_proyek_kemajuan.deleted_at', null]);
        return parent::max($q, $db);
    }

    public function scalar($db = null)
    {
        $this->andWhere(['t_proyek_kemajuan.flag' => 1]);
        $this->andWhere(['is', 't_proyek_kemajuan.deleted_at', null]);
        return parent::scalar($db);
    }

    public function column($db = null)
    {
        $this->andWhere(['t_proyek_kemajuan.flag' => 1]);
        $this->andWhere(['is', 't_proyek_kemajuan.deleted_at', null]);
        return parent::column($db);
    }

    public function exists($db = null)
    {
        $this->andWhere(['t_proyek_kemajuan.flag' => 1]);
        $this->andWhere(['is', 't_proyek_kemajuan.deleted_at', null]);
        return parent::exists($db);
    }
}
