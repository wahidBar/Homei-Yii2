<?php

use \yii\helpers\Html;
use yii\widgets\Pjax;

use function PHPSTORM_META\map;

?>

<div style='position: relative'>
    <?= Html::a(
        '<span class="glyphicon glyphicon-plus"></span> ' . 'Tambah CCTV',
        ['proyek-cctv/create', 'ProyekCctv' => ['id_proyek' => $model->id], 'id_project' => $model->id],
        ['class' => 'btn btn-success btn-xs']
    ); ?>
</div>
<?php Pjax::begin(['id' => 'pjax-ProyekCctvs', 'enableReplaceState' => false, 'linkSelector' => '#pjax-ProyekCctvs ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("defrindr")}']]) ?>
<?= '<div class="table-responsive">'
    . \yii\grid\GridView::widget([
        'layout' => '{summary}<br/>{items}{pager}',
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getProyekCctvs(),
            'pagination' => [
                'pageSize' => 10,
                'pageParam' => 'page-proyekCctvs',
            ]
        ]),
        'pager'        => [
            'class'          => \app\components\annex\LinkPager::className(),
            'firstPageLabel' => 'First',
            'lastPageLabel'  => 'Last'
        ],
        'columns' => [
            'lokasi',
            [
                'attribute' => 'tipe',
                'value' => function ($model) {
                    return $model->tipeLabel;
                }
            ],
            'link:url',
            [
                'class'      => 'yii\grid\ActionColumn',
                'template'   => '{update} {delete}',
                'contentOptions' => ['nowrap' => 'nowrap'],
                'urlCreator' => function ($action, $model, $key, $index) {
                    // using the column name as key, not mapping to 'id' like the standard generator
                    $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                    $params[0] = 'proyek-cctv' . '/' . $action;
                    $params['ProyekCctv'] = ['id_proyek' => $model->id];

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