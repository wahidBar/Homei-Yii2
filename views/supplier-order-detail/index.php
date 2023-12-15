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
 * @var app\models\search\SupplierOrderDetailSearch $searchModel
 */

$this->title = 'Supplier Order Detail';
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

                            [
                                'attribute' => 'kode_unik',
                                'format' => 'text',
                            ],
                            // modified by Defri Indra
                            [
                                'class' => yii\grid\DataColumn::className(),
                                'attribute' => 'supplier_order_id',
                                'value' => function ($model) {
                                    if ($rel = $model->supplierOrder) {
                                        return $rel->id;
                                    } else {
                                        return '';
                                    }
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'kode_order',
                                'format' => 'text',
                            ],
                            // modified by Defri Indra
                            [
                                'class' => yii\grid\DataColumn::className(),
                                'attribute' => 'supplier_barang_id',
                                'value' => function ($model) {
                                    if ($rel = $model->supplierBarang) {
                                        return $rel->id;
                                    } else {
                                        return '';
                                    }
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'jumlah',
                                'format' => 'text',
                            ],
                            [
                                'attribute' => 'total_ppn',
                                'format' => 'text',
                            ],
                            /*[
            'attribute' => 'created_by',
            'format' => 'text',
        ],*/
                            /*[
            'attribute' => 'updated_by',
            'format' => 'iddate',
        ],*/
                            /*[
            'attribute' => 'catatan',
            'format' => 'ntext',
        ],*/
                            /*[
            'attribute' => 'created_at',
            'format' => 'iddate',
        ],*/
                            /*[
            'attribute' => 'updated_at',
            'format' => 'iddate',
        ],*/
                            /*[
            'attribute' => 'voucher',
            'format' => 'text',
        ],*/
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\widgets\Pjax::end() ?>