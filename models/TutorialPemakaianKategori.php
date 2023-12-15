<?php

namespace app\models;

use Yii;
use \app\models\base\TutorialPemakaianKategori as BaseTutorialPemakaianKategori;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_tutorial_pemakaian_kategori".
 * Modified by Defri Indra M
 */
class TutorialPemakaianKategori extends BaseTutorialPemakaianKategori
{
    /**
     * find All tutorial from kategori
     */
    public static function findAllTutorial($id)
    {
        $tutorial = TutorialPemakaian::find()->where(['id_kategori' => $id])->all();
        return $tutorial;
    }
}
