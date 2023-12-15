<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\Proyek]].
 *
 * @see \app\models\Proyek
 */
class ProyekQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\Proyek[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['t_proyek.flag' => 1]);
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Proyek|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['t_proyek.flag' => 1]);
        return parent::one($db);
    }
}
