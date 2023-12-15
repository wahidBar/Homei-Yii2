<?php

use yii\widgets\Pjax;

Pjax::begin(['id' => 'pjax-DealPelanggans', 'enableReplaceState' => false, 'linkSelector' => '#pjax-DealPelanggans ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("defrindr")}']]) ?>
<?= '<div class="table-responsive">'
    . \yii\grid\GridView::widget([
        'layout' => '{summary}{pager}<br/>{items}{pager}',
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getDealPelanggans(),
            'pagination' => [
                'pageSize' => 20,
                'pageParam' => 'page-dealpelanggans',
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
                'template'   => '{view} {update}',
                'contentOptions' => ['nowrap' => 'nowrap'],
                'urlCreator' => function ($action, $model, $key, $index) {
                    // using the column name as key, not mapping to 'id' like the standard generator
                    $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                    $params[0] = 'deal-pelanggan' . '/' . $action;
                    $params['DealPelanggan'] = ['id_isian_lanjutan' => $model->primaryKey()[0]];
                    return $params;
                },
                'buttons'    => [],
                'controller' => 'deal-pelanggan'
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
            // modified by Defri Indra
            [
                'class' => yii\grid\DataColumn::className(),
                'attribute' => 'id_kontraktor',
                'value' => function ($model) {
                    if ($rel = $model->kontraktor) {
                        return $rel->id;
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
            ],
            // modified by Defri Indra
            [
                'class' => yii\grid\DataColumn::className(),
                'attribute' => 'id_penawaran',
                'value' => function ($model) {
                    if ($rel = $model->penawaran) {
                        return $rel->id;
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'nama_pelanggan',
                'format' => 'text',
            ],
            [
                'attribute' => 'alamat_pelanggan',
                'format' => 'ntext',
            ],
            [
                'attribute' => 'no_hp',
                'format' => 'text',
            ],
            [
                'attribute' => 'no_ktp',
                'format' => 'text',
            ],
            [
                'attribute' => 'alamat_proyek',
                'format' => 'ntext',
            ],
            [
                'attribute' => 'created_at',
                'format' => 'iddate',
            ],
        ]
    ])
    . '</div>' ?>
<?php Pjax::end() ?>