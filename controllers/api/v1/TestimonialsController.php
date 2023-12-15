<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "TestimonialsController".
 * Modified by Defri Indra
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class TestimonialsController extends \app\controllers\api\BaseController
{
    public $modelClass = 'app\models\Testimonials';

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
}
