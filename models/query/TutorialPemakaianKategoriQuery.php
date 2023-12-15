<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\TutorialPemakaianKategori]].
 *
 * @see \app\models\TutorialPemakaianKategori
 */
class TutorialPemakaianKategoriQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\models\TutorialPemakaianKategori[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\TutorialPemakaianKategori|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
