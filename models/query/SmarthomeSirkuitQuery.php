<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\SmarthomeSirkuit]].
 *
 * @see \app\models\SmarthomeSirkuit
 */
class SmarthomeSirkuitQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere(['t_smarthome_sirkuit.flag' => 1]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return \app\models\SmarthomeSirkuit[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\SmarthomeSirkuit|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
