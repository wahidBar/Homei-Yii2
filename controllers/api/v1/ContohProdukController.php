<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "ContohProdukController".
 * Modified by Defri Indra
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class ContohProdukController extends \app\controllers\api\BaseController
{
    public $modelClass = 'app\models\ContohProduk';

    public function behaviors()
    {
        $parent = parent::behaviors();
        unset($parent['authentication']);

        return $parent;
    }

    public function actionIndex()
    {
        $query = $this->modelClass::find();
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
