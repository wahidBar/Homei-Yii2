<?php

use richardfan\widget\JSRegister;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->registerCssFile("@web/homepage/css/tabs.css");
$this->registerCssFile("@web/homepage/css/image-map.css");
$this->registerCssFile("@web/homepage/css/gallery.css");
$this->registerCssFile("@web/homepage/vendor/owl-carousel/animate.css");
$this->registerCssFile("@web/homepage/vendor/owl-carousel/owl.carousel.min.css");
$this->registerCssFile("@web/homepage/vendor/owl-carousel/owl.theme.default.min.css");
$this->registerCssFile("@web/homepage/vendor/revolution/settings.css");
$this->registerCss("
main {
	margin: 0vh auto;
	max-width: 100%;
	display: grid;
	grid-gap: 5px;
	grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
	grid-auto-rows: 250px;
	grid-auto-flow: dense;
}

.style {
	text-align: center;
	padding: 1rem 0;
	color: white;
	text-transform: uppercase;
	background: rgba(0,0,0,.2);
	overflow: hidden;
	padding: 0;
	display: flex;
	align-items: stretch;
	justify-content: center;
    position: relative;
}

.style img {
	width: 100%;
	height: 100%;
	display: block;
	-o-object-fit: cover;
	   object-fit: cover;
	-o-object-position: center;
	   object-position: center;
	transition: all .5s;
}

.text-img {
    position: absolute;
    bottom: 8px;
    left: 16px;    
}

.text-img h2{
    font-size : 2rem;
    color: #fff;
    text-align : left;
}

.horizontal {
	grid-column: span 2;
}

.vertical {
	grid-row: span 2;
}

.big {
	grid-column: span 2;
	grid-row: span 2;
}
.overlay {
    position:absolute;
    width:100%;
    height:100%;
    background:linear-gradient(0deg, rgb(0 0 0) 0%, rgba(0,0,0,0.8519782913165266) 10%, rgba(255,255,255,0) 100%);
}
");
?>
<?php
$setting = \app\models\SiteSetting::find()->all();
?>
<?php if ($popup and $popup->web_show) : ?>
    <div id="modal" class="fade modal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    Selamat datang di HOMEi
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div id='modalContent'>
                        <a href="<?= $popup->web_link ?>">
                            <img src="<?= Yii::$app->formatter->asMyImage($popup->image, false) ?>" alt="" class="img img-fluid">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<!-- Slider -->
<section class="slide">
    <!-- revolution slider begin -->
    <div class="rev_slider_wrapper">
        <div id="revolution-slider4" class="rev_slider" data-version="5.4.5" style="display: none;">
            <ul>
                <?php foreach ($slides as $slide) {
                    $items = array("slidedown", "scaledownfromleft");
                ?>
                    <li data-transition="<?= $items[array_rand($items)]; ?>" data-slotamount="7" data-masterspeed="2500" data-delay="7000">
                        <!--  BACKGROUND IMAGE -->
                        <img src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $slide->image ?>" alt="Slide">
                        <div class="overlay"></div>
                        <div class="tp-caption slide-title-4-h mb-3" data-x="center" data-y="['360','240','240','200']" data-fontsize="['60', '60', '56', '50']" data-whitespace="nowrap" data-frames='[{"delay":1600,"speed":1500,"frame":"0","from":"y:-50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'>
                            <?= $slide->title ?>
                        </div>
                        <div class="tp-caption slide-title-4 mt-3" data-x="center" data-y="center" data-fontsize="['36','32','30','22']" data-whitespace="nowrap" data-frames='[{"delay":1600,"speed":1500,"frame":"0","from":"y:-50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'>
                            <?= $slide->subtitle ?>
                        </div>
                        <?php if ($slide->button_link != null && $slide->button_title) { ?>
                            <a href="<?= $slide->button_link ?>" class="tp-caption btn btn-warning au-btn--slide" data-x="center" data-y="['500','390','390','310']" data-frames='[{"delay":2200,"speed":1500,"frame":"0","from":"y:50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'>
                                <?= $slide->button_title ?>
                            </a>
                        <?php } ?>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <!-- revolution slider end -->
</section>
<!-- End Slider -->

<section class="pb-4">
    <div class="container pt-4">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                <h2 class="title-3 title-3--left">
                    Langkah Mudah Wujudkan<br> Interior Ideal Versi Anda.
                </h2>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-12">
                <div class="board ml-4 mr-5">
                    <div class="board-inner position-relative">
                        <div class="line"></div>
                        <ul id="circleTab" class="nav nav-tabs py-5 justify-content-between position-relative circle-tab border-0" role="navigation">
                            <?php
                            $countTabs = \app\models\TabHome::find()->count();
                            $tabs = \app\models\TabHome::find()->all();
                            for ($i = 0; $i < $countTabs; $i++) {
                            ?>
                                <li class="tab-circle">
                                    <a class="tab-link font-weight-bold <?php if ($i == 0) echo "active" ?>" href="#<?= $tabs[$i]['nama_id_tab'] ?>" style="font-size:1.2rem" aria-controls="<?= $tabs[$i]['nama_id_tab'] ?>" role="tab" title="welcome">
                                        <?= $i + 1 ?>
                                    </a>
                                </li>
                            <?php
                            }
                            ?>

                        </ul>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <div class="tab-content ml-4" id="tabContent">

                    <?php
                    $countTabs = \app\models\TabHome::find()->count();
                    $tabs = \app\models\TabHome::find()->all();
                    for ($i = 0; $i <= $countTabs; $i++) {
                    ?>
                        <div class="tab-pane border-0 card shadow show <?php if ($i == 0) echo "active" ?>" id="<?= $tabs[$i]['nama_id_tab'] ?>" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-6 col-md-8 col-8">
                                    <div class="text-left">
                                        <h3><?= $tabs[$i]['judul'] ?></h3>
                                        <p class="mt-4">
                                            <?= $tabs[$i]['isi'] ?>
                                        </p>
                                        <?php
                                        if ($tabs[$i]['button_link'] && $tabs[$i]['button_label'] != null) {
                                        ?>
                                        <?= Html::a(
                                                $tabs[$i]['button_label'],
                                                [$tabs[$i]['button_link']],
                                                [
                                                    'class' => 'btn btn-sm btn-warning text-white mt-3',
                                                ]
                                            );
                                        } ?>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-4">
                                    <img src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $tabs[$i]['gambar'] ?>" alt="" class="img-fluid" width="250px">
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>

<!-- Service 2 -->
<section class="service-2">
    <div class="container clearfix">
        <div class="service-2-wrap clearfix">
            <div class="service-2__left wow fadeInLeft" data-wow-duration="1s">
                <div class="service-2__img d-none d-lg-block">
                    <h2 class="title title-2--special title-small" id="our1">
                        <?= $thomei->judul_kiri ?>
                    </h2>
                    <h2 class="title title-2--special title-small" id="our2">
                        <?= $thomei->judul_atas ?>
                    </h2>
                    <img alt="<?= $thomei->judul_kiri . $thomei->judul_atas ?>" src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $thomei->gambar ?>">
                </div>
            </div>
            <div class="service-2__right">
                <div class="service-2__inner-head wow fadeInDown" data-wow-duration="1s" data-wow-delay=".5s">
                    <h3>
                        <?= $thomei->judul_utama ?>
                    </h3>
                    <p>
                        <?= $thomei->isi ?>
                    </p>
                </div>
                <div class="service-2__inner-body wow fadeInUp" data-wow-duration="1s" data-wow-delay=".5s">
                    <?php foreach ($dhomeis as $homei) { ?>
                        <!-- <div class="box-item body__item clearfix">
                            <div class="box-head box-head--border"> -->
                        <div class="row box-item clearfix">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="box-head box-head--border" style="padding: 0px 0px;border:0px">
                                        <div class="border-about-homei">
                                            <i class="fa <?= $homei->icon ?> icon-homei" aria-hidden="true"></i>
                                        </div>
                                    </td>
                                    <td style="padding: 0px 10px; border:0px">
                                        <div class="box-body">
                                            <h5 class="text-white" style="font-size: 1.5rem;"><?= $homei->judul ?></h5>
                                            <p class="text-white">
                                                <?= $homei->isi ?>
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <!-- <div class="col-4 box-head box-head--border">
                                <i class="<?= $homei->icon ?> icon-homei" aria-hidden="true"></i>
                            </div>
                            <div class="col-8">
                                <div class="box-body">
                                    <h5 class="text-white" style="font-size: 1.5rem;"><?= $homei->judul ?></h5>
                                    <p class="text-white">
                                        <?= $homei->isi ?>
                                    </p>
                                </div>
                            </div> -->
                        </div>
                        <!-- </div>
                        </div> -->
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Service 2 -->

<!-- Slider -->
<section class="slide2 container clearfix">
    <!-- revolution slider begin -->
    <h2 class="title title-2">
        Contoh Produk
        <span class="under-title-2">

        </span>
    </h2>
    <div class="rev_slider_wrapper rev_slider_width">
        <div id="revolution-slider1" class="rev_slider" data-version="5.4.4" style="display: none;">
            <ul>
                <?php
                foreach ($cproduks as $key => $cproduk) :
                    $dproduks = \app\models\DetailContohProduk::find()->where(['id_contoh_produk' => $cproduk->id])->all();
                ?>
                    <li data-transition="crossfade" data-slotamount="7" data-masterspeed="2000" data-delay="1000000" id="image-map<?= $key ?>">
                        <!--  BACKGROUND IMAGE -->

                        <img style="width: 100%;height: auto;" src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $cproduk->gambar ?>">
                        <?php foreach ($dproduks as $produk) { ?>
                            <div class="<?= (($produk->y_pos <= 50) ? "pin map{$key}" : (($produk->y_pos > 50) ? "pin map{$key} pin-down" : "pin map{$key}")) ?>" data-xpos="<?= $produk->x_pos ?>" data-ypos="<?= $produk->y_pos ?>">
                                <div class="col-12 image-content d-none d-md-block" style="background-image: url(<?= \Yii::$app->request->baseUrl . "/uploads/" . $produk->supplierBarang->gambar ?>);">
                                </div>
                                <div class="text-center">
                                    <h5><?= implode(' ', array_slice(explode(' ', $produk->supplierBarang->nama_barang), 0, 2)) ?></h5>
                                    <p class="price-font"><?= \app\components\Angka::toReadableHarga($produk->supplierBarang->harga_ritel) ?></p>
                                    <a href="<?=
                                                Url::to([
                                                    "/home/bahan-material/view",
                                                    "id" => $produk->supplierBarang->slug,
                                                ]) ?>" class="btn btn-info btn-sm btn-block text-light">Detail</a>
                                </div>
                            </div>
                        <?php } ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
</section>
<!-- End Slider -->


<!-- gallery -->
<section class="latest-project-4">
    <div class="container pt-3">
        <div class="wow fadeInUp" data-wow-duration="1s" data-wow-delay="1s">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                    <h2 class="title title-3 title-3--left">
                        Galeri Kami
                    </h2>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 col-12">
                    <p>Kami menerjemahkan visi Anda menjadi kenyataan melalui kerangka kerja kreatif kami. Kami menciptakan desain estetis dan fungsional yang disesuaikan dengan semua kebutuhan dan prefensi Anda yang bertujuan untuk menciptakan kenyamanan dan menginspirasi kreativitas.</p>
                </div>
            </div>
            <div class="d-none d-md-block">
                <main class="pb-5">
                    <?php foreach ($galleries as $gal) { ?>
                        <?php
                        if ($gal->style == "square") {
                            $style = "";
                        } else {
                            $style = $gal->style;
                        }
                        ?>
                        <div class="style <?= $style ?> img-hover">
                            <img src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $gal->gambar ?>" alt="">
                            <div class="text-hover">
                                <h3><?= $gal->judul ?></h3>
                                <h4><?= $gal->keterangan ?></h4>
                            </div>
                            <a href="#"></a>
                        </div>
                    <?php } ?>
                </main>
            </div>
            <div class="d-block d-sm-none d-sm-block d-md-none">
                <main class="pb-5">
                    <?php foreach ($hp_galleries as $gal) { ?>
                        <div class="style"><img src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $gal->gambar ?>" alt="">
                            <div class="overlay"></div>
                            <div class="text-img">
                                <h2>
                                    <?= $gal->judul ?>
                                </h2>
                                <p class="text-left">
                                    <?= $gal->keterangan ?>
                                </p>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- <div class="image-grid">
                        <?php foreach ($hp_galleries as $gal) { ?>
                            <div class="style"><img src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $gal->gambar ?>" alt="">
                                <div class="overlay"></div>
                                <div class="text-img">
                                    <h5 class="text-white text-left">
                                        <?= $gal->judul ?>
                                    </h5>
                                    <p class="text-left">
                                        <?= $gal->keterangan ?>
                                    </p>
                                </div>
                            </div>
                        <?php } ?>
                    </div> -->
                </main>
            </div>
        </div>
    </div>
</section>
<!-- end of gallery -->

<!-- Testi-Partner -->
<section class="testi-partner">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-12">
                <div class="testi-partner__left">
                    <h2 class="title title-3 title-3--left">
                        Testimoni
                    </h2>
                    <div class="testi-slide-wrap owl-carousel owl-theme" id="owl-testi-1">

                        <?php foreach ($testimonials as $testi) { ?>
                            <div class="testi__item item clearfix">
                                <div class="testi__person">
                                    <img class="img-testi mx-auto" src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $testi->gambar ?>" alt="Testi 1">
                                    <h6><?= $testi->nama ?></h6>
                                    <p class="testi-job">
                                        <em><?= $testi->jabatan ?></em>
                                    </p>
                                </div>
                                <div class="testi__speech">
                                    <blockquote>
                                        <i class="fa fa-quote-left big-qoute"></i>
                                        <?= $testi->isi ?>
                                    </blockquote>

                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-12">
                <div class="testi-partner__right">
                    <h2 class="title title-3 title-3--right">
                        Partner Kami
                    </h2>
                    <div class="partner-wrap1 owl-carousel owl-theme" id="owl-partner-1">
                        <?php foreach ($partners as $partner) { ?>
                            <a href="<?= $partner->link ?>" target="_blank" class="partner__item item">
                                <img class="img-partner" alt="<?= $partner->nama_partner ?>" src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $partner->gambar ?>">
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Testi-Partner -->
<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js");
$this->registerJsFile("@web/homepage/js/tabs.js", ['position' => \yii\web\View::POS_END]);
?>

<?php JSRegister::begin(); ?>
<script>
    <?php if ($popup and $popup->web_show) : ?>
        $('#modal').modal('show');
    <?php endif ?>

    $(document).ready(function() {
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': false,
            'alwaysShowNavOnTouchDevices': true,
        });
    });
    $(".hover").mouseleave(
        function() {
            $(this).removeClass("hover");
        }
    );

    $(document).ready(function() {
        //tooltip direction
        var tooltipDirection;

        <?php foreach ($cproduks as $key => $cproduk) : ?>
            for (i = 0; i < $(".map<?= $key ?>").length; i++) {
                // set tooltip direction type - up or down             
                if ($(".map<?= $key ?>").eq(i).hasClass('pin-down')) {
                    tooltipDirection = 'tooltip2-down';
                } else {
                    tooltipDirection = 'tooltip2-up';
                }
                // append the tooltip
                if ($(".map<?= $key ?>").eq(i).data('xpos') < 70) {
                    $("#image-map<?= $key ?>").append("<div style='left:" + $(".map<?= $key ?>").eq(i).data('xpos') + "%;top:" + $(".map<?= $key ?>").eq(i).data('ypos') + "%' class='" + tooltipDirection + "'>\
                                            <div class='tooltip2'>" + $(".map<?= $key ?>").eq(i).html() + "</div>\
                                    </div>");
                } else {
                    $("#image-map<?= $key ?>").append("<div style='left:" + $(".map<?= $key ?>").eq(i).data('xpos') + "%;top:" + $(".map<?= $key ?>").eq(i).data('ypos') + "%' class='" + tooltipDirection + "'>\
                                            <div class='tooltip2 tooltip2-right'>" + $(".map<?= $key ?>").eq(i).html() + "</div>\
                                    </div>");
                }
            }
        <?php endforeach ?>
        // show/hide the tooltip
        $('.tooltip2-up, .tooltip2-down').mouseenter(function() {
            $(this).children('.tooltip2').fadeIn(100);
        }).mouseleave(function() {
            $(this).children('.tooltip2').fadeOut(100);
        })
    });
</script>
<?php JSRegister::end(); ?>