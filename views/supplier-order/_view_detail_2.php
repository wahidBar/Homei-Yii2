<?php

use app\models\MasterMaterial;
use app\models\SupplierBarang;
use yii\widgets\Pjax;

Pjax::begin(['id' => 'pjax-SupplierOrderDetails', 'enableReplaceState' => false, 'linkSelector' => '#pjax-SupplierOrderDetails ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("defrindr")}']]) ?>
<?= '<div class="table-responsive">'
    .                     \yii\grid\GridView::widget([
        'layout' => '{summary}{pager}<br/>{items}{pager}',
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getSupplierOrderDetails(),
            'pagination' => [
                'pageSize' => 20,
                'pageParam' => 'page-supplierOrderdetails',
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
                'attribute' => 'nota',
                'value' => function ($model) {
                    if ($rel = $model->supplierOrder) {
                        return $rel->no_nota;
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
            ],
            // modified by Defri Indra
            [
                'class' => yii\grid\DataColumn::className(),
                'attribute' => 'barang',
                'value' => function ($model) {
                    if ($rel = $model->supplierBarang) {
                        return $rel->nama_barang;
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
            ],
            [
                'class' => yii\grid\DataColumn::className(),
                'attribute' => 'satuan',
                'value' => function ($model) {
                    $material = SupplierBarang::find()->where(['t_supplier_barang.id' => $model->supplierBarang->id])
                        ->joinWith(['satuan'])
                        ->select('t_master_satuan.nama')->column();
                    if ($material) {
                        return $material[0];
                    } else {
                        return '-';
                    }
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'jumlah',
                'format' => 'text',
            ],
            // [
            //     'attribute' => 'volume',
            //     'format' => 'text',
            // ],
            [
                'attribute' => 'subtotal',
                'format' => 'rp',
            ],
            
        ]
    ])
    . '</div>' ?>
<?php Pjax::end() ?>