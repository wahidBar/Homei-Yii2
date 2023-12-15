
<?php

use yii\widgets\Pjax;

Pjax::begin(['id' => 'pjax-HasilKonsultases', 'enableReplaceState' => false, 'linkSelector' => '#pjax-HasilKonsultases ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("defrindr")}']]) ?>
<?= '<div class="table-responsive">'
    . \yii\grid\GridView::widget([
        'layout' => '{summary}{pager}<br/>{items}{pager}',
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getHasilKonsultasis(),
            'pagination' => [
                'pageSize' => 20,
                'pageParam' => 'page-hasilkonsultases',
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
                    $params[0] = 'hasil-konsultasi' . '/' . $action;
                    $params['HasilKonsultasi'] = ['id_isian_lanjutan' => $model->primaryKey()[0]];
                    return $params;
                },
                'buttons'    => [],
                'controller' => 'hasil-konsultasi'
            ],
            // modified by Defri Indra
            [
                'class' => yii\grid\DataColumn::className(),
                'attribute' => 'id_konsultan',
                'value' => function ($model) {
                    if ($rel = $model->konsultan) {
                        return $rel->name;
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'judul',
                'format' => 'text',
            ],
            [
                'attribute' => 'isi',
                'format' => 'ntext',
            ],
            [
                'attribute' => 'image',
                'format' => 'myImage',
            ],
            [
                'attribute' => 'status',
                'format' => 'text',
            ],
            [
                'attribute' => 'created_at',
                'format' => 'iddate',
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'iddate',
            ],
            // modified by Defri Indra
            [
                'class' => yii\grid\DataColumn::className(),
                'attribute' => 'created_by',
                'value' => function ($model) {
                    if ($rel = $model->createdBy) {
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
                'attribute' => 'updated_by',
                'value' => function ($model) {
                    if ($rel = $model->updatedBy) {
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