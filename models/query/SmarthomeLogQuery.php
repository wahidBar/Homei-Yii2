<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\SmarthomeLog]].
 *
 * @see \app\models\SmarthomeLog
 */
class SmarthomeLogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\SmarthomeLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\SmarthomeLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
