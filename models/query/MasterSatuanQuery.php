<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\MasterSatuan]].
 *
 * @see \app\models\MasterSatuan
 */
class MasterSatuanQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\models\MasterSatuan[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['t_master_satuan.flag' => 1]);
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\MasterSatuan|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['t_master_satuan.flag' => 1]);
        return parent::one($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\MasterSatuan|array|null
     */
    public function column($db = null)
    {
        $this->andWhere(['t_master_satuan.flag' => 1]);
        return parent::column($db);
    }

    public function count($q = '*', $db = null)
    {
        $this->andWhere(['t_master_satuan.flag' => 1]);
        return parent::count($q, $db);
    }
}
