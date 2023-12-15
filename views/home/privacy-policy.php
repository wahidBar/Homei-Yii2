<?php
$settings = \app\models\SiteSetting::find()->all();

$this->registerCss("

.overlay {
    position:absolute;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
}
ul {
    margin-left: 20px;
}
");
?>
<!-- Navigation -->
<section class="navigation">
    <div class="parallax parallax--nav" style="background-image: url(<?= \Yii::$app->request->baseUrl . "/uploads/" . $settings[0]['gambar_header'] ?>);">
        <div class="overlay"></div>
        <div class="container clearfix">
            <div class="row">
                <div class="col-12">
                    <h2>
                        <?= $settings[0]['tagline']; ?>
                    </h2>
                </div>
                <div class="col-12">
                    <p>
                        <?= $settings[0]['tagline2']; ?>
                    </p>
                </div>
                <div class="col-12">
                    <ul class="breadcrumbs ul--inline ul--no-style">
                        <li>
                            <a href="<?= \Yii::$app->request->BaseUrl ?>/home">Home</a>
                        </li>
                        <span>/</span>
                        <li class="active">
                            <a href="<?= \Yii::$app->request->BaseUrl ?>/home/kebijakan-privasi">Kebijakan Privasi</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Navigation -->
<!-- Blog Detail -->
<section class="blog-detail">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-7">
                <h1 class="blog-title">
                    Kebijakan Privasi
                </h1>
                <!-- <p class="blog-meta">
                    <em class="author">By Admin</em>
                    <em class="cate">Privacy Policy</em>
                    <em class="time">Dec 30,2017</em>
                </p> -->
                <div class="blog-content">
                    <?= $setting->kebijakan_dan_privacy ?>
                </div>
            </div>
            <div class="col-lg-4 col-md-5">
                <div class="blog-sidebar">
                    <div class="blog__recent">
                        <h4 class="title-sidebar">
                            Artikel Terkait
                        </h4>
                        <div class="blog__recent-item clearfix">
                            <div class="">
                                <h6>
                                    <a href="<?= \Yii::$app->request->BaseUrl ?>/home/syarat-ketentuan">Syarat & Ketentuan</a>
                                </h6>
                                <!-- <p>
                                    <em>November , 24 , 2017</em>
                                </p> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Blog Detail -->
<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
?>