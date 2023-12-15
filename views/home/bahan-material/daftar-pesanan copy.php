<?php

use app\components\annex\ActiveForm;
use dmstr\helpers\Html;
use kartik\file\FileInput;
use richardfan\widget\JSRegister;
use yii\helpers\Url;
use app\components\annex\Modal;
use yii\grid\GridView;

\app\assets\MapAsset::register($this);
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
    'size' => 'modal-xl'
]);
echo "<div id='modalContent2'></div>";
Modal::end();
?>
<style>
    #map_canvas {
        width: 100%;
        height: 70vh;
        margin-bottom: 1rem;
        border-radius: 20px;
        box-shadow: 0 8px 4px 5px #eee;
    }
</style>


<!-- Breadcrumb -->
<section class="breadcrumbs-wrap">
    <div class=" section-content section-content--w1140">
        <div class="container clearfix">
            <div class="link-back">
                <a href="<?=
                            Url::to([
                                "/home/bahan-material/index",
                            ])
                            ?>" class="au-btn au-btn--pill au-btn--small au-btn--yellow" style="margin-top:25px">
                    <?= Yii::t("cruds", "Kembali") ?>
                </a>
            </div>
        </div>
    </div>
</section>
<!-- End Breadcrumb -->
<!-- Cart Wrap -->
<section class="cart-wrap">
    <div class=" section-content section-content--w1140">
        <div class="container">
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
                        [
                            'attribute' => 'Detail Order',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return
                                    Html::button('Detail Order', ['value' => Url::to(['detail-pesanan','id' => $model->kode_unik]), 'class' => 'btn btn-success modalButton', 'id' => 'modalButton']);
                            }
                        ],
                        [
                            'attribute' => 'Info Pembangunan',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if ($model->kode_isian_lanjutan) :
                                    return
                                        Html::button('Info Pembangunan', ['value' => Url::to(['detail-boq', 'id' => $model->kode_isian_lanjutan]), 'class' => 'btn btn-success modalButton2', 'id' => 'modalButton2']);
                                else :
                                    return "-";
                                endif;
                            }
                        ],
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
                            'attribute' => 'no_nota',
                            'format' => 'text',
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
        // console.log($(this).attr('value'));
		$('#modal').modal('show')
			.find('#modalContent')
			.load($(this).attr('value'));
	});
});

$(function(){
    
	$('.modalButton2').click(function (){
        console.log($(this).attr('value'));
		$('#modal2').modal('show')
			.find('#modalContent2')
			.load($(this).attr('value'));
	});
});
JS;

$this->registerJs($script);
?>