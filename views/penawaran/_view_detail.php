<?php

use app\models\MasterMaterial;
use app\models\SupplierBarang;
use yii\widgets\Pjax;

Pjax::begin(['id' => 'pjax-PenawaranDetails', 'enableReplaceState' => false, 'linkSelector' => '#pjax-PenawaranDetails ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("defrindr")}']]) ?>
<?= '<div class="table-responsive">'
    .                     \yii\grid\GridView::widget([
        'layout' => '{summary}{pager}<br/>{items}{pager}',
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getPenawaranDetails(),
            'pagination' => [
                'pageSize' => 20,
                'pageParam' => 'page-penawarandetails',
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
                    $params[0] = 'penawaran-detail' . '/' . $action;
                    $params['PenawaranDetail'] = ['id_penawaran' => $model->primaryKey()[0]];
                    return $params;
                },
                'buttons'    => [],
                'controller' => 'penawaran-detail'
            ],
            // modified by Defri Indra
            [
                'class' => yii\grid\DataColumn::className(),
                'attribute' => 'id_material',
                'value' => function ($model) {
                    if ($rel = $model->supplierBarang) {
                        return $rel->nama;
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'kisaran_harga',
                'format' => 'rp',
            ],
            [
                'class' => yii\grid\DataColumn::className(),
                'attribute' => 'jumlah',
                'value' => function ($model) {
                    $material = SupplierBarang::find()->where(['id' => $model->id_material])
                        ->joinWith(['satuan'])
                        ->select('t_master_satuan.nama')->column();
                    // var_dump($material);die;
                    if ($model->jumlah != 0) {
                        return $model->jumlah . " " . $material[0];
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'sub_harga',
                'format' => 'rp',
            ],
        ]
    ])
    . '</div>' ?>
<?php Pjax::end() ?>