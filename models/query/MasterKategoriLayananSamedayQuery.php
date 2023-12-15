<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\MasterKategoriLayananSameday]].
 *
 * @see \app\models\MasterKategoriLayananSameday
 */
class MasterKategoriLayananSamedayQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\MasterKategoriLayananSameday[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\MasterKategoriLayananSameday|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
