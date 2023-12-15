<?php

use yii\helpers\Html;
use yii\grid\GridView;
?>
<?= GridView::widget([
    'layout' => '{summary}{pager}{items}{pager}',
    'dataProvider' => $dataProvider2,
    'pager'        => [
        'class'          => app\components\annex\LinkPager::className(),
        'firstPageLabel' => 'First',
        'lastPageLabel'  => 'Last'
    ],
    'filterModel' => $searchModel2,
    'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
    'headerRowOptions' => ['class' => 'x'],
    'columns' => [

        \app\components\ActionButton::getButtons([
            'template' => '{update} {delete}',
            'buttons' => [
                'update' => function ($url, $model, $key) {
                    return Html::a("<i class='fa fa-pencil'></i>", ["master-jenis-satuan/update", "id" => $model->id], ["class" => "mr-1 mb-1 btn btn-warning", "title" => "Edit Data"]);
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a("<i class='fa fa-trash'></i>", ["master-jenis-satuan/delete", "id" => $model->id], [
                        "class" => "mr-1 mb-1 btn btn-danger",
                        "title" => "Hapus Data",
                        "data-confirm" => "Apakah Anda yakin ingin menghapus data ini ?",
                        //"data-method" => "GET"
                    ]);
                },
            ]
        ]),

        [
            'attribute' => 'nama',
            'format' => 'text',
        ],
    ],
]); ?>