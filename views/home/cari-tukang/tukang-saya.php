<?php

use dmstr\helpers\Html;
use yii\grid\GridView;
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
$this->registerCssFile("@web/homepage/css/construction.css");
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
                        <span>/</span>
                        <li class="active">
                            <a href="#">Tukang Saya</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Navigation -->
<?php if ($models != null) { ?>
    <!-- Contact content -->
    <section class="contact-content">
        <div class="container">
            <div id="tableHover" class="col-lg-12 col-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="title title-3 title--dark">
                                    Tukang Saya
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area mt-4">
                        <div class="row">
                            <?php foreach ($models as $model) {
                            ?>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12 pt-1">
                                    <div class="row">
                                        <div class="col-4 d-none d-lg-block">
                                            <img alt="Client" src="<?= \Yii::$app->request->baseUrl . "/uploads/proyek-saya.jpg" ?>" style="height: 150px;">
                                        </div>
                                        <div class="col-8 client-content" style="padding:2rem">
                                            <h3 class="text-white">
                                                <?= $model->kategori->nama_kategori_layanan ?>
                                            </h3>
                                            <p class="client-name">
                                                <?= $model->nama_pelanggan ?>
                                            </p>
                                            <p class="client-job mb-3">
                                                <em><?= \Yii::$app->formatter->asRp($model->biaya) ?></em>
                                            </p>
                                            <a href="<?= \Yii::$app->request->baseUrl . "/home/cari-tukang/view?id=" . $model->kode_unik ?>" class="btn btn-sm btn-block btn-warning mt-2">Detail</a>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } else { ?>
    <div class="container mt-4">
        <div class="col-md-12">
            <h2 class="title title-3 title--dark">
                Anda Belum Mempunyai Rencana Pembangunan
            </h2>
        </div>
        <?= $this->render('../_construction-animation'); ?>
    </div>
<?php
}
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
?>