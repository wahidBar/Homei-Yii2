<?php

use app\components\annex\ActiveForm;
use dmstr\helpers\Html;
use kartik\file\FileInput;
use richardfan\widget\JSRegister;
use yii\helpers\Url;
use app\components\annex\Modal;
use yii\grid\GridView;

date_default_timezone_set("Asia/Jakarta");
Modal::begin([
    'id' => 'modal',
    'header' => '<h3>Detail Order</h3>',
    'size' => 'modal-lg'
]);
echo "<div id='modalContent'></div>";
Modal::end();

Modal::begin([
    'id' => 'modal2',
    'header' => '<h3>Detail BOQ</h3>',
    'size' => 'modal-lg'
]);
echo "<div id='modalContent2'></div>";
Modal::end();
?>


<!-- Cart Wrap -->
<section class="cart-wrap">
    <div class=" section-content section-content--w1140">
        <div class="container">

            <div class="daftar-pesanan-search">
                <?php $form = ActiveForm::begin([
                    'method' => 'get',
                ]); ?>
                <div class="input-group mb-3">
                    <!-- <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2"> -->
                    <?= $form->field($searchModel, 'search', [
                        'template' => '
                        {input}
                        {error}
                    ',
                        'inputOptions' => [
                            'class' => 'form-control'
                        ],
                        'options' => ['tag' => false]
                    ])->textInput(['maxlength' => true]) ?>
                    <div class="input-group-append">
                        <?= Html::submitButton('Cari', ['class' => 'btn btn-primary rounded-0 rounded-right']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="table-responsive">
                <?= GridView::widget([
                    'layout' => '{summary}{pager}{items}{pager}',
                    'dataProvider' => $dataProvider,
                    'pager'        => [
                        'class'          => app\components\annex\LinkPager::className(),
                        'firstPageLabel' => 'First',
                        'lastPageLabel'  => 'Last'
                    ],
                    // 'filterModel' => $searchModel,
                    'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                    'headerRowOptions' => ['class' => 'x'],
                    'columns' => [
                        [
                            'attribute' => 'no_nota',
                            'contentOptions' => ['class' => 'text-center'],
                            'headerOptions' => ['class' => 'text-center'],
                            'format' => 'text',
                        ],
                        [
                            'attribute' => 'Total Harga',
                            'contentOptions' => ['class' => 'text-left'],
                            'headerOptions' => ['class' => 'text-left'],
                            'format' => 'raw',
                            'value' => function ($model) {
                                return \app\components\Angka::toReadableHarga($model->total_harga);
                            }
                        ],
                        [
                            'attribute' => 'deadline_bayar',
                            'contentOptions' => ['class' => 'text-center'],
                            'headerOptions' => ['class' => 'text-center'],
                            'format' => 'iddate',
                        ],
                        [
                            'attribute' => 'created_at',
                            'contentOptions' => ['class' => 'text-center'],
                            'headerOptions' => ['class' => 'text-center'],
                            'format' => 'iddate',
                        ],
                        [
                            'attribute' => 'Detail Order',
                            'contentOptions' => ['class' => 'text-center'],
                            'headerOptions' => ['class' => 'text-center'],
                            'format' => 'raw',
                            'value' => function ($model) {
                                return
                                    Html::button('Detail Order', ['value' => Url::to(['detail-pesanan', 'id' => $model->kode_unik]), 'class' => 'btn btn-sm btn-success modalButton', 'id' => 'modalButton']);
                            }
                        ],
                        [
                            'attribute' => 'Invoice',
                            'contentOptions' => ['class' => 'text-center'],
                            'headerOptions' => ['class' => 'text-center'],
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Html::a('Lihat Invoice', ['cetak-invoice', 'id' => $model->kode_unik], ['class' => 'btn btn-sm btn-info text-white']);
                            }
                        ],
                        [
                            'attribute' => 'status',
                            'contentOptions' => ['class' => 'text-center'],
                            'headerOptions' => ['class' => 'text-center'],
                            'format' => 'raw',
                            'value' => function ($model) {
                                if (($model->status == $model::STATUS_BELUM_BAYAR || $model->status == $model::STATUS_PEMBAYARAN_DIBATALKAN) && time() > strtotime($model->deadline_bayar)) {
                                    $model->status = $model::STATUS_PEMBAYARAN_EXPIRED;
                                    $model->save();
                                }

                                if ($model->status == $model::STATUS_BELUM_BAYAR) :
                                    return Html::a('Bayar', ['pembayaran', 'id' => $model->kode_unik], ['class' => 'btn btn-sm btn-warning text-center']);
                                elseif ($model->status == $model::STATUS_MENUNGGU_KONFIRMASI_ADMIN) :
                                    return "<span  class='badge badge-info'>Dalam Pengecekan</span>";
                                elseif ($model->status == 2) :
                                    return Html::a('Cek Pengiriman', ['proses-pengiriman', 'id' => $model->kode_unik], ['class' => 'btn btn-sm btn-info text-white']);
                                elseif ($model->status == $model::STATUS_PEMBAYARAN_DIBATALKAN) :
                                    return Html::a('Ditolak (Bayar Ulang)', ['pembayaran', 'id' => $model->kode_unik], ['class' => 'btn btn-sm btn-danger text-white']);
                                elseif ($model->status == $model::STATUS_PEMBAYARAN_EXPIRED) :
                                    return "<span  class='badge badge-danger'>Pembayaran Kadaluarsa</span>";
                                elseif ($model->status == 4) :
                                    return "<span  class='badge badge-success'>Pesanan Diterima</span>";
                                endif;
                            }
                        ],
                        // [
                        //     'attribute' => 'Info Pembangunan',
                        //     'contentOptions' => ['class' => 'text-center'],
                        //     'headerOptions' => ['class' => 'text-center'],
                        //     'format' => 'raw',
                        //     'value' => function ($model) {
                        //         if ($model->kode_isian_lanjutan) :
                        //             return
                        //                 Html::button('Info Pembangunan', ['value' => Url::to(['detail-boq', 'id' => $model->kode_isian_lanjutan]), 'class' => 'btn btn-sm btn-success modalButton2', 'id' => 'modalButton2']);
                        //         else :
                        //             return "-";
                        //         endif;
                        //     }
                        // ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</section>
<!-- End Cart Wrap -->
<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile("@web/homepage/js/cart-input.js", ['position' => \yii\web\View::POS_END]);
?>
<?php
$script = <<<JS
$(function(){
    
	$('.modalButton').click(function (){
		$('#modal').modal('show')
			.find('#modalContent')
			.load($(this).attr('value'));
	});
});

$(function(){
    
	$('.modalButton2').click(function (){
		$('#modal2').modal('show')
			.find('#modalContent2')
			.load($(this).attr('value'));
	});
});
JS;

$this->registerJs($script);
?>