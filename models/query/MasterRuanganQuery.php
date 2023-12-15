<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\MasterRuangan]].
 *
 * @see \app\models\MasterRuangan
 */
class MasterRuanganQuery extends \yii\db\ActiveQuery
{
    public function all($db = null)
    {
        $this->andWhere(['t_master_ruangan.flag' => 1]);
        return parent::all($db);
    }

    public function one($db = null)
    {
        $this->andWhere(['t_master_ruangan.flag' => 1]);
        return parent::one($db);
    }

    public function count($q = '*', $db = null)
    {
        $this->andWhere(['t_master_ruangan.flag' => 1]);
        return parent::count($q, $db);
    }
    public function sum($q, $db = null)
    {
        $this->andWhere(['t_master_ruangan.flag' => 1]);
        return parent::sum($q, $db);
    }
    public function average($q, $db = null)
    {
        $this->andWhere(['t_master_ruangan.flag' => 1]);
        return parent::average($q, $db);
    }

    public function min($q, $db = null)
    {
        $this->andWhere(['t_master_ruangan.flag' => 1]);
        return parent::min($q, $db);
    }

    public function max($q, $db = null)
    {
        $this->andWhere(['t_master_ruangan.flag' => 1]);
        return parent::max($q, $db);
    }

    public function scalar($db = null)
    {
        $this->andWhere(['t_master_ruangan.flag' => 1]);
        return parent::scalar($db);
    }

    public function column($db = null)
    {
        $this->andWhere(['t_master_ruangan.flag' => 1]);
        return parent::column($db);
    }

    public function exists($db = null)
    {
        $this->andWhere(['t_master_ruangan.flag' => 1]);
        return parent::exists($db);
    }
}
