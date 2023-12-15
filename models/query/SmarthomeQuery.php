<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\Smarthome]].
 *
 * @see \app\models\Smarthome
 */
class SmarthomeQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere(['t_smarthome.flag' => 1]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return \app\models\Smarthome[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Smarthome|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
