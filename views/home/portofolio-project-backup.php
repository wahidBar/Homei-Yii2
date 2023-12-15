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
<!-- Blog Grid 2 -->
<div class="blog1 blog2">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div id="filter-wrap">
                    <ul id="filter" class="ul--no-style ul--inline">
                        <li class="active">
                            <span data-filter="*">All</span>
                        </li>
                        <li>
                            <span data-filter=".design">Design Knowledge</span>
                        </li>
                        <li>
                            <span data-filter=".trick">Tricks and Tips</span>
                        </li>
                        <li>
                            <span data-filter=".exper">Interior Experiences</span>
                        </li>
                        <li>
                            <span data-filter=".qna">Q&A</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="isotope-grid" class="clearfix">
            <div class="col-lg-3 col-md-6 col-12 item design">
                <div class="blog-item">
                    <div class="img-blog">
                        <a href="<?= \Yii::$app->request->baseUrl . "/home/detail-portofolio" ?>">
                            <img alt="Blog 1" src="<?= \Yii::$app->request->baseUrl . "/uploads/gallery/img01.jpg" ?>">
                        </a>
                    </div>
                    <div class="blog-content">
                        <h4 class="blog-title">
                            <a href="<?= \Yii::$app->request->baseUrl . "/home/detail-portofolio" ?>">Minimalist style 2017</a>
                        </h4>
                        <p class="blog-meta">
                            <em class="author">By AThony Lee</em>
                            <em class="cate">Design Knowledge</em>
                        </p>
                        <em class="location">Japandi Modern House · Sawangan</em>
                        <p class="blog-price">
                            Rp 43,779,400 · 46m2 · 5 ruang
                        </p>
                    </div>
                </div>
            </div>
            <!-- End Item -->
            <div class="col-lg-3 col-md-6 col-12 item exper">
                <div class="blog-item">
                    <div class="img-blog">
                        <a href="<?= \Yii::$app->request->baseUrl . "/home/detail-portofolio" ?>">
                            <img alt="Blog 2" src="<?= \Yii::$app->request->baseUrl . "/uploads/gallery/img01.jpg" ?>">
                        </a>
                    </div>
                    <div class="blog-content">
                        <h4 class="blog-title">
                            <a href="<?= \Yii::$app->request->baseUrl . "/home/detail-portofolio" ?>">Best resort in uk</a>
                        </h4>
                        <p class="blog-meta">
                            <em class="author">By Mike Song</em>
                            <em class="cate">Interior Experiences</em>
                        </p>
                        <em class="location">Japandi Modern House · Sawangan</em>
                        <p class="blog-price">
                            Rp 43,779,400 · 46m2 · 5 ruang
                        </p>
                    </div>
                </div>
            </div>
            <!-- End Item -->
            <div class="col-lg-3 col-md-6 col-12 item trick">
                <div class="blog-item">
                    <div class="img-blog">
                        <a href="<?= \Yii::$app->request->baseUrl . "/home/detail-portofolio" ?>">
                            <img alt="Blog 3" src="<?= \Yii::$app->request->baseUrl . "/uploads/gallery/img02.jpg" ?>">
                        </a>
                    </div>
                    <div class="blog-content">
                        <h4 class="blog-title">
                            <a href="<?= \Yii::$app->request->baseUrl . "/home/detail-portofolio" ?>">5 things will every room need</a>
                        </h4>
                        <p class="blog-meta">
                            <em class="author">By Jawn Ha</em>
                            <em class="cate">Tricks and Tips</em>
                        </p>
                        <em class="location">Japandi Modern House · Sawangan</em>
                        <p class="blog-price">
                            Rp 43,779,400 · 46m2 · 5 ruang
                        </p>
                    </div>
                </div>
            </div>
            <!-- End Item -->
            <div class="col-lg-3 col-md-6 col-12 item design">
                <div class="blog-item">
                    <div class="img-blog">
                        <a href="<?= \Yii::$app->request->baseUrl . "/home/detail-portofolio" ?>">
                            <img alt="Blog 4" src="<?= \Yii::$app->request->baseUrl . "/uploads/gallery/img03.jpg" ?>">
                        </a>
                    </div>
                    <div class="blog-content">
                        <h4 class="blog-title">
                            <a href="<?= \Yii::$app->request->baseUrl . "/home/detail-portofolio" ?>">simple is the best</a>
                        </h4>
                        <p class="blog-meta">
                            <em class="author">By Lake Louthor</em>
                            <em class="cate">Design Knowledge</em>
                        </p>
                        <em class="location">Japandi Modern House · Sawangan</em>
                        <p class="blog-price">
                            Rp 43,779,400 · 46m2 · 5 ruang
                        </p>
                    </div>
                </div>
            </div>
            <!-- End Item -->
            <div class="col-lg-3 col-md-6 col-12 item qna">
                <div class="blog-item">
                    <div class="img-blog">
                        <a href="<?= \Yii::$app->request->baseUrl . "/home/detail-portofolio" ?>">
                            <img alt="Blog 5" src="<?= \Yii::$app->request->baseUrl . "/uploads/gallery/img04.jpg" ?>">
                        </a>
                    </div>
                    <div class="blog-content">
                        <h4 class="blog-title">
                            <a href="<?= \Yii::$app->request->baseUrl . "/home/detail-portofolio" ?>">Black or White?</a>
                        </h4>
                        <p class="blog-meta">
                            <em class="author">By Lucy Green</em>
                            <em class="cate">Q&A</em>
                        </p>
                        <em class="location">Japandi Modern House · Sawangan</em>
                        <p class="blog-price">
                            Rp 43,779,400 · 46m2 · 5 ruang
                        </p>
                    </div>
                </div>
            </div>
            <!-- End Item -->
            <div class="col-lg-3 col-md-6 col-12 item exper">
                <div class="blog-item">
                    <div class="img-blog">
                        <a href="<?= \Yii::$app->request->baseUrl . "/home/detail-portofolio" ?>">
                            <img alt="Blog 5" src="<?= \Yii::$app->request->baseUrl . "/uploads/gallery/img05.jpg" ?>">
                        </a>
                    </div>
                    <div class="blog-content">
                        <h4 class="blog-title">
                            <a href="<?= \Yii::$app->request->baseUrl . "/home/detail-portofolio" ?>">Best office interior in uk 2017</a>
                        </h4>
                        <p class="blog-meta">
                            <em class="author">By Lebrond</em>
                            <em class="cate">Interior Experiences</em>
                        </p>
                        <em class="location">Japandi Modern House · Sawangan</em>
                        <p class="blog-price">
                            Rp 43,779,400 · 46m2 · 5 ruang
                        </p>
                    </div>
                </div>
            </div>
            <!-- End Item -->
            <div class="col-lg-3 col-md-6 col-12 item design">
                <div class="blog-item">
                    <div class="img-blog">
                        <a href="<?= \Yii::$app->request->baseUrl . "/home/detail-portofolio" ?>">
                            <img alt="Blog 7" src="<?= \Yii::$app->request->baseUrl . "/uploads/gallery/img06.jpg" ?>">
                        </a>
                    </div>
                    <div class="blog-content">
                        <h4 class="blog-title">
                            <a href="<?= \Yii::$app->request->baseUrl . "/home/detail-portofolio" ?>">time to change your place</a>
                        </h4>
                        <p class="blog-meta">
                            <em class="author">By Mike song</em>
                            <em class="cate">Interior Experiences</em>
                        </p>
                        <em class="location">Japandi Modern House · Sawangan</em>
                        <p class="blog-price">
                            Rp 43,779,400 · 46m2 · 5 ruang
                        </p>
                    </div>
                </div>
            </div>
            <!-- End Item -->
            <div class="col-lg-3 col-md-6 col-12 item qna">
                <div class="blog-item">
                    <div class="img-blog">
                        <a href="<?= \Yii::$app->request->baseUrl . "/home/detail-portofolio" ?>">
                            <img alt="Blog 8" src="<?= \Yii::$app->request->baseUrl . "/uploads/gallery/img07.jpg" ?>">
                        </a>
                    </div>
                    <div class="blog-content">
                        <h4 class="blog-title">
                            <a href="<?= \Yii::$app->request->baseUrl . "/home/detail-portofolio" ?>">wood items , yes or no?</a>
                        </h4>
                        <p class="blog-meta">
                            <em class="author">By Jawn Ha</em>
                            <em class="cate">Q&A</em>
                        </p>
                        <em class="location">Japandi Modern House · Sawangan</em>
                        <p class="blog-price">
                            Rp 43,779,400 · 46m2 · 5 ruang
                        </p>
                    </div>
                </div>
            </div>
            <!-- End Item -->
        </div>
    </div>
</div>
<!-- End Blog Grid 2 -->
<!-- Contact -->
<section class="contact">
    <div class="parallax parallax--contact parallax--contact1">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="contact__inner clearfix">
                        <p class="contact__content">
                            We are the reliable partner to help you complete the work
                        </p>
                        <a href="contact.html" class="au-btn au-btn--big au-btn--pill au-btn--dark">Contact Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Contact -->

<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile("@web/homepage/vendor/isotope/isotope.pkgd.min.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile("@web/homepage/js/isotope-custom.js", ['position' => \yii\web\View::POS_END]);
?>
