<?php
/**
 * Customizable controller class.
 * Modified by Defri Indra
 */
echo "<?php\n";
?>

namespace <?= $generator->controllerNs ?>\api;

/**
 * This is the class for REST controller "<?= $controllerClassName ?>".
 * Modified by Defri Indra
 */

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class <?= $controllerClassName ?> extends \app\controllers\api\BaseController
{
    public $modelClass = '<?= $generator->modelClass ?>';

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
        $model = new $this->modelClass;
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
