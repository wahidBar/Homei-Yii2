<?php

use dmstr\helpers\Html;
use yii\widgets\Pjax;

?>
<div style='position: relative'>
    <div style='position:absolute; right: 0px; top: 0px;'>
        <?= Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . 'Semua Data' . ' Konsultases',
            ['konsultasi/index'],
            ['class' => 'btn text-muted btn-xs']
        ) ?>
        <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . 'Tambah Baru' . ' Konsultase',
            ['konsultasi/create', 'Konsultase' => ['Array' => $model->id]],
            ['class' => 'btn btn-success btn-xs']
        ); ?>
    </div>
</div><?php Pjax::begin(['id' => 'pjax-Konsultases', 'enableReplaceState' => false, 'linkSelector' => '#pjax-Konsultases ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("defrindr")}']]) ?>
<?= '<div class="table-responsive">'
    .                     \yii\grid\GridView::widget([
        'layout' => '{summary}{pager}<br/>{items}{pager}',
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getKonsultasis(),
            'pagination' => [
                'pageSize' => 20,
                'pageParam' => 'page-konsultases',
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
                    $params[0] = 'konsultasi' . '/' . $action;
                    $params['Konsultasi'] = ['id_isian_lanjutan' => $model->primaryKey()[0]];
                    return $params;
                },
                'buttons'    => [],
                'controller' => 'konsultasi'
            ],
            [
                'attribute' => 'ticket',
                'format' => 'text',
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
                'attribute' => 'is_active',
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
        ]
    ])
    . '</div>' ?>
<?php Pjax::end() ?>