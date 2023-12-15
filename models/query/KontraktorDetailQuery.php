<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\KontraktorDetail]].
 *
 * @see \app\models\KontraktorDetail
 */
class KontraktorDetailQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\KontraktorDetail[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\KontraktorDetail|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
