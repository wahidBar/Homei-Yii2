<?php

use app\components\annex\ActiveForm;
use dmstr\helpers\Html;
use kartik\file\FileInput;
use yii\grid\GridView;
?>
<?php
$setting = \app\models\SiteSetting::find()->all();
$this->registerCssFile("@web/homepage/vendor/owl-carousel/animate.css");
$this->registerCssFile("@web/homepage/vendor/owl-carousel/owl.carousel.min.css");
$this->registerCssFile("@web/homepage/vendor/owl-carousel/owl.theme.default.min.css");
// $this->registerCssFile("@web/homepage/vendor/revolution/settings.css");
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
                        <li>
                            <a href="<?= \Yii::$app->request->BaseUrl ?>/home/cari-tukang/index">Cari Tukang</a>
                        </li>
                        <span>/</span>
                        <li class="active">
                            <a href="#">Pembayaran Akhir
                            </a>
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
        <div class="row">
            <div class="col-md-6">
                <div class="form-contact-wrap m-t-20">
                    <h4 class="mb-0">Form Pembayaran Akhir</h4>
                    <p class="mb-3"><strong>Biaya yang harus ditransfer</strong> : <?= \Yii::$app->formatter->asRp($model->biaya - $model->nominal_dp) ?></p>
                    <?php $form = ActiveForm::begin([
                        'id' => 'PekerjaanSameday',
                        'layout' => 'horizontal',
                        'enableClientValidation' => true,
                        'errorSummaryCssClass' => 'error-summary alert alert-error'
                    ]);
                    ?>
                    <?php echo $form->errorSummary($model); ?>
                    <div class="row">
                        <?= $form->field($model, 'bukti_pembayaran', \app\components\Constant::COLUMN(1))->widget(FileInput::class, [
                            'options' => [
                                'accept' => 'image/*'
                            ]
                        ]) ?>
                        <?= $form->field($model, 'keterangan_pembayaran', \app\components\Constant::COLUMN(1))->textarea(['rows' => 6]) ?>
                        
                    </div>
                    <div class="col-12 mt-3">
                        <h4>Transfer Pembayaran :</h4>
                        <?php foreach ($pembayarans as $bank) : ?>
                            <p><strong><?= $bank->nama_bank ?></strong> : <?= $bank->nomor_rekening ?> (<?= $bank->atas_nama ?>)</p>
                        <?php endforeach ?>
                        
                    </div>

                    <hr />
                    <div class="text-left ml-4 mb-5">
                        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success m-0']); ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="m-t-20">
                    <h4><?= $model->kategori->nama_kategori_layanan ?></h4>
                    <p><?= $model->kategori->deskripsi ?></p>
                    <img alt="Service 1" src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $model->kategori->icon ?>">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Contact Content -->

<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
?>