<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\SupplierOrderCart]].
 *
 * @see \app\models\SupplierOrderCart
 */
class SupplierOrderCartQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\SupplierOrderCart[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['t_supplier_order_cart.flag' => 1]);
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\SupplierOrderCart|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['t_supplier_order_cart.flag' => 1]);
        return parent::one($db);
    }
}
