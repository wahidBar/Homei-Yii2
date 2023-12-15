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
<!-- Contact content -->
<section class="contact-content">
    <div class="container">
        <div id="tableHover" class="col-lg-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h2 class="text-center">Deal Proyek</h2>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area mt-4">
                    <div class="row">
                        <?php foreach($models as $model){ ?>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12 pt-1">
                            <div class="client__item item clearfix">
                                <div class="client-img">
                                    <img alt="Client 6" src="<?= \Yii::$app->request->baseUrl . "/uploads/proyek-saya.jpg" ?>" style="height: 150px;">
                                </div>
                                <div class="client-content">
                                    <h3 class="text-white">
                                        <?=$model->kontraktors->nama_kontraktor ?>
                                    </h3>
                                    <p class="client-name">
                                    <?= $model->penawaran->kode_penawaran ?>
                                    </p>
                                    <a href="<?= \Yii::$app->request->baseUrl . "/home/detail-deal-project/" . $model->id ?>" class="au-btn au-btn--small au-btn--pill au-btn--yellow au-btn--white text-dark mt-4">Detail</a>
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
<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
?>