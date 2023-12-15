<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[MasterPembayaran]].
 *
 * @see MasterPembayaran
 */
class MasterPembayaranQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return MasterPembayaran[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MasterPembayaran|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
