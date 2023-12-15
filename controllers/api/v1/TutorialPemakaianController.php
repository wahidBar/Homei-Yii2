<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "TutorialPemakaianController".
 * Modified by Defri Indra
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class TutorialPemakaianController extends \app\controllers\api\BaseController
{
    public $modelClass = '\app\models\TutorialPemakaian';

    // except index from access control
    public function behaviors()
    {
        $parent = parent::behaviors();
        $parent['authentication']['except'] = ['index', 'view'];

        return $parent;
    }

    public function actionIndex($kategori = null)
    {
        $query = $this->modelClass::find();
        if ($kategori) {
            $query->where(['id_kategori' => $kategori]);
        }

        return $this->dataProvider($query);
    }

    public function actionView($id)
    {
        $data = $this->findModel($id);
        return [
            "success" => true,
            "data" => $data
        ];
    }
}
