<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "KategoriTutorialPemakaianController".
 * Modified by Defri Indra
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class KategoriTutorialPemakaianController extends \app\controllers\api\BaseController
{
    public $modelClass = '\app\models\TutorialPemakaianKategori';

    // except index from access control
    public function behaviors()
    {
        $parent = parent::behaviors();
        $parent['authentication']['except'] = ['index'];

        return $parent;
    }


    public function actionIndex()
    {
        $query = $this->modelClass::find()->andWhere(['flag' => 1])
            ->select(['id', 'nama_kategori'])
            ->all();
        return $query;
    }
}
