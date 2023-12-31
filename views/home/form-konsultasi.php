<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use yii\helpers\Html;
use app\components\annex\ActiveForm;
use \app\components\annex\Tabs;
use app\components\Constant;
use kartik\depdrop\DepDrop;
use richardfan\widget\JSRegister;

/**
 * @var yii\web\View $this
 * @var app\models\IsianLanjutan $model
 * @var app\components\annex\ActiveForm $form
 */

?>
<?php
$setting = \app\models\SiteSetting::find()->all();
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
                            <a href="<?= \Yii::$app->request->BaseUrl ?>/home/formulir-konsultasi">Konsultasi</a>
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
                <div class="m-t-40">
                    <img src="<?= \Yii::$app->request->baseUrl . "/homepage/img/offers.jpg" ?>" alt="">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-contact-wrap m-t-40">
                    <h4>Pilih Konsultan</h4>
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
                            <h4><i class="icon fa fa-check"></i>Saved!</h4>
                            <?= Yii::$app->session->getFlash('error') ?>
                        </div>
                    <?php endif; ?>
                    <?php $form = ActiveForm::begin([
                        'id' => 'Konsultasi',
                        'layout' => 'horizontal',
                        'enableClientValidation' => true,
                        'errorSummaryCssClass' => 'error-summary alert alert-error',
                        'enableClientScript' => false,
                    ]);
                    ?>
                    <?php echo $form->errorSummary($model); ?>

                    <div class="row">
                        <!-- <div class="col-md-12 col-12"> -->

                        <?php
                        // $id = $_GET['id'];
                        // $isian = app\models\IsianLanjutan::find()->where(['id' => $id])->one();
                        // echo
                        // $form->field($model, 'id_isian_lanjutan', [
                        //     'template' => '
                        //             {label}
                        //             {input}
                        //             {error}
                        //         ',
                        //     'inputOptions' => [
                        //         'class' => 'form-control'
                        //     ],
                        //     'labelOptions' => [
                        //         'class' => 'control-label'
                        //     ],
                        //     'options' => ['tag' => false]
                        // ])->textInput(['disabled' => true, 'value' => $isian->label]);
                        ?>
                        <!-- </div> -->
                        <div class="col-md-12 col-12">
                            <?=
                            $form->field($model, 'id_konsultan', [
                                'template' => '
                                        {label}
                                        {input}
                                        {error}
                                    ',
                                'inputOptions' => [
                                    'class' => 'form-control'
                                ],
                                'labelOptions' => [
                                    'class' => 'control-label'
                                ],
                                'options' => ['tag' => false]
                            ])->widget(\kartik\select2\Select2::classname(), [
                                'name' => 'class_name',
                                'model' => $model,
                                'attribute' => 'id_konsultan',
                                'data' => \yii\helpers\ArrayHelper::map(\app\models\User::find()->where(['is_active' => 1, 'role_id' => 4])->all(), 'id', 'name'),
                                'options' => [
                                    'placeholder' => $model->getAttributeLabel('id_konsultan'),
                                    'multiple' => false,
                                    'disabled' => (isset($relAttributes) && isset($relAttributes['id_konsultan'])),
                                ]
                            ]); ?>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-md-offset-3 col-md-10">
                        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']); ?>
                        <?= Html::a('<i class="fa fa-chevron-left"></i> Kembali', ['index'], ['class' => 'btn btn-default']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    </div>
</section>
<!-- End Contact Content -->


<?php JSRegister::begin(); ?>
<script>
    $(document).ready(function() {
        $("#id_provinsi").select2({
            ajax: {
                url: '<?= \Yii::$app->request->BaseUrl ?>/home/getdataprov',
                type: "post",
                dataType: 'json',
                delay: 200,
                data: function(params) {
                    return {
                        searchTerm: params.term
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
    });

    // Kabupaten
    $("#id_provinsi").change(function() {
        var id_prov = $("#id_provinsi").val();
        $("#id_kota").select2({
            ajax: {
                url: '<?= \Yii::$app->request->BaseUrl ?>/home/getdatakab?id_prov=' + id_prov,
                type: "post",
                dataType: 'json',
                delay: 200,
                data: function(params) {
                    return {
                        searchTerm: params.term
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
    });
</script>
<?php JSRegister::end(); ?>
<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
?>