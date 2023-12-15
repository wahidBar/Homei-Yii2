<?php

use \yii\helpers\Html;
use yii\widgets\Pjax;

?>

<div style='position: relative'>
    <?= Html::a(
        '<span class="glyphicon glyphicon-plus"></span> ' . 'Tambah Dokumen',
        ['proyek-dokumen/create', 'ProyekDokumen' => ['id_proyek' => $model->id], "id_project" => $model->id],
        ['class' => 'btn btn-success btn-xs']
    ); ?>
</div>
<?php Pjax::begin(['id' => 'pjax-ProyekDokumens', 'enableReplaceState' => false, 'linkSelector' => '#pjax-ProyekDokumens ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("defrindr")}']]) ?>
<?= '<div class="table-responsive">'
    . \yii\grid\GridView::widget([
        'layout' => '{summary}<br/>{items}{pager}',
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getProyekDokumens(),
            'pagination' => [
                'pageSize' => 10,
                'pageParam' => 'page-proyekDokumens',
            ]
        ]),
        'pager'        => [
            'class'          => \app\components\annex\LinkPager::className(),
            'firstPageLabel' => 'First',
            'lastPageLabel'  => 'Last'
        ],
        'columns' => [
            // [
            //     'attribute' => 'pathfile',
            //     'format' => 'download',
            // ],
            [
                'attribute' => 'type',
                'format' => 'text',
                'value' => function ($model) {
                    return $model::TYPE_DOCUMENTS[$model->type];
                }
            ],
            [
                'attribute' => 'nama_file',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->nama_file, Yii::$app->formatter->asFileLink($model->pathfile), ['target' => '_blank']);
                }
            ],
            [
                'attribute' => 'created_at',
                'format' => 'iddate',
            ],

            [
                'class'      => 'yii\grid\ActionColumn',
                'template'   => '{update} {delete}',
                'contentOptions' => ['nowrap' => 'nowrap'],
                'urlCreator' => function ($action, $model, $key, $index) {
                    // using the column name as key, not mapping to 'id' like the standard generator
                    $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                    $params[0] = 'proyek-dokumen' . '/' . $action;
                    $params['ProyekDokumen'] = ['id_proyek' => $model->id];
                    $params['id_project'] = $model->id;
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
                'controller' => 'proyek-dokumen'
            ],
        ]
    ])
    . '</div>' ?>
<?php Pjax::end() ?>