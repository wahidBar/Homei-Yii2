<?php

use dmstr\helpers\Html;
use yii\widgets\Pjax;

?>

<div style='position: relative'>
    <div style='position:absolute; right: 0px; top: 0px;'>
        <?= Html::a(
            '<span class="glyphicon glyphicon-list"></span> ' . 'Semua Data' . ' Isian Lanjutan Ruangans0s',
            ['isian-lanjutan-ruangan/index'],
            ['class' => 'btn text-muted btn-xs']
        ) ?>
        <?= Html::a(
            '<span class="glyphicon glyphicon-plus"></span> ' . 'Tambah Baru' . ' Isian Lanjutan Ruangans0',
            ['isian-lanjutan-ruangan/create', 'IsianLanjutanRuangans0' => ['Array' => $model->id]],
            ['class' => 'btn btn-success btn-xs']
        ); ?>
    </div>
</div><?php Pjax::begin(['id' => 'pjax-IsianLanjutanRuangans0s', 'enableReplaceState' => false, 'linkSelector' => '#pjax-IsianLanjutanRuangans0s ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("defrindr")}']]) ?>
<?= '<div class="table-responsive">'
    .                     \yii\grid\GridView::widget([
        'layout' => '{summary}{pager}<br/>{items}{pager}',
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getIsianLanjutanRuangans0(),
            'pagination' => [
                'pageSize' => 20,
                'pageParam' => 'page-isianlanjutanruangans0s',
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
                    $params[0] = 'isian-lanjutan-ruangan' . '/' . $action;
                    $params['IsianLanjutanRuangan'] = ['id_isian_lanjutan' => $model->primaryKey()[0]];
                    return $params;
                },
                'buttons'    => [],
                'controller' => 'isian-lanjutan-ruangan'
            ],
            // modified by Defri Indra
            [
                'class' => yii\grid\DataColumn::className(),
                'attribute' => 'id_ruangan',
                'value' => function ($model) {
                    if ($rel = $model->ruangan) {
                        return $rel->nama;
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