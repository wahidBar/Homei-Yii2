<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\IsianLanjutanRuangan]].
 *
 * @see \app\models\IsianLanjutanRuangan
 */
class IsianLanjuanRuanganQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\IsianLanjutanRuangan[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\IsianLanjutanRuangan|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
