<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\api\v1\kontraktor;

use Yii;
use yii\web\HttpException;

/**
 * ProyekCctvController implements the CRUD actions for ProyekCctv model.
 **/
class ProyekController extends \app\controllers\api\v1\kontraktor\BaseController
{
    public $modelClass = 'app\models\Proyek';


    public function actionIndex()
    {
        $query = $this->modelClass::find()->innerJoinWith(['proyekAnggotas' => function ($query) {
            $query
                ->where(['{{%t_proyek_anggota}}.[[id_user]]' => Yii::$app->user->id]);
        }]);

        return $this->dataProvider($query);
    }

    public function actionView($id)
    {
        $model = $this->modelClass::find()
            ->andWhere(['{{%t_proyek}}.[[id]]' => $id])
            ->innerJoinWith(['proyekAnggotas' => function ($query) {
                $query
                    ->where(['{{%t_proyek_anggota}}.[[id_user]]' => Yii::$app->user->id]);
            }])->one();

        if ($model == null)
            throw new HttpException(404, 'Proyek tidak ditemukan');
        return $model;
    }
}
