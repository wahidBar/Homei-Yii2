<?php
/**
 * Created by PhpStorm.
 * User: feb
 * Date: 30/05/16
 * Time: 00.14
 */

namespace app\components;


use yii\helpers\Html;

class ActionDeleteButton
{
    public static function getButtons()
    {
        return [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'buttons' => [

              'delete' => function ($url, $model, $key) {
                  return Html::a("<i class='fa fa-trash'></i>", ["delete", "id"=>$model->id], [
                      "class"=>"btn btn-danger",
                      "title"=>"Hapus Data",
                      "data-confirm" => "Apakah Anda yakin ingin menghapus data ini ?",
                      //"data-method" => "GET"
                  ]);
              },

            ],
            'contentOptions' => ['nowrap'=>'nowrap', 'style'=>'text-align:center;width:140px']
        ];
    }
}
