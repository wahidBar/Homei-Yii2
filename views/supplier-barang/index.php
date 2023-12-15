<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\SupplierBarangSearch $searchModel
 */

$this->title = 'Supplier Barang';
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= Html::a('<i class="fa fa-plus"></i> Tambah Baru', ['create'], ['class' => 'btn btn-success']) ?>
</p>


<?php \yii\widgets\Pjax::begin(['id' => 'pjax-main', 'enableReplaceState' => false, 'linkSelector' => '#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("yo")}']]) ?>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <?= GridView::widget([
                        'layout' => '{summary}{pager}{items}{pager}',
                        'dataProvider' => $dataProvider,
                        'pager'        => [
                            'class'          => app\components\annex\LinkPager::className(),
                            'firstPageLabel' => 'First',
                            'lastPageLabel'  => 'Last'
                        ],
                        'filterModel' => $searchModel,
                        'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                        'headerRowOptions' => ['class' => 'x'],
                        'columns' => [

                            \app\components\ActionButton::getButtons(),

                            // modified by Defri Indra
                            [
                                'class' => yii\grid\DataColumn::className(),
                                'attribute' => 'supplier_id',
                                'value' => function ($model) {
                                    if ($rel = $model->supplierSubMaterial) {
                                        return $rel->nama;
                                    } else {
                                        return '';
                                    }
                                },
                                'format' => 'raw',
                            ],
                            [
                                'class' => yii\grid\DataColumn::className(),
                                'attribute' => 'supplier_id',
                                'value' => function ($model) {
                                    if ($rel = $model->supplier) {
                                        return $rel->nama_supplier;
                                    } else {
                                        return '';
                                    }
                                },
                                'format' => 'raw',
                            ],
                            // [
                            //     'attribute' => 'gambar',
                            //     'format' => 'myImage',
                            // ],
                            [
                                'attribute' => 'nama_barang',
                                'format' => 'text',
                            ],
                            [
                                'attribute' => 'stok',
                                'format' => 'text',
                            ],
                            [
                                'class' => yii\grid\DataColumn::className(),
                                'attribute' => 'satuan_id',
                                'value' => function ($model) {
                                    if ($rel = $model->satuan) {
                                        return $rel->nama;
                                    } else {
                                        return '';
                                    }
                                },
                                'format' => 'raw',
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
                            // [
                            //     'attribute' => 'minimal_beli_satuan',
                            //     'format' => 'text',
                            // ],
                            // [
                            //     'attribute' => 'minimal_beli_volume',
                            //     'format' => 'text',
                            // ],
                            /*[
            'attribute' => 'created_by',
            'format' => 'text',
        ],*/
                            /*[
            'attribute' => 'updated_by',
            'format' => 'iddate',
        ],*/
                            /*[
            'attribute' => 'deleted_by',
            'format' => 'text',
        ],*/
                            /*[
            'attribute' => 'status',
            'format' => 'text',
        ],*/
                            /*[
            'attribute' => 'flag',
            'format' => 'boolean',
        ],*/
                            /*[
            'attribute' => 'created_at',
            'format' => 'iddate',
        ],*/
                            /*[
            'attribute' => 'updated_at',
            'format' => 'iddate',
        ],*/
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\widgets\Pjax::end() ?>