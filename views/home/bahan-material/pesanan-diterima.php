<?php

use app\components\annex\ActiveForm;
use dmstr\helpers\Html;
use kartik\file\FileInput;
use richardfan\widget\JSRegister;
use yii\helpers\Url;

\app\assets\MapAsset::register($this);
$pengaturan = \app\models\SiteSetting::find()->one();

date_default_timezone_set("Asia/Jakarta");

$to_time = strtotime($model->deadline_bayar);
$from_time = strtotime(date('Y-m-d H:i:s'));
$minute = round(abs($to_time - $from_time) / 60, 2);

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
                <table class="table table-cart">
                    <thead>
                        <tr>
                            <th><?= Yii::t("cruds", "No Nota") ?></th>
                            <th><?= Yii::t("cruds", "Total Bayar") ?></th>
                            <th><?= Yii::t("cruds", "Bukti Pembayaran") ?></th>
                            <!-- <th><?= Yii::t("cruds", "Alamat") ?></th> -->
                            <th><?= Yii::t("cruds", "Invoice") ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $model->no_nota ?></td>
                            <td><?= \app\components\Angka::toReadableHarga($model->total_harga) ?></td>
                            <td>
                                <?php
                                $link =  $model->bukti_bayar;
                                $absolutelink = Yii::getAlias("@file/$link");
                                if (\app\components\Constant::checkFile($link)) {
                                    echo "<a href='$absolutelink' class='btn btn-primary text-white' target='_blank'>Download</a>";
                                } else {
                                    echo "<span  class='badge badge-warning'>Belum Dibayar</span>";
                                }
                                ?>
                            </td>
                            <!-- <td><?= $model->alamat ?></td> -->
                            <td>
                                <?= Html::a('Lihat Invoice', ['cetak-invoice', 'id' => $model->kode_unik], ['class' => 'btn btn-info text-white']); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="table-responsive">
                        <table class="table table-cart">
                            <thead>
                                <tr>
                                    <th><?= Yii::t("cruds", "Barang") ?></th>
                                    <th><?= Yii::t("cruds", "Jumlah") ?></th>
                                    <th><?= Yii::t("cruds", "Subtotal") ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($daftar_barangs as $barang) { ?>
                                    <tr>
                                        <td><?= $barang->supplierBarang->nama_barang ?> </td>
                                        <td><?= $barang->jumlah ?> <?= $barang->supplierBarang->satuan->nama ?> </td>
                                        <td><?= \app\components\Angka::toReadableHarga($barang->subtotal) ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="2" class="font-weight-bold">Total</td>
                                    <td class="font-weight-bold"><?= \app\components\Angka::toReadableHarga($model->total_harga) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
                if ($model->status == 2) {
                ?>
                    <div class="col-lg-6">
                        <div class="container">
                            <?php $form = ActiveForm::begin([
                                'id' => 'SupplierOrder',
                                'layout' => 'horizontal',
                                'enableClientValidation' => true,
                                'errorSummaryCssClass' => 'error-summary alert alert-error'
                            ]);
                            ?>
                            <?php echo $form->errorSummary($model); ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="col-lg-12 col-12 layout-spacing">
                                        <div class="row">
                                            <div class="">
                                                <h4>Upload Foto Penerimaan</h4>
                                                <div class="card m-b-30 pt-2 pb-3">
                                                    <div class="">
                                                        <div class="d-flex  flex-wrap">
                                                            <div class="clearfix"></div>
                                                            <?= $form->field($model, 'bukti_diterima', \app\components\Constant::COLUMN(1))->widget(FileInput::class, [
                                                                'options' => [
                                                                    'preview' => false,
                                                                    'accept' => 'image/*'
                                                                ]
                                                            ]) ?>
                                                            <?= $form->field($model, 'keterangan_diterima', \app\components\Constant::COLUMN(1))->textarea(['rows' => 6]) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="">
                                                    <div class="">
                                                        <div class="row">
                                                            <div class="ml-4 col-md-12 text-left">
                                                                <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']); ?>
                                                                <?= Html::a('<i class="fa fa-chevron-left"></i> Kembali', ['index'], ['class' => 'btn btn-default']) ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <?php ActiveForm::end(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
                ?>
            </div>
        </div>
    </div>
</section>
<!-- End Cart Wrap -->
<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile("@web/homepage/js/cart-input.js", ['position' => \yii\web\View::POS_END]);
?>