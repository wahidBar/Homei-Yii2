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
                            <a href="<?= \Yii::$app->request->BaseUrl ?>/home/portofolio">Portofolio Proyek</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Navigation -->
<!-- Blog Grid 2 -->
<div class="blog1 blog2">
    <div class="container-fluid">
        <div id="isotope-grid" class="clearfix">
            <?php
            foreach ($models as $porto) {
            ?>
                <div class="col-lg-3 col-md-6 col-12 item design">
                    <div class="blog-item">
                        <div class="img-blog">
                            <?php
                            $gambar = app\models\PortofolioGambar::find()->where(['portofolio_id' => $porto->id])->one();
                            ?>
                            <a href="<?= \Yii::$app->request->baseUrl . "/home/detail-portofolio?id=" . $porto->kode_unik ?>">
                                <img alt="Blog 1" src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $gambar->gambar_design; ?>">
                            </a>
                        </div>
                        <div class="blog-content">
                            <h4 class="blog-title">
                                <a href="<?= \Yii::$app->request->baseUrl . "/home/detail-portofolio?id=" . $porto->kode_unik ?>"><?= $porto->judul ?></a>
                            </h4>
                            <p class="blog-meta">
                                <em class="author">By <?= $porto->user->name ?></em>
                                <em class="cate">Konsultan</em>
                            </p>
                            <em class="location"><?= $porto->konsepDesain->nama_konsep ?> · <?= $porto->wilayahProvinsi->nama ?></em>
                            <p class="blog-price" style="font-size: 0.8rem;">
                                <?= \app\components\Angka::toReadableHarga($porto->total_harga) ?> · <?= $porto->luas ?>m<sup>2</sup> · <?= $porto->ruangan ?> ruang
                            </p>
                        </div>
                    </div>
                </div>
                <!-- End Item -->
            <?php } ?>
        </div>
    </div>
</div>
<!-- End Blog Grid 2 -->

<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile("@web/homepage/vendor/isotope/isotope.pkgd.min.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile("@web/homepage/js/isotope-custom.js", ['position' => \yii\web\View::POS_END]);
?>