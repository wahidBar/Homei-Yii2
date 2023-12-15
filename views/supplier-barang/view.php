<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use dmstr\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use app\components\annex\Tabs;

/**
 * @var yii\web\View $this
 * @var app\models\SupplierBarang $model
 */

$this->title = 'Supplier Barang : ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Supplier Barang', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';
?>
<div class="giiant-crud supplier-barang-view">

    <!-- menu buttons -->
    <p class='pull-left'>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . 'Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'Tambah Baru', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <p class="pull-right">
        <?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . 'Daftar Supplier Barang', ['index'], ['class' => 'btn btn-default']) ?>
    </p>

    <div class="clearfix"></div>

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <?= \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <?php $this->beginBlock('app\models\SupplierBarang'); ?>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            // modified by Defri Indra
                            [
                                'format' => 'html',
                                'attribute' => 'submaterial_id',
                                'value' => ($model->supplierSubMaterial ? $model->supplierSubMaterial->nama : '<span class="label label-warning">?</span>'),
                            ],
                            [
                                'format' => 'html',
                                'attribute' => 'supplier_id',
                                'value' => ($model->supplier ? $model->supplier->nama_supplier : '<span class="label label-warning">?</span>'),
                            ],
                            [
                                'attribute' => 'nama_barang',
                                'format' => 'text',
                            ],
                            [
                                'attribute' => 'slug',
                                'format' => 'text',
                            ],
                            [
                                'format' => 'html',
                                'attribute' => 'panjang',
                                'value' => ($model->panjang ? $model->panjang . ' cm' : '-'),
                            ],
                            [
                                'format' => 'html',
                                'attribute' => 'lebar',
                                'value' => ($model->lebar ? $model->lebar . ' cm' : '-'),
                            ],
                            [
                                'format' => 'html',
                                'attribute' => 'tebal',
                                'value' => ($model->tebal ? $model->tebal . ' cm' : '-'),
                            ],
                            [
                                'format' => 'html',
                                'attribute' => 'satuan_id',
                                'value' => ($model->satuan ? $model->satuan->nama : '<span class="label label-warning">?</span>'),
                            ],
                            [
                                'attribute' => 'harga_ritel',
                                'format' => 'rp',
                            ],
                            [
                                'attribute' => 'harga_proyek',
                                'format' => 'rp',
                            ],
                            [
                                'attribute' => 'stok',
                                'format' => 'text',
                            ],
                            [
                                'attribute' => 'minimal_beli_satuan',
                                'format' => 'text',
                            ],
                            [
                                'attribute' => 'minimal_beli_volume',
                                'format' => 'text',
                            ],
                            [
                                'attribute' => 'deskripsi',
                                'format' => 'ntext',
                            ],
                            [
                                'attribute' => 'gambar',
                                'format' => 'myImage',
                            ],
                            [
                                'attribute' => 'created_at',
                                'format' => 'iddate',
                            ],
                            [
                                'attribute' => 'updated_at',
                                'format' => 'iddate',
                            ],
                            [
                                'attribute' => 'created_by',
                                'format' => 'text',
                            ],
                            [
                                'attribute' => 'updated_by',
                                'format' => 'text',
                            ],
                            [
                                'attribute' => 'deleted_by',
                                'format' => 'text',
                            ],
                            [
                                'class' => yii\grid\DataColumn::className(),
                                'attribute' => 'status',
                                'value' => function ($model) {
                                    if ($model->status == 1) {
                                        return '<span class="badge badge-pill badge-success">Aktif</span>';
                                    } else {
                                        return '<span class="badge badge-pill badge-danger">Nonaktif</span>';
                                    }
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'flag',
                                'format' => 'boolean',
                            ],
                        ],
                    ]); ?>

                    <hr />

                    <?= Html::a(
                        '<span class="glyphicon glyphicon-trash"></span> ' . 'Delete',
                        ['delete', 'id' => $model->id],
                        [
                            'class' => 'btn btn-danger',
                            'data-confirm' => '' . 'Are you sure to delete this item?' . '',
                            'data-method' => 'post',
                        ]
                    ); ?>
                    <?php $this->endBlock(); ?>



                    <?= Tabs::widget(
                        [
                            'id' => 'relation-tabs',
                            'encodeLabels' => false,
                            'items' => [
                                [
                                    'label'   => '<b class=""># ' . $model->id . '</b>',
                                    'content' => $this->blocks['app\models\SupplierBarang'],
                                    'active'  => true,
                                ],
                            ]
                        ]
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>