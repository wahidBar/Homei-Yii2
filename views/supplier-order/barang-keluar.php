<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */
use app\components\annex\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\SupplierOrderSearch $searchModel
 */

$this->title = 'Barang Keluar';
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <!-- <?= Html::a('<i class="fa fa-plus"></i> Tambah Baru', ['create'], ['class' => 'btn btn-success']) ?> -->
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
                            // modified by Defri Indra
                            [
                                'class' => yii\grid\DataColumn::className(),
                                'attribute' => 'user_id',
                                'value' => function ($model) {
                                    if ($rel = $model->user) {
                                        return $rel->name;
                                    } else {
                                        return '';
                                    }
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'barang',
                                'format' => 'text',
                                'value' => function ($model) {
                                    if ($rel = $model->supplierOrderDetail) {
                                        return $rel->supplierBarang->nama_barang;
                                    } else {
                                        return '';
                                    }
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'jumlah',
                                'format' => 'text',
                                'value' => 'supplierOrderDetail.jumlah'
                            ],
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if ($model->status == 0) :
                                        return "Belum Bayar";
                                    elseif ($model->status == 1) :
                                        return "Bayar (pengecekan)";
                                    elseif ($model->status == 2) :
                                        return "Lunas";
                                    elseif ($model->status == 3) :
                                        return "Pembayaran ditolak";
                                    endif;
                                }
                            ],
                            [
                                'attribute' => 'created_at',
                                'format' => 'iddate',
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\widgets\Pjax::end() ?>