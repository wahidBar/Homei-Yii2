<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\PortofolioGambar]].
 *
 * @see \app\models\PortofolioGambar
 */
class PortofolioGambarQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\PortofolioGambar[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\PortofolioGambar|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
