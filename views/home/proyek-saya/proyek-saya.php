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
                            <a href="<?= \Yii::$app->request->BaseUrl ?>/home/proyek-saya/index">Proyek Saya</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Navigation -->
<!-- End Navigation -->
<?php if ($proyeks != null) { ?>
    <!-- Contact content -->
    <section class="contact-content">
        <div class="container">
            <div id="tableHover" class="col-lg-12 col-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="title title-3 title--dark">
                                    Proyek Saya
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area mt-4">
                        <div class="row">
                            <?php foreach ($proyeks as $proyek) { ?>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12 pt-1">

                                    <div class="row">
                                        <div class="col-4 d-none d-md-block">
                                            <img alt="Client" src="<?= \Yii::$app->request->baseUrl . "/uploads/proyek-saya.jpg" ?>" style="height: 150px;">

                                        </div>
                                        <div class="col-8 client-content">
                                            <h3 class="text-white">
                                                <?= $proyek->nama_proyek ?>
                                            </h3>
                                            <p class="client-name">
                                                <?= \app\components\Angka::toReadableHarga($proyek->nilai_kontrak) ?>
                                            </p>
                                            <p class="client-job">
                                                <em><?=  Yii::$app->formatter->asDate($proyek->tanggal_awal_kontrak) . " hingga " .  Yii::$app->formatter->asDate($proyek->tanggal_akhir_kontrak) ?></em>
                                            </p>
                                            <div class="text-center">
                                                <a href="<?= \Yii::$app->request->baseUrl . "/home/proyek-saya/detail-proyek?id=" . $proyek->kode_unik ?>" class="btn btn-sm btn-warning btn-block mt-4">Detail</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
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
                Anda Belum Mempunyai Proyek
            </h2>
        </div>
        <?= $this->render('../_construction-animation'); ?>
    </div>
<?php
}
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
?>