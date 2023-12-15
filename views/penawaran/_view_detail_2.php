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
            // modified by Defri Indra
            [
                'class' => yii\grid\DataColumn::className(),
                'attribute' => 'id_material',
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
                'attribute' => 'kisaran_harga',
                'value' => function ($model) {
                    // if ($model->kisaran_harga != null) {
                    return \Yii::$app->formatter->asRp($model->supplierBarang->harga_proyek);
                    // } else {
                    //     return '-';
                    // }
                },
                'format' => 'raw',
            ],
            [
                'class' => yii\grid\DataColumn::className(),
                'attribute' => 'jumlah',
                'value' => function ($model) {
                    $material = SupplierBarang::find()->where(['t_supplier_barang.id' => $model->id_material])
                        ->joinWith(['satuan'])
                        ->select('t_master_satuan.nama')->column();
                    if ($model->jumlah != 0) {
                        return $model->jumlah . " " . $material[0];
                    } else {
                        return '-';
                    }
                },
                'format' => 'raw',
            ],
            // [
            //     'class' => yii\grid\DataColumn::className(),
            //     'attribute' => 'volume',
            //     'value' => function ($model) {
            //         $material = SupplierBarang::find()->where(['t_supplier_barang.id' => $model->id_material])
            //             ->joinWith(['satuan'])
            //             ->select('t_master_satuan.nama')->column();
            //         if ($model->volume != 0) {
            //             return $model->volume . " " . $material[0];
            //         } else {
            //             return '';
            //         }
            //     },
            //     'format' => 'raw',
            // ],
            [
                'attribute' => 'sub_harga',
                'format' => 'rp',
            ],
        ]
    ])
    . '</div>' ?>
<?php Pjax::end() ?>
<p>
    <b>
        Total Harga Material : <?= \Yii::$app->formatter->asRp($model->harga_penawaran) ?>
    </b>
</p>