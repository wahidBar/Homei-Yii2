<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\SupplierOrder]].
 *
 * @see \app\models\SupplierOrder
 */
class SupplierOrderQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\SupplierOrder[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['t_supplier_order.flag' => 1]);
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\SupplierOrder|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['t_supplier_order.flag' => 1]);
        return parent::one($db);
    }
}
