<?php

use app\components\frontend\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;

$setting = \app\models\SiteSetting::find()->all();
$this->registerCss("

.overlay {
    position:absolute;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
}
.dropdown-list-image {
    position: relative;
    height: 2.5rem;
    width: 2.5rem;
}
.dropdown-list-image img {
    height: 2.5rem;
    width: 2.5rem;
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
                            <a href="#">Daftar Notifikasi</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Navigation -->
<div class="container mt-4 mb-4">
    <div class="row">
        <div class="col-lg-3 left d-none d-md-block">
            <div class="box mb-3 shadow-sm rounded bg-white profile-box text-center">
                <div class="pl-3 pr-3">
                    <?php
                    if (Yii::$app->user->identity->photo_url != null) :
                        echo Html::img(Yii::$app->request->baseUrl . "/uploads/" . Yii::$app->user->identity->photo_url, ["class" => "img-fluid"]);
                    else :
                        echo Html::img(Yii::$app->request->baseUrl . "/uploads/default.png", ["class" => "img-fluid"]);
                    endif;
                    ?>
                </div>
                <div class="p-3 border-top border-bottom">
                    <h5 class="font-weight-bold text-dark mb-1 mt-0"><?= Yii::$app->user->identity->name ?></h5>
                    <p class="mb-0 text-muted"><?= Yii::$app->user->identity->email ?></p>
                </div>
                <div class="p-3">
                    <div class="d-flex align-items-top mb-2">
                        <p class="mb-0 text-muted">Jumlah Proyek</p>
                        <p class="font-weight-bold text-dark mb-0 mt-0 ml-auto"><?= $jumlah_proyek ?></p>
                    </div>
                    <div class="d-flex align-items-top">
                        <p class="mb-0 text-muted">Jumlah Order Barang</p>
                        <p class="font-weight-bold text-dark mb-0 mt-0 ml-auto"><?= $jumlah_order ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 right">
            <div class="box shadow-sm rounded bg-white mb-3">
                <div class="box-title border-bottom p-3">
                    <h6 class="m-0">Daftar Notifikasi</h6>
                </div>
                <div class="box-body p-0">
                    <?php foreach ($models as $model) : ?>
                        <div class="p-3 d-flex align-items-center osahan-post-header">
                            <div class="dropdown-list-image mr-3">
                                <!-- <img class="rounded-circle" src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" /> -->
                                <div class="dropdown-list-image mr-3 d-flex align-items-center bg-info justify-content-center rounded-circle text-white">A</div>
                            </div>
                            <div class="font-weight-bold mr-3">
                                <div class=""><?= $model->title ?></div>
                                <div class="small"><?= $model->description ?></div>
                                <?php
                                echo Html::a('Lihat Notifikasi', Url::to(["/notification/redirect?id=" . $model->id]), ['class' => 'btn btn-outline-success btn-sm']);
                                ?>
                            </div>
                            <span class="ml-auto mb-auto">
                                <div class="text-right text-muted pt-1">
                                    <?php
                                    $date = strtotime($model->created_at);
                                    echo \app\components\Tanggal::timeElapsedString($date);
                                    ?>
                                </div>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?= LinkPager::widget(['pagination' => $pages]); ?>
            </div>

        </div>
    </div>
</div>
<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
?>