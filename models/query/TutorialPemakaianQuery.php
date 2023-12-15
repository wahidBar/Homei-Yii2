<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\TutorialPemakaian]].
 *
 * @see \app\models\TutorialPemakaian
 */
class TutorialPemakaianQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\TutorialPemakaian[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\TutorialPemakaian|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
