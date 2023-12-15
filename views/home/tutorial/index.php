<?php

use app\components\frontend\LinkPager;
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
                            <a href="<?= \Yii::$app->request->BaseUrl ?>/home/tutorial/index">Tutorial</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Navigation -->

<section class="service-list">
    <div class="container">
        <h2 class="text-center">Daftar Tutorial</h2>

        <div id="isotope-grid" class="project--hover clearfix row no-gutters mt-4">
            <div class="col-12">
                <div id="filter-wrap">
                    <ul id="filter" class="ul--no-style ul--inline">
                        <li class="active">
                            <a href="<?= \Yii::$app->request->BaseUrl ?>/home/tutorial/index">Semua</a>
                        </li>
                        <?php foreach ($kategories as $kategori) : ?>
                        <li>
                            <a href="<?= \Yii::$app->request->BaseUrl . "/home/tutorial/index?id=". $kategori->id ?>"><?= $kategori->nama_kategori ?></a>
                        </li>
                        <?php endforeach ?>
                    </ul>
                </div>
            </div>
            <?php foreach ($models as $key => $model) : ?>
                <div class="col-lg-3 col-md-6 col-sm-6 col-6 item agency">
                    <div class="project__item ml-1 mr-1">
                        <div class="pro__img" style="background-image: url(<?= $model->thumbnail ?>);background-size: cover;background-position: center;">
                            <a href="" class="pro-link" data-toggle="modal" data-target="#tutor-<?= $key ?>">
                                <div class="pro-info pro-info--darker" style="opacity: 1;">
                                    <h4 class="company mr-2">
                                        <?= $model->judul ?>
                                    </h4>
                                    <p class="cat-name d-none d-md-block">
                                        <em>
                                            Klik untuk melihat
                                        </em>
                                    </p>
                                </div>
                            </a>
                            <!-- Modal -->
                            <div class="modal fade" id="tutor-<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="<?= $model->judul ?>" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle"><?= $model->judul ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <iframe width="100%" height="345" src="<?= $model->link_youtube ?>">
                                            </iframe>
                                        </div>
                                        <!-- <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach ?>
            <div class="col-12 mt-4 text-center">
                <?= LinkPager::widget(['pagination' => $pages]); ?>
            </div>
        </div>
    </div>

</section>

<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
?>