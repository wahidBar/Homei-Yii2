<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\SupplierBoqProyek]].
 *
 * @see \app\models\SupplierBoqProyek
 */
class SupplierBoqProyekQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\SupplierBoqProyek[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\SupplierBoqProyek|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
