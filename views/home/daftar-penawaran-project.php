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
                            <a href="#">Daftar Penawaran Proyek</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Our Process 2 -->
<section class="our-process2 mt-5 mb-4">
    <!-- <div class="parallax parallax--our-process2"style="background-image: url(<?= \Yii::$app->request->baseUrl . "/uploads/" . $setting[0]['gambar_header'] ?>);"> -->
    
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title title-3 title--dark">
                        Pilih Penawaran
                    </h2>
                </div>
            </div>
            <div class="row">
                <?php

                use dmstr\helpers\Html;

                // foreach ($models as $model) {
                $no = 1;
                for ($i = 0; $i < $count; $i++) {
                ?>
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="our-process__item" style="padding:2rem">
                            <h3>
                            <i class="zmdi zmdi-city-alt"></i>
                                Pilihan <?= $no ?>
                            </h3>
                            <?php
                            $penawarans = \app\models\PenawaranDetail::find()->where(['kode_penawaran' => $models[$i]['penawaran_id']])->limit(4)->all();
                            // var_dump($penawarans);
                            foreach ($penawarans as $tawaran) {
                            ?>
                                <li class="text-white"><?= $tawaran->supplierBarang->nama_barang . " x " . $tawaran->jumlah ?></li>
                            <?php } ?>
                            <p><?= \app\components\Angka::toReadableHarga($models[$i]['total_harga_penawaran']) ?></p>
                            <div class="text-center">
                                <?= Html::a('Detail', ['detail-penawaran-project', 'id' => $models[$i]['penawaran_id']], ['class' => 'au-btn au-btn--small btn-block au-btn--pill au-btn--yellow au-btn--white text-dark mt-4']) ?>
                            </div>
                        </div>
                    </div>
                <?php $no++;
                } ?>
            </div>
        </div>
    <!-- </div> -->
</section>
<!-- End Our Process 2 -->
<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
?>