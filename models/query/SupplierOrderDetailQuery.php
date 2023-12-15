<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\SupplierOrderDetail]].
 *
 * @see \app\models\SupplierOrderDetail
 */
class SupplierOrderDetailQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\SupplierOrderDetail[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['t_supplier_order_detail.flag' => 1]);
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\SupplierOrderDetail|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['t_supplier_order_detail.flag' => 1]);
        return parent::one($db);
    }
}
