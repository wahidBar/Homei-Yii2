<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\SmarthomeKontrol]].
 *
 * @see \app\models\SmarthomeKontrol
 */
class SmarthomeKontrolQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere(['t_smarthome_kontrol.flag' => 1]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return \app\models\SmarthomeKontrol[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\SmarthomeKontrol|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
