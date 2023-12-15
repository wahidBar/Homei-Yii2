<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\ProyekTermin]].
 *
 * @see \app\models\ProyekTermin
 */
class ProyekTerminQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\ProyekTermin[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['t_proyek_termin.flag' => 1]);
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\ProyekTermin|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['t_proyek_termin.flag' => 1]);
        return parent::one($db);
    }
}
