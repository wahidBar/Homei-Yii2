<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\ProyekCctv]].
 *
 * @see \app\models\ProyekCctv
 */
class ProyekCctvQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\ProyekCctv[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['t_proyek_cctv.flag' => 1]);
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\ProyekCctv|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['t_proyek_cctv.flag' => 1]);
        return parent::one($db);
    }
}
