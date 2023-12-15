<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

?>

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
            'class'          => \app\components\frontend\LinkPager::className(),
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
        ]
    ])
    . '</div>' ?>
<?php Pjax::end() ?>