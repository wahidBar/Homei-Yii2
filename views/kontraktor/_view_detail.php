<?php

use dmstr\helpers\Html;
use yii\widgets\Pjax;

?>
<div style='position: relative'>
    <?= Html::a(
        '<span class="glyphicon glyphicon-plus"></span> ' . 'Tambah Baru' . ' Kontraktor Detail',
        ['kontraktor-detail/create', 'KontraktorDetail' => ['id_kontraktor' => $model->id]],
        ['class' => 'btn btn-success btn-xs']
    ); ?>
</div>
<?php
Pjax::begin(['id' => 'pjax-KontraktorDetails', 'enableReplaceState' => false, 'linkSelector' => '#pjax-KontraktorDetails ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("defrindr")}']]) ?>
<?= '<div class="table-responsive">'
    . \yii\grid\GridView::widget([
        'layout' => '{summary}{pager}<br/>{items}{pager}',
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getKontraktorDetails(),
            'pagination' => [
                'pageSize' => 20,
                'pageParam' => 'page-kontraktordetails',
            ]
        ]),
        'pager'        => [
            'class'          => \app\components\annex\LinkPager::className(),
            'firstPageLabel' => 'First',
            'lastPageLabel'  => 'Last'
        ],
        'columns' => [
            [
                'class'      => 'yii\grid\ActionColumn',
                'template'   => '{update} {delete} ',
                'contentOptions' => ['nowrap' => 'nowrap'],
                'urlCreator' => function ($action, $model, $key, $index) {
                    // using the column name as key, not mapping to 'id' like the standard generator
                    $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                    $params[0] = 'kontraktor-detail' . '/' . $action;
                    $params['KontraktorDetail'] = ['id_kontraktor' => $model->primaryKey()[0]];
                    return $params;
                },
                'buttons'    => [
                    'update' => function ($url, $model, $key) {
                        return Html::a("<i class='fa fa-pencil'></i>", $url, ["class" => "mr-1 mb-1 btn btn-warning", "title" => "Edit Data"]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a("<i class='fa fa-trash'></i>", $url, ["class" => "mr-1 mb-1 btn btn-danger", "title" => "Hapus Data", "method" => "POST"]);
                    },
                ],
                'controller' => 'kontraktor-detail'
            ],
            // modified by Defri Indra
            [
                'class' => yii\grid\DataColumn::className(),
                'attribute' => 'id_user',
                'value' => function ($model) {
                    if ($rel = $model->user) {
                        return $rel->name;
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
            ],
        ]
    ])
    . '</div>' ?>
<?php Pjax::end() ?>