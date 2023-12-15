<?php

use yii\widgets\Pjax;

Pjax::begin(['id' => 'pjax-IsianLanjutanRuangans', 'enableReplaceState' => false, 'linkSelector' => '#pjax-IsianLanjutanRuangans ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("defrindr")}']]) ?>
<?= '<div class="table-responsive">'
    . \yii\grid\GridView::widget([
        'layout' => '{items}{pager}',
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getIsianLanjutanRuangans(),
            'pagination' => [
                'pageSize' => 20,
                'pageParam' => 'page-isianlanjutanruangans',
            ]
        ]),
        'pager'        => [
            'class'          => \app\components\annex\LinkPager::className(),
            'firstPageLabel' => 'First',
            'lastPageLabel'  => 'Last'
        ],
        'columns' => [
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