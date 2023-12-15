<?php
/**
 * Created by PhpStorm.
 * User: feb
 * Date: 30/05/16
 * Time: 00.14
 */

namespace app\components;


use yii\helpers\Html;

class ActionEditBatalButton
{
    public static function getButtons()
    {
        return [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}',
            'buttons' => [

                'update' => function ($url, $model, $key) {
                    return Html::a("<i class='fa fa-pencil'></i>", ["updatebtl", "id"=>$model->id], ["class"=>"btn btn-warning", "title"=>"Edit Data"]);
                },

            ],
            'contentOptions' => ['nowrap'=>'nowrap', 'style'=>'text-align:center;width:140px']
        ];
    }
}
