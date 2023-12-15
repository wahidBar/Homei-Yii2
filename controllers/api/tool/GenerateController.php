<?php

namespace app\controllers\api\tool;

use app\components\Constant;
use app\components\SendFcm;

/**
 * This is the class for REST controller "PlaceController".
 */

class GenerateController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\Place';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $parent = parent::actions();
        // $parent['index']['prepareDataProvider'] = [$this,"prepareDataProvider"];

        unset($parent['index']);
        unset($parent['view']);
        unset($parent['create']);
        unset($parent['update']);
        unset($parent['delete']);

        return $parent;
    }

    public function actionFields($table_name)
    {
        $q_columns = \Yii::$app->db->createCommand("select column_name from information_schema.columns where table_name=\"$table_name\" and table_schema=schema()")->queryAll();
        echo "
        <pre>
        public function fields()
        {
            \$parent=parent::fields();";
        foreach ($q_columns as $qc) {
            $qc = (object) $qc;
            echo "

            if(isset(\$parent['{$qc->column_name}'])){
                unset(\$parent['{$qc->column_name}']);
                \$parent['{$qc->column_name}']=function(\$model){
                    return \$model->{$qc->column_name};
                };
            }
            ";
        }
        echo "
            return \$parent;
        }
        </pre>";
        die;
    }

    public function generateEmail()
    {
        $email = Constant::generateRandomString(32) . "@gmail.com";
        return $email;
    }


    public function generateName()
    {
        $email = Constant::generateRandomString(32);
        return $email;
    }

    public function generatePhone($suffix = "62")
    {
        $number = Constant::generateRandomString(15, "0123456789");
        return $suffix . $number;
    }

    public function actionSendFcm()
    {
        return SendFcm::message([$_POST['token']], [
            "title" => $_POST['title'],
            "body" => $_POST['body'],
        ], function ($data) {
            return $data;
        });
    }
}
