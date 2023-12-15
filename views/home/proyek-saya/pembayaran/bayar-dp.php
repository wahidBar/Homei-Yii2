<?php

use app\components\annex\ActiveForm;
use kartik\file\FileInput;
use richardfan\widget\JSRegister;
use yii\helpers\Html;

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
                            <a href="#">Bayar DP</a>
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
            'id' => 'Proyek',
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
                <?= $this->render('../_sidemenu', compact('model')) ?>
            </div>
            <div class="col-lg-9 col-md-9">
                <div class="col-lg-12 col-12 layout-spacing">                    
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Upload Pembayaran DP</h4>
                            <div class="card m-b-30">
                                <div class="card-body">
                                    <div class="d-flex  flex-wrap">
                                        <div class="clearfix"></div>
                                        <?= $form->field($model, 'bukti_pembayaran', \app\components\Constant::COLUMN(1))->widget(FileInput::class, [
                                            'options' => [
                                                // 'accept' => 'image/*'
                                            ]
                                        ]) ?>
                                        <?= $form->field($model, 'keterangan_pembayaran', \app\components\Constant::COLUMN(1))->textarea(['rows' => 6]) ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card m-b-30">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-10">
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
