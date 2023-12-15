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
                            <a href="#"><?= $page->title ?></a>
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
                    <?= $page->title ?>
                </h1>
                <p class="blog-meta">
                    <em class="author">By <?= isset($page->createdBy) ? $page->createdBy->name : "-" ?></em>
                    <em class="time"><?= Yii::$app->formatter->asIddate($page->created_at, false) ?></em>
                </p>
                <div class="blog-content">
                    <img src="<?= Yii::$app->formatter->asMyImage($page->thumbnail, false) ?>" alt="Image" class="img img-fluid" style="display: block; margin: auto">
                    <?= $page->pages ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Blog Detail -->
<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
?>