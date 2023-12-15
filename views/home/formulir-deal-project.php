<?php

use app\components\annex\ActiveForm;
use richardfan\widget\JSRegister;
use kartik\depdrop\DepDrop;
use yii\db\Query;
use yii\helpers\Url;

$galleries = \app\models\Galeri::find()->limit(10)->all();
$hp_galleries = \app\models\Galeri::find()->limit(4)->all();

$this->registerCssFile("@web/homepage/css/sweetalert2.min.css");
$this->registerCss("
main {
	margin: 10vh auto;
	max-width: 90%;
	display: grid;
	grid-gap: 5px;
	grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
	grid-auto-rows: 250px;
	grid-auto-flow: dense;
}

.style {
	text-align: center;
	padding: 1rem 0;
	color: white;
	text-transform: uppercase;
	background: rgba(0,0,0,.2);
	overflow: hidden;
	padding: 0;
	display: flex;
	align-items: stretch;
	justify-content: center;
    position: relative;
}

.style img {
	width: 100%;
	height: 100%;
	display: block;
	-o-object-fit: cover;
	   object-fit: cover;
	-o-object-position: center;
	   object-position: center;
	transition: all .5s;
}

.text-img {
    position: absolute;
    bottom: 8px;
    left: 16px;    
}

.text-img h2{
    font-size : 2rem;
    color: #fff;
}

.horizontal {
	grid-column: span 2;
}

.vertical {
	grid-row: span 2;
}

.big {
	grid-column: span 2;
	grid-row: span 2;
}
.overlay {
    position:absolute;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
                            

}
");
?>

<?php
$setting = \app\models\SiteSetting::find()->all();
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
                            <a href="<?= \Yii::$app->request->BaseUrl ?>/formulir-deal-project">Formulir Deal Proyek</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Navigation -->
<div class="container pt-5 pb-5">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header text-center" style="background-color: #F8D20A; border-bottom:0px;">
                    <h2 class="text-white">
                        Mulai Kesuksesan Anda Disini!
                    </h2>
                </div>
                <div class="card-body" style="border: 6px solid rgb(248 210 10);">
                    <div class="form-contact-wrap">
                        <?php echo $form->errorSummary($model); ?>
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <label for="" class="cmt-title">Kode Penawaran</label>
                                <?php
                                $penawaran = app\models\Penawaran::find()->where(['id' => $_GET['id']])->one();
                                echo $form->field($model, 'id_penawaran', [
                                    'template' => '
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
                                ])->textInput(['disabled' => true, 'value' => $penawaran->kode_penawaran]);
                                ?>
                            </div>
                            <div class="col-md-12 col-12">
                                <label for="" class="cmt-title">Kontraktor</label>
                                <?= $form->field($model, 'id_kontraktor', [
                                    'template' => '
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
                                    'attribute' => 'id_kontraktor',
                                    'data' => \yii\helpers\ArrayHelper::map(app\models\Kontraktor::find()->all(), 'id', 'nama_kontraktor'),
                                    'options' => [
                                        'placeholder' => $model->getAttributeLabel('id_kontraktor'),
                                        'multiple' => false,
                                        'disabled' => (isset($relAttributes) && isset($relAttributes['id_kontraktor'])),
                                    ]
                                ]); ?>
                            </div>
                            <div class="col-md-12 col-12">
                                <label for="" class="cmt-title">Nama Pelanggan</label>
                                <?php
                                echo $form->field($model, 'nama_pelanggan', [
                                    'template' => '
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
                                ])->textInput(['disabled' => true, 'value' => \Yii::$app->user->identity->name]);
                                ?>
                            </div>
                            <div class="col-md-12 col-12">
                                <label for="" class="cmt-title">Alamat Pelanggan</label>
                                <?= $form->field($model, 'alamat_pelanggan', [
                                    'template' => '
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
                                ])->textarea(['rows' => 6]) ?>
                            </div>
                            <div class="col-md-12 col-12">
                                <label for="" class="cmt-title">Alamat Proyek</label>
                                <?= $form->field($model, 'alamat_proyek', [
                                    'template' => '
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
                                ])->textarea(['rows' => 6]) ?>
                            </div>
                        </div>
                        <hr />
                        <div class="text-center">
                            <button type="submit" class="au-btn au-btn--pill au-btn--yellow au-btn--big">Submit</button>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 d-none d-lg-block">
            <h2 class="title-3 title-3--left">
                Anda Membayangkannya, Kami Mewujudkannya!
            </h2>
            <p style="font-size:1rem; font-weight:600;color:#000">Beritahu kami apa yang Anda butuhkan dan Home I akan mengurus semuanya.</p>
            <div class="row">
                <?php
                $tabs = \app\models\TabHome::find()->all();
                foreach ($tabs as $tab) {
                ?>
                    <div class="col-lg-6 col-md-6">
                        <img src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $tab->gambar ?>" alt="" class="img-fluid" width="200px">
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <label for="" class="cmt-title"><?= $tab->judul ?></label>
                        <p><?= $tab->isi ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- gallery -->
<div class="container pt-4">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
            <h2 class="title title-3 title-3--left">
                Galeri Kami
            </h2>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12 col-12">
            <p>Kami menerjemahkan visi Anda menjadi kenyataan melalui kerangka kerja kreatif kami. Kami menciptakan desain estetis dan fungsional yang disesuaikan dengan semua kebutuhan dan prefensi Anda yang bertujuan untuk menciptakan kenyamanan dan menginspirasi kreativitas.</p>
        </div>
    </div>
    <div class="d-none d-md-block">
        <main class="pb-5">
            <?php foreach ($galleries as $gal) { ?>
                <?php
                if ($gal->style == "square") {
                    $style = "";
                } else {
                    $style = $gal->style;
                }
                ?>
                <div class="style <?= $style ?> "><img src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $gal->gambar ?>" alt="">
                    <div class="overlay"></div>
                    <div class="text-img">
                        <h2>
                            <?= $gal->judul ?>
                        </h2>
                        <p class="text-left">
                            <?= $gal->keterangan ?>
                        </p>
                    </div>
                </div>
            <?php } ?>
        </main>
    </div>
    <div class="d-block d-sm-none d-sm-block d-md-none">
        <main class="pb-5">
            <?php foreach ($hp_galleries as $gal) { ?>
                <div class="style"><img src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $gal->gambar ?>" alt="">
                    <div class="overlay"></div>
                    <div class="text-img">
                        <h2>
                            <?= $gal->judul ?>
                        </h2>
                        <p class="text-left">
                            <?= $gal->keterangan ?>
                        </p>
                    </div>
                </div>
            <?php } ?>
        </main>
    </div>
</div>
<!-- end of gallery -->