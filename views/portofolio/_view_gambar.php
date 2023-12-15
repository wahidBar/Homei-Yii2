<?php

use dmstr\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

\yii\widgets\Pjax::begin(['id' => 'pjax-main', 'enableReplaceState' => false, 'linkSelector' => '#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("yo")}']]) ?>

<?= GridView::widget([
    'layout' => '{summary}{pager}{items}{pager}',
    'dataProvider' => new ActiveDataProvider([
        'query' => $model->getPortofolioGambars(),
        'pagination' => [
            'pageSize' => 10,
        ],
    ]),
    'pager' => [
        'class' => app\components\annex\LinkPager::className(),
        'firstPageLabel' => 'First',
        'lastPageLabel' => 'Last'
    ],
    'filterModel' => $searchModel,
    'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
    'headerRowOptions' => ['class' => 'x'],
    'columns' => [
        'gambar_design:myImage',
        [
            'attribute' => 'jenis_gambar',
            'value' => function ($model) {
                return $model::LIST_JENIS_GAMBAR[$model->jenis_gambar];
            }
        ]
    ],
]); ?>
<?php \yii\widgets\Pjax::end() ?>