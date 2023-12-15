<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\MasterJenisSatuan]].
 *
 * @see \app\models\MasterJenisSatuan
 */
class MasterJenisSatuanQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\models\MasterJenisSatuan[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['t_master_jenis_satuan.flag' => 1]);
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\MasterJenisSatuan|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['t_master_jenis_satuan.flag' => 1]);
        return parent::one($db);
    }
}
