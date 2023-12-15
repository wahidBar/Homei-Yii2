<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\Kontraktor]].
 *
 * @see \app\models\Kontraktor
 */
class KontraktorQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\Kontraktor[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['t_kontraktor.flag' => 1]);
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Kontraktor|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['t_kontraktor.flag' => 1]);
        return parent::one($db);
    }
}
