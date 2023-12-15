<?php

namespace app\controllers\api;

/**
 * This is the class for REST controller "SupplierController".
 * Modified by Defri Indra
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class SupplierController extends \app\controllers\api\BaseController
{
    public $modelClass = 'app\models\Supplier';

    /**
    * @inheritdoc
    */
    public function behaviors()
    {
        $parent = parent::behaviors();
        $parent['authentication'] = [
            "class" => "\app\components\CustomAuth",
            "except" => ["index", "view"]
        ];

        return $parent;
    }

    public function actionCreate(){
        $model = new $this->modalClass;
        $model->scenario=$model::SCENARIO_CREATE;
        return $model->apiDummyCreate();
    }
    
    public function actionUpdate($id){
        $model = $this->findModel($id);
        $model->scenario=$model::SCENARIO_UPDATE;
        return $model->apiDummyUpdate();
    }
    
    public function actionDelete($id){
        $model = $this->findModel($id);
        return $model->apiDummyDelete();
    }
}
