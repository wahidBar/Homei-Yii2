<?php

use app\components\annex\ActiveForm;
use richardfan\widget\JSRegister;
use yii\helpers\Html;
use yii\grid\GridView;
?>
<?php
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
            </div>
        </div>
    </div>
</section>
<!-- End Navigation -->
<div class="widget-header mt-2">
    <div class="row">
        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
            <h2 class="text-center">Formulir Rencana Pembangunan</h2>
        </div>
    </div>
</div>
<!-- Contact content -->
<section class="contact-content">
    <div class="container">
        <?php $form = ActiveForm::begin([
            'id' => 'IsianLanjutan',
            'layout' => 'horizontal',
            'enableClientValidation' => false,
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]);
        ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="d-flex  flex-wrap">
                            <?= $this->render('_form_umum', compact('form', 'model')) ?>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="d-flex  flex-wrap">
                            <?= $this->render('_form_wilayah', compact('form', 'model')) ?>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="d-flex  flex-wrap">
                            <?= $this->render('_form_ruangan', compact('form', 'model')) ?>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="d-flex  flex-wrap">
                            <?= $this->render('_form_isian', compact('form', 'model')) ?>
                            <div class="clearfix"></div>
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
</section>