<?php

use dmstr\helpers\Html;
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
                        <li class="active">
                            <a href="<?= \Yii::$app->request->BaseUrl ?>/home/cari-tukang/index">Cari Tukang</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Navigation -->
<!-- Service List -->
<section class="service-list">
    <div class="container">
        <h2 class="text-center">Layanan Cari Tukang</h2>

        <div id="isotope-grid" class="project--hover clearfix row no-gutters">
            <?php foreach ($models as $model) : ?>
                <div class="col-lg-3 col-md-6 col-sm-6 col-6 item agency">
                    <div class="project__item ml-1 mr-1">
                        <div class="pro__img" style="background-image: url(<?= \Yii::$app->request->baseUrl . "/uploads/" . $model->icon ?>);background-size: cover;background-position: center;">
                            <?php
                            if (Yii::$app->user->identity->id != null) {
                                $link = \Yii::$app->request->baseUrl . "/home/cari-tukang/form-keperluan?id=" . $model->slug;
                            } else {
                                $link = \Yii::$app->request->baseUrl . "/site/login";
                            }
                            ?>
                            <a href="<?= $link ?>" class="pro-link">
                                <div class="pro-info pro-info--darker" style="opacity: 1;">
                                    <h4 class="company">
                                        <?= $model->nama_kategori_layanan ?>
                                    </h4>
                                    <p class="cat-name d-none d-md-block">
                                        <em>
                                            Pilih layanan untuk mengisi form
                                        </em>
                                    </p>
                                </div>
                            </a>
                        </div>

                        <?php
                        if (Yii::$app->user->identity->id == null) { ?>
                            <?= Html::a('Pilih Layanan', ['site/login'], ['class' => 'btn btn-sm btn-block btn-warning']) ?>
                        <?php } else {
                            echo Html::a('Pilih Layanan', ['form-keperluan', 'id' => $model->slug], ['class' => 'btn btn-sm btn-block btn-warning']);
                        }
                        ?>

                    </div>
                </div>
            <?php endforeach ?>

        </div>
    </div>
    <?php if(\app\models\Config::findOne(['name' => 'form-registrasi-user'])->value): ?>
    <div class="mt-5 p-2 pt-5 pb-5 text-center" style="background-color: #ebcd1e;">
            <h3>Ayo Bergabung menjadi bagian dari kami</h3>
            <a href="<?= \yii\helpers\Url::to(['/home/register-tukang']) ?>"><h3  style="color:#eee!important">Join sebagai mitra</h3></a>
    </div>
    <?php endif ?>
    <!-- Testi-Partner -->
    <section class="testi-partner">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-12">
                    <div class="testi-partner__left">
                        <h2 class="title title-3 title-3--left">
                            Testimoni
                        </h2>
                        <div class="testi-slide-wrap owl-carousel owl-theme" id="owl-testi-1">

                            <?php foreach ($testimonials as $testi) { ?>
                                <div class="testi__item item clearfix">
                                    <div class="testi__person">
                                        <img class="img-testi mx-auto" src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $testi->gambar ?>" alt="Testi 1">
                                        <h6><?= $testi->nama ?></h6>
                                        <p class="testi-job">
                                            <em><?= $testi->jabatan ?></em>
                                        </p>
                                    </div>
                                    <div class="testi__speech">
                                        <blockquote>
                                            <i class="fa fa-quote-left big-qoute"></i>
                                            <?= $testi->isi ?>
                                        </blockquote>

                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-12">
                    <div class="testi-partner__right">
                        <h2 class="title title-3 title-3--right">
                            Partner Kami
                        </h2>
                        <div class="partner-wrap1 owl-carousel owl-theme" id="owl-partner-1">
                            <?php foreach ($partners as $partner) { ?>
                                <a href="#" class="partner__item item">
                                    <img class="img-partner" alt="<?= $partner->nama_partner ?>" src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $partner->gambar ?>">
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Testi-Partner -->
</section>
<!-- End Service List -->

<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
?>