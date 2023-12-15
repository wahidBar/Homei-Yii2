<?php

namespace app\controllers\api\v1\keuangan;

class BaseController extends \app\controllers\api\BaseController
{
    use \app\components\productive\Messages;

    const ALLOWED_ROLES = [];

    function verbs()
    {
        return [
            'index' => ['GET'],
            'view' => ['GET'],
            'create' => ['POST'],
            'update' => ['POST'],
            'delete' => ['DELETE'],
        ];
    }

    /**
     * hasAccessAtThisProject
     * 
     */
    function hasAccessAtThisProject($id)
    {
        $model = \app\models\Proyek::find()
            ->where(['{{%t_proyek}}.[[id]]' => $id])
            ->joinWith(['proyekAnggotas' => function ($query) {
                $query
                    ->andWhere(['{{%t_proyek_anggota}}.[[id_user]]' => \Yii::$app->user->id])
                    ->andWhere(['{{%t_proyek_anggota}}.[[id_role]]' => static::ALLOWED_ROLES]);
            }])
            ->one();
        if ($model == null) {
            throw new \yii\web\HttpException(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }

        return $model;
    }
}
