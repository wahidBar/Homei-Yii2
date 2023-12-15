<?php

use dmstr\helpers\Html;
use yii\widgets\Pjax;

?>

<div style='position: relative'>
    <?= Html::a(
        '<span class="glyphicon glyphicon-plus"></span> ' . 'Tambah Gambar',
        ['proyek-galeri', 'ProyekGaleri' => ['id_proyek' => $model->id]],
        ['class' => 'btn btn-warning btn-xs']
    ); ?>
</div>
<?php Pjax::begin(['id' => 'pjax-ProyekGaleris', 'enableReplaceState' => false, 'linkSelector' => '#pjax-ProyekGaleris ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("defrindr")}']]) ?>
<?= '<div class="table-responsive">'
    . \yii\grid\GridView::widget([
        'layout' => '{summary}{pager}<br/>{items}{pager}',
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getProyekGaleris(),
            'pagination' => [
                'pageSize' => 5,
                'pageParam' => 'page-proyekgaleris',
            ]
        ]),
        'pager'        => [
            'class'          => \app\components\frontend\LinkPager::className(),
            'firstPageLabel' => 'First',
            'lastPageLabel'  => 'Last'
        ],
        'columns' => [
            // [
            //     'class' => yii\grid\DataColumn::className(),
            //     'attribute' => 'id_proyek_kemajuan',
            //     'value' => function ($model) {
            //         if ($rel = $model->proyekKemajuan) {
            //             return $rel->id;
            //         } else {
            //             return '';
            //         }
            //     },
            //     'format' => 'raw',
            // ],
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
            ]
        ]
    ])
    . '</div>' ?>
<?php Pjax::end() ?>