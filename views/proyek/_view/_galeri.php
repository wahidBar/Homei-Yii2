<?php

use \yii\helpers\Html;
use yii\widgets\Pjax;

?>

<div style='position: relative'>
    <?= Html::a(
        '<span class="glyphicon glyphicon-plus"></span> ' . 'Tambah Gambar',
        ['proyek-galeri/create', 'ProyekGaleri' => ['id_proyek' => $model->id], "id_project" => $model->id],
        ['class' => 'btn btn-success btn-xs']
    ); ?>
</div>
<?php Pjax::begin(['id' => 'pjax-ProyekGaleris', 'enableReplaceState' => false, 'linkSelector' => '#pjax-ProyekGaleris ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("defrindr")}']]) ?>
<?= '<div class="table-responsive">'
    . \yii\grid\GridView::widget([
        'layout' => '{summary}<br/>{items}{pager}',
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getProyekGaleris(),
            'pagination' => [
                'pageSize' => 5,
                'pageParam' => 'page-proyekgaleris',
            ]
        ]),
        'pager'        => [
            'class'          => \app\components\annex\LinkPager::className(),
            'firstPageLabel' => 'First',
            'lastPageLabel'  => 'Last'
        ],
        'columns' => [
            [
                'attribute' => 'nama_file',
                'format' => 'myImage',
            ],
            [
                'attribute' => 'keterangan',
                'format' => 'ntext',
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
                    $params[0] = 'proyek-galeri' . '/' . $action;
                    $params['ProyekGaleri'] = ['id_proyek' => $model->id];
                    $params['id_project'] = $model->id;
                    return $params;
                },
                'buttons'    => [
                    'update' => function ($url, $model, $key) {
                        return Html::a("<i class='fa fa-pencil'></i>", $url, ["class" => "mr-1 mb-1 btn btn-warning", "title" => "Edit Data"]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a("<i class='fa fa-trash'></i>", $url, [
                            "class" => "mr-1 mb-1 btn btn-danger",
                            "title" => "Hapus Data",
                            'method' => 'post',
                            "data-confirm" => "Apakah Anda yakin ingin menghapus data ini ?",
                        ]);
                    },
                ],
                'controller' => 'proyek-galeri'
            ],
        ]
    ])
    . '</div>' ?>
<?php Pjax::end() ?>