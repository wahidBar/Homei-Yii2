<?php
$this->registerCssFile("@web/homepage/vendor/lightbox2/src/css/lightbox.css");
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
                            <a href="#">Detail Portofolio</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Navigation -->
<!-- Portfolio 1 -->
<section class="port1">
    <div class="container">
        <div class="port1-wrap">
            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <?php
                    $gambars = app\models\PortofolioGambar::find()->where(['portofolio_id' => $model->id])->all();
                    ?>
                    <div class="port1__big-img">
                        <img alt="Portfolio 1" src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $gambars[0]['gambar_design']; ?>">
                    </div>
                    <div class="port1__img-wrap">
                        <?php for ($i = 0; $i < 3; $i++) { ?>
                            <div class="port1-img">
                                <a href="<?= \Yii::$app->request->baseUrl . "/uploads/" . $gambars[$i]->gambar_design ?>" data-lightbox="portfolio">
                                    <img alt="Portfolio Small 1" src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $gambars[$i]->gambar_design ?>" class="img-project">
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="port__text">
                        <h3>Project Detail</h3>
                        <p class="m-b-20">
                        <div style="font-size: 1rem;font-weight:bold;">
                            Mengenai Proyek Ini
                        </div>
                        <?= $model->tentang_proyek ?>
                        </p>
                        <p>
                        <div class="mt-3" style="font-size: 1rem;font-weight:bold;">
                            Timeline Proyek
                        </div>
                        <?= $model->timeline_proyek ?>
                        </p>
                    </div>
                    <div class="port__info">
                        <ul class="port__info-list clearfix ul--no-style">
                            <li>
                                <span class="port__info-title">Luas</span>
                                <span class="port__info-value"><?= $model->luas ?>m<sup>2</sup></span>
                            </li>
                            <li>
                                <span class="port__info-title">Design Rumah</span>
                                <span class="port__info-value"><?= $model->konsepDesain->nama_konsep ?></span>
                            </li>
                            <li>
                                <span class="port__info-title">Lokasi</span>
                                <span class="port__info-value"><?= $model->wilayahProvinsi->nama ?></span>
                            </li>
                            <li>
                                <span class="port__info-title">Harga</span>
                                <span class="port__info-value"> <?= \app\components\Angka::toReadableHarga($model->total_harga) ?></span>
                            </li>
                        </ul>
                        <!-- <div class="social--port">
                            <a href="">
                                <i class="zmdi zmdi-facebook"></i>
                            </a>
                            <a href="">
                                <i class="zmdi zmdi-google"></i>
                            </a>
                            <a href="">
                                <i class="zmdi zmdi-instagram"></i>
                            </a>
                        </div> -->
                    </div>
                    <!-- End Port Info -->
                </div>
            </div>
        </div>
        <!-- End Item -->
    </div>
</section>
<!-- End Portfolio 1 -->
<!-- Blog Detail -->
<section class="blog-detail">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12">
                <h2 class="blog-title">
                    Lihat Design!
                </h2>
                <div style="font-size: 1rem;font-weight:bold;">
                    Gambar Hasil
                </div>
                <?php
                $hasils = app\models\PortofolioGambar::find()->where(['portofolio_id' => $model->id])->andWhere(['jenis_gambar' => 0])->all();
                ?>
                <div class="blog-content">
                    <p class="mt-1">
                    <div class="row no-gutters">
                        <?php foreach ($hasils as $hasil) { ?>
                            <div class="col-lg-3 col-md-3 col-4">
                                <div class="latest__item">
                                    <img alt="Lastest Project 1" src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $hasil->gambar_design ?>" class="img-project">
                                    <a href="<?= \Yii::$app->request->baseUrl . "/uploads/" . $hasil->gambar_design ?>" data-lightbox="Lastest Project" class="overlay overlay--yellow overlay--invisible overlay--p-15">
                                        <i class="zmdi zmdi-plus-circle-o"></i>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    </p>
                </div>
            </div>
            <div class="col-lg-12 col-12 mt-4">
                <div style="font-size: 1rem;font-weight:bold;">
                    Gambar Design
                </div>
                <?php
                $designs = app\models\PortofolioGambar::find()->where(['portofolio_id' => $model->id])->andWhere(['jenis_gambar' => 1])->all();
                ?>
                <div class="blog-content">
                    <p class="mt-1">
                    <div class="row no-gutters">
                        <?php foreach ($designs as $design) { ?>
                            <div class="col-lg-3 col-md-3 col-4">
                                <div class="latest__item">
                                    <img alt="Lastest Project 1" src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $design->gambar_design ?>" class="img-project">
                                    <a href="<?= \Yii::$app->request->baseUrl . "/uploads/" . $design->gambar_design ?>" data-lightbox="Lastest Project" class="overlay overlay--yellow overlay--invisible overlay--p-15">
                                        <i class="zmdi zmdi-plus-circle-o"></i>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    </p>
                </div>
            </div>
            <div class="col-lg-12 col-12 mt-4">
                <div style="font-size: 1rem;font-weight:bold;">
                    Sebelum dan Sesudah
                </div>
                <?php
                $befores = app\models\PortofolioGambar::find()->where(['portofolio_id' => $model->id])->andWhere(['jenis_gambar' => 2])->all();
                ?>
                <div class="blog-content">
                    <p class="mt-1">
                    <div class="row no-gutters">
                        <?php foreach ($befores as $ba) { ?>
                            <div class="col-lg-3 col-md-3 col-4">
                                <div class="latest__item">
                                    <img alt="Lastest Project 1" src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $ba->gambar_design ?>" class="img-project">
                                    <a href="<?= \Yii::$app->request->baseUrl . "/uploads/" . $ba->gambar_design ?>" data-lightbox="Lastest Project" class="overlay overlay--yellow overlay--invisible overlay--p-15">
                                        <i class="zmdi zmdi-plus-circle-o"></i>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Blog Detail -->
<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile("@web/homepage/vendor/lightbox2/src/js/lightbox.js", ['position' => \yii\web\View::POS_END]);
?>
<?php

use richardfan\widget\JSRegister;

JSRegister::begin(); ?>
<script>
    $(document).ready(function() {
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': false,
            'alwaysShowNavOnTouchDevices': true,
        });
    });
</script>
<?php JSRegister::end(); ?>