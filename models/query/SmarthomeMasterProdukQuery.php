<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\SmarthomeMasterProduk]].
 *
 * @see \app\models\SmarthomeMasterProduk
 */
class SmarthomeMasterProdukQuery extends \yii\db\ActiveQuery
{

    public function active()
    {
        $this->andWhere(['t_smarthome_master_produk.flag' => 1]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return \app\models\SmarthomeMasterProduk[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\SmarthomeMasterProduk|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
