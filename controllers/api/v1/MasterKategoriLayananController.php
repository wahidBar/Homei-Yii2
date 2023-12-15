<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "MasterKategoriLayananController".
 * Modified by Defri Indra
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class MasterKategoriLayananController extends \app\controllers\api\BaseController
{
    public $modelClass = 'app\models\MasterKategoriLayananSameday';
    public function behaviors()
    {
        $parent = parent::behaviors();
        $parent['authentication']['except'] = ['index'];
        return $parent;
    }

    public function actionIndex()
    {
        $query = $this->modelClass::find();
        return $this->dataProvider($query);
    }
}
