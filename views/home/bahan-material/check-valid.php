<?php

use app\components\annex\ActiveForm;
use dmstr\helpers\Html;
use kartik\file\FileInput;
use richardfan\widget\JSRegister;
use yii\helpers\Url;
use app\components\annex\Modal;
use app\components\Tanggal;
use yii\grid\GridView;

date_default_timezone_set("Asia/Jakarta");

Modal::begin([
    'id' => 'modal',
    'header' => '<h3>Detail Order</h3>',
    'size' => 'modal-lg'
]);
echo "<div id='modalContent'></div>";
Modal::end();
?>


<!-- Cart Wrap -->
<section class="cart-wrap">
    <div class=" section-content section-content--w1140">
        <h2 class="text-center">CEK KEASLIAN DOKUMEN</h2>
        <div class="container text-center" style="min-height: 40vh;">
            <?php if ($model) : ?>
                <table class="table">
                    <tr>
                        <th>NO NOTA</th>
                        <td class="text-left"><?= $model->no_nota ?></td>
                    </tr>
                    <tr>
                        <th>Nama Penerima</th>
                        <td class="text-left"><?= $model->nama_penerima ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Pemesanan</th>
                        <td class="text-left"><?= Tanggal::toReadableDate($model->created_at, false) ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td class="text-left"><?= $model->getStatuses()[$model->status] ?></td>
                    </tr>
                    <tr>
                        <th>Detail Pesanan</th>
                        <td class="text-left"><?=
                                                Html::button('Detail Order', ['value' => Url::to(['detail-pesanan', 'id' => $model->kode_unik]), 'class' => 'btn btn-sm btn-success modalButton', 'id' => 'modalButton']);
                                                ?></td>
                    </tr>
                </table>
            <?php else : ?>
                <table class="table">
                    <tr>
                        <td>Data Tidak Ditemukan</td>
                    </tr>
                </table>
            <?php endif ?>
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
JS;

$this->registerJs($script);
?>