<?php

use app\components\annex\ActiveForm;
use kartik\file\FileInput;
use dmstr\helpers\Html;
use richardfan\widget\JSRegister;
use yii\helpers\Url;

$setting = \app\models\SiteSetting::find()->all();
$this->registerCssFile("@web/homepage/css/sweetalert2.min.css");
$this->registerCss("

.overlay {
    position:absolute;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
}
");
?>
<!-- Navigation -->
<section class="navigation">
    <div class="parallax parallax--nav" style="background-image: url(<?= \Yii::$app->request->baseUrl . "/uploads/" . $setting[0]['gambar_header'] ?>);">
        <div class="overlay"></div>
        <div class="container clearfix">
            <div class="row">
                <div class="col-12">
                    <h2>
                        <?= $setting[0]['tagline']; ?>
                    </h2>
                </div>
                <div class="col-12">
                    <p>
                        <?= $setting[0]['tagline2']; ?>
                    </p>
                </div>
                <div class="col-12">
                    <ul class="breadcrumbs ul--inline ul--no-style">
                        <li>
                            <a href="<?= \Yii::$app->request->BaseUrl ?>/home">Home</a>
                        </li>
                        <span>/</span>
                        <li class="active">
                            <a href="<?= \Yii::$app->request->BaseUrl ?>/home/proyek-saya/index">Proyek Saya</a>
                        </li>
                        <span>/</span>
                        <li class="active">
                            <a href="#">Pembayaran</a>
                        </li>
                        <span>/</span>
                        <li class="active">
                            <a href="#">Bayar Termin</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Navigation -->
<!-- Contact content -->
<section class="contact-content">
    <div class="container">
        <?php $form = ActiveForm::begin([
            'id' => 'ProyekTermin',
            'layout' => 'horizontal',
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]);
        ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="row">
            <div class="col-md-12">
                <h2 class="title title-3 title--dark">
                    Upload Bukti Pembayaran
                </h2>
            </div>
            <div class="col-lg-3 col-md-3 pb-3">
                <div class="list-group" style="border-style: solid;border-color: #ebcd1e;border-radius: 5px;">
                    <p class="text-center text-dark" style="font-size: 2rem;background-color: #ebcd1e">Menu</p>
                    <?php
                    $current_url = Url::current();
                    $target = \Yii::$app->request->BaseUrl . "/home/proyek-saya/detail-proyek";
                    if (stripos($current_url, $target) !== false) {
                        $link_dashboard = " link-active";
                    } ?>
                    <?php
                    $current_url = Url::current();
                    $target = \Yii::$app->request->BaseUrl . "/home/proyek-saya/keuangan";
                    if (stripos($current_url, $target) !== false) {
                        $link_uang = " link-active";
                    } ?>
                    <?php
                    $current_url = Url::current();
                    $target = \Yii::$app->request->BaseUrl . "/home/proyek-saya/pantau-proyek";
                    if (stripos($current_url, $target) !== false) {
                        $link_cctv = " link-active";
                    } ?>
                    <?php
                    $current_url = Url::current();
                    $target = \Yii::$app->request->BaseUrl . "/home/proyek-saya/bayar-cicilan";
                    if (stripos($current_url, $target) !== false) {
                        $link_bayar = " link-active";
                    } ?>
                    <?= Html::a('Dashboard', ['detail-proyek', 'id' => $model->kode_proyek], ['class' => 'list-group-item list-group-item-action' . $link_dashboard]) ?>
                    <?= Html::a('Keuangan', ['keuangan', 'id' => $model->kode_proyek], ['class' => 'list-group-item list-group-item-action' . $link_uang]) ?>
                    <?= Html::a('Pantau Proyek', ['pantau-proyek', 'id' => $model->kode_proyek], ['class' => 'list-group-item list-group-item-action' . $link_cctv]) ?>
                    <?= Html::a('Pembayaran', ['pembayaran', 'id' => $model->kode_proyek], ['class' => 'list-group-item list-group-item-action' . $link_bayar]) ?>
                </div>
            </div>
            <div class="col-lg-9 col-md-9">
                <div class="col-lg-12 col-12 layout-spacing">
                    <!-- display success message -->
                    <?php if (Yii::$app->session->hasFlash('success')) : ?>
                        <div class="alert alert-success alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <p><i class="icon fa fa-check"></i>Saved!</p>
                            <?= Yii::$app->session->getFlash('success') ?>
                        </div>
                    <?php endif; ?>

                    <!-- display error message -->
                    <?php if (Yii::$app->session->hasFlash('error')) : ?>
                        <div class="alert alert-danger alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <h4><i class="icon fa fa-check"></i>Error!</h4>
                            <?= Yii::$app->session->getFlash('error') ?>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="">
                            <h4 class="text-center mt-2 mb-2">Upload Pembayaran <?= $model->termin ?></h4>
                            <div class="m-b-30">
                                <div class="">
                                    <div class="d-flex  flex-wrap">
                                        <div class="clearfix"></div>
                                        <?= $form->field($model, 'bukti_pembayaran', \app\components\Constant::COLUMN(1))->widget(FileInput::class, [
                                            'options' => [
                                                // 'accept' => 'image/*'
                                            ]
                                        ]) ?>
                                        <?= $form->field($model, 'keterangan_pembayaran', \app\components\Constant::COLUMN(1))->textarea(['rows' => 6]) ?>
                                        <div class="col-12 mt-3">
                                            <div class="col-12">
                                                <h4>Transfer Pembayaran :</h4>
                                                <?php foreach ($pembayarans as $bank) : ?>
                                                    <p><strong><?= $bank->nama_bank ?></strong> : <?= $bank->nomor_rekening ?> (<?= $bank->atas_nama ?>)</p>
                                                <?php endforeach ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card m-b-30">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 text-left ml-4">
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
</section>