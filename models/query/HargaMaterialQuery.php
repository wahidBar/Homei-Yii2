<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\HargaMaterial]].
 *
 * @see \app\models\HargaMaterial
 */
class HargaMaterialQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\models\HargaMaterial[]|array
     */
    public function all($db = null)
    {
        // $this->andWhere(['t_harga_material.flag' => 1]);
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\HargaMaterial|array|null
     */
    public function one($db = null)
    {
        // $this->andWhere(['t_harga_material.flag' => 1]);
        return parent::one($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\HargaMaterial|array|null
     */
    public function column($db = null)
    {
        // $this->andWhere(['t_harga_material.flag' => 1]);
        return parent::column($db);
    }

    public function count($q = '*', $db = null)
    {
        // $this->andWhere(['t_harga_material.flag' => 1]);
        return parent::count($q, $db);
    }
}
