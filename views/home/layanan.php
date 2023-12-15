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
<!-- Our Process -->
<section class="process-page">
    <div class="container">
        <div class="process-item">
            <div class="process__left wow fadeInLeft" data-wow-delay="1s">
                <div class="pro__img">
                    <img alt="Process 1" src="img/process-05.jpg">
                </div>
            </div>
            <div class="process__right pro__text-wrap bg-f8 wow fadeInRight" data-wow-delay="1s">
                <div class="pro__text">
                    <h2>
                        <span>01</span>
                        meet
                    </h2>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmo tempor incididunt ut labore et dolore magna aliqua.
                        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    </p>
                    <div class="row no-gutters">
                        <div class="col-md-6">
                            <div class="li-item">
                                <i class="fa fa-check-circle" aria-hidden="true"></i>
                                Duis aute irure dolor in
                            </div>
                            <div class="li-item">
                                <i class="fa fa-check-circle" aria-hidden="true"></i>
                                Excepteur sint occaecat
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="li-item">
                                <i class="fa fa-check-circle" aria-hidden="true"></i>
                                Sunt in culpa qui
                            </div>
                            <div class="li-item">
                                <i class="fa fa-check-circle" aria-hidden="true"></i>
                                Ut enim ad minima veniam
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="process-item">
            <div class="process__left pro__text-wrap bg-f8 wow fadeInLeft" data-wow-delay="1s">
                <div class="pro__text">
                    <h2>
                        <span>02</span>
                        disscusion
                    </h2>
                    <p class="mb-0">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmo tempor incididunt ut labore et dolore magna aliqua.
                        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    </p>
                    <p>
                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem huska ax accuntium doloremque laudantium, totam rem aperiam,
                        eaque ipsam .quae ab illo inven
                    </p>
                </div>
            </div>
            <div class="process__right wow fadeInRight" data-wow-delay="1s">
                <div class="pro__img">
                    <img alt="Process 2" src="img/process-06.jpg">
                </div>
            </div>
        </div>
        <div class="process-item">
            <div class="process__left wow fadeInLeft">
                <div class="pro__img">
                    <img alt="Process 3" src="img/process-07.jpg">
                </div>
            </div>
            <div class="process__right pro__text-wrap bg-f8 wow fadeInRight">
                <div class="pro__text">
                    <h2>
                        <span>03</span>
                        ideal
                    </h2>
                    <p class="m-b-30">
                        Excepteur sint occaecat cupidatat non proident, sunt in culpa quison al officia deserunt mollit anim id est laborum.
                    </p>
                    <div class="row no-gutters pro-bar-wrap">
                        <div class="col-md-3">
                            <p class="bar__title">Creative</p>
                            <p class="bar__title">Effective</p>
                            <p class="bar__title">Suport</p>
                        </div>
                        <div class="col-md-9">
                            <div class="pro-bar-container color-333">
                                <div class="pro-bar color-e1" data-pro-bar-percent="80" data-pro-bar-delay="500">
                                </div>
                            </div>
                            <div class="pro-bar-container color-333 m-y-15">
                                <div class="pro-bar color-e1" data-pro-bar-percent="90" data-pro-bar-delay="500">
                                </div>
                            </div>
                            <div class="pro-bar-container color-333">
                                <div class="pro-bar color-e1" data-pro-bar-percent="75" data-pro-bar-delay="500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="process-item">
            <div class="process__left pro__text-wrap bg-f8 wow fadeInLeft">
                <div class="pro__text">
                    <h2 class="m-b-20">
                        <span>04</span>
                        contruct
                    </h2>
                    <p class="m-b-0">
                        <span>saving money: </span>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmo tempor incididunt ut labore et dolore magna aliqua.
                        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    </p>
                    <p>
                        <span>fast: </span>
                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem huska ax accuntium doloremque laudantium, totam rem aperiam,
                        eaque ipsam .quae ab illo inven
                    </p>
                </div>
            </div>
            <div class="process__right wow fadeInRight">
                <div class="pro__img">
                    <img alt="Process 4" src="img/process-08.jpg">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Our Process -->
<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
?>