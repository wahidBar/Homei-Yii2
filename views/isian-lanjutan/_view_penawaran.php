<?php

use dmstr\helpers\Html;
?>
<?php

use yii\widgets\Pjax;

Pjax::begin(['id' => 'pjax-Penawarans', 'enableReplaceState' => false, 'linkSelector' => '#pjax-Penawarans ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("defrindr")}']]) ?>
<?= '<div class="table-responsive">'
    .                     \yii\grid\GridView::widget([
        'layout' => '{summary}<br/>{items}{pager}',
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getPenawarans(),
            'pagination' => [
                'pageSize' => 20,
                'pageParam' => 'page-penawarans',
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
                'template'   => '{view}',
                'contentOptions' => ['nowrap' => 'nowrap'],
                'urlCreator' => function ($action, $model, $key, $index) {
                    // using the column name as key, not mapping to 'id' like the standard generator
                    $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                    $params[0] = 'penawaran' . '/' . $action;
                    $params['Penawaran'] = ['id_isian_lanjutan' => $model->primaryKey()[0]];
                    return $params;
                },
                'buttons'    => [
                    'view' => function ($url, $model, $key) {
                        $params[0] = '/penawaran/detail';
                        $params['id'] = $model->id;
                        return Html::button("<i class='fa fa-clock-o'></i>", [
                            "value" => \yii\helpers\Url::to($params),
                            "class" => "mr-1 mb-1 btn btn-warning modalButton",
                            "title" => "Lihat Detail"
                        ]);
                    },
                ],
                'controller' => 'penawaran'
            ],
            [
                'attribute' => 'kode_penawaran',
                'format' => 'text',
            ],
            [
                'attribute' => 'tgl_transaksi',
                'format' => 'iddate',
            ],
            [
                'attribute' => 'estimasi_waktu',
                'format' => 'text',
            ],
            [
                'attribute' => 'total_harga_penawaran',
                'format' => 'rp',
            ],
            // [
            //     'attribute' => 'flag',
            //     'format' => 'boolean',
            // ],
        ]
    ])
    . '</div>' ?>
<?php Pjax::end() ?>