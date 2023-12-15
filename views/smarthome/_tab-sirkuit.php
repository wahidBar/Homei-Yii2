<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
?>

<?= GridView::widget([
    'dataProvider' => new ArrayDataProvider([
        'allModels' => $model->getSmarthomeSirkuits()->active()->all(),
        'pagination' => [
            'pageSize' => 10,
        ],
    ]),
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'nama',
            'value' => function ($model) {
                return "ID:" . $model->id . " | " . $model->nama;
            },
        ],
        [
            'header' => 'Aksi',
            'class' => 'yii\grid\ActionColumn',
            'template' => '{ubah} {delete}',
            'buttons' => [
                'ubah' => function ($url, $model) {
                    return Html::a('<i class="fa fa-pencil"></i>', ['update', "id" => $model->id_smarthome, "_sirkuit" => $model->id], [
                        'title' => 'Ubah',
                        'class' => 'btn btn-primary btn-xs mb-1 mr-1',
                    ]);
                },
                'delete' => function ($url, $model) {
                    return Html::beginForm(['smarthome/hapussirkuit', 'id' => $model->id], 'post', ['class' => 'd-inline-block'])
                        . Html::submitButton('<i class="fa fa-trash"></i>', [
                            'class' => 'btn btn-danger btn-xs mb-1 mr-1',
                            'title' => 'Hapus',
                            'data' => [
                                'confirm' => 'Apakah Anda yakin ingin menghapus item ini?',
                                'method' => 'post',
                            ],
                        ])
                        . Html::endForm();
                }
            ]
        ],
    ],
]); ?>