<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\LogHargaMaterial]].
 *
 * @see \app\models\LogHargaMaterial
 */
class LogHargaMaterialQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\models\LogHargaMaterial[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\LogHargaMaterial|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\LogHargaMaterial|array|null
     */
    public function column($db = null)
    {
        return parent::column($db);
    }

    public function count($q = '*', $db = null)
    {
        return parent::count($q, $db);
    }
}
