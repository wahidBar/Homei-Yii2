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
* @var app\models\BarangMasuk $model
*/

$this->title = 'Barang Masuk : ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Barang Masuk', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';
?>
<div class="giiant-crud barang-masuk-view">

    <!-- menu buttons -->
    <p class='pull-left'>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . 'Edit', ['update', 'id' => $model->id],['class' => 'btn btn-info']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'Tambah Baru', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <p class="pull-right">
        <?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . 'Daftar Barang Masuk', ['index'], ['class'=>'btn btn-default']) ?>
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
                    <?php $this->beginBlock('app\models\BarangMasuk'); ?>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
					                    // modified by Defri Indra
                    [
                        'format' => 'html',
                        'attribute' => 'id_master_gudang',
                        'value' => ($model->masterGudang ? $model->masterGudang->nama : '<span class="label label-warning">?</span>'),
                    ],
					                    // modified by Defri Indra
                    [
                        'format' => 'html',
                        'attribute' => 'id_supplier_barang',
                        'value' => ($model->supplierBarang ? $model->supplierBarang->id : '<span class="label label-warning">?</span>'),
                    ],
					        [
            'attribute' => 'no_po',
            'format' => 'text',
        ],
					        [
            'attribute' => 'jumlah',
            'format' => 'text',
        ],
					        [
            'attribute' => 'keterangan',
            'format' => 'ntext',
        ],
					                    // modified by Defri Indra
                    [
                        'format' => 'html',
                        'attribute' => 'created_by',
                        'value' => ($model->createdBy ? $model->createdBy->name : '<span class="label label-warning">?</span>'),
                    ],
					                    // modified by Defri Indra
                    [
                        'format' => 'html',
                        'attribute' => 'updated_by',
                        'value' => ($model->updatedBy ? $model->updatedBy->name : '<span class="label label-warning">?</span>'),
                    ],
					                    // modified by Defri Indra
                    [
                        'format' => 'html',
                        'attribute' => 'deleted_by',
                        'value' => ($model->deletedBy ? $model->deletedBy->name : '<span class="label label-warning">?</span>'),
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
            'attribute' => 'deleted_at',
            'format' => 'iddate',
        ],
					        [
            'attribute' => 'flag',
            'format' => 'boolean',
        ],
                        ],
                    ]); ?>

                    <hr/>

                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ' . 'Delete', ['delete', 'id' => $model->id],
                    [
                    'class' => 'btn btn-danger',
                    'data-confirm' => '' . 'Are you sure to delete this item?' . '',
                    'data-method' => 'post',
                    ]); ?>
                    <?php $this->endBlock(); ?>


                    
                    <?= Tabs::widget(
                    [
                        'id' => 'relation-tabs',
                        'encodeLabels' => false,
                        'items' => [ 
                                                [
                        'label'   => '<b class=""># '.$model->id.'</b>',
                        'content' => $this->blocks['app\models\BarangMasuk'],
                        'active'  => true,
                    ],
                        ]
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
