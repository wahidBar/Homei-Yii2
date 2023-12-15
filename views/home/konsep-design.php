<?php
$this->registerCssFile("@web/homepage/css/chat.css");
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
    background:rgba(0,0,0,0.4);
                            

}
");
?>
<?php
$setting = \app\models\SiteSetting::find()->all();
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
<!-- Service List -->
<section class="service-list">
    <div class="container">
        <div class="row">
            <?php
            foreach ($designs as $design) {
            ?>
                <div class="col-lg-4 col-md-6">
                    <div class="service-list__item">
                        <img alt="<?= $design->nama_konsep ?>" src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $design->gambar ?>">
                        <div class="service-list__text">
                            <h5>
                                <a href="<?= \Yii::$app->request->baseUrl . "/home/formulir-design-bangunan/" . $design->id ?>"><?= $design->nama_konsep ?></a>
                            </h5>
                            <!-- <p>
                            Sed ut perspiciatis unde omnis iste natus error sitdow volunterr voluptatem
                        </p> -->
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <!-- gallery -->
    <div class="container pt-4">
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
                    <div class="style <?= $style ?> "><img src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $gal->gambar ?>" alt="">
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
            </main>
        </div>
        <!-- end of gallery -->
    </div>
</section>
<!-- End Service List -->

<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
?>