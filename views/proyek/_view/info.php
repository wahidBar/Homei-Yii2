<?php

use \yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$this->registerCssFile("@web/homepage/css/card.css");
\app\assets\AnnexAsset::register($this);
\app\assets\MapAsset::register($this);

$setting = \app\models\SiteSetting::find()->one();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <link rel="icon" type="image/png" href=<?= \Yii::$app->request->baseUrl . "/uploads/" . $setting->icon ?> />
    <meta charset="<?= Yii::$app->charset ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($setting->judul) ?></title>
    <script>
        var baseUrl = "<?= Yii::$app->urlManager->baseUrl ?>";
    </script>
    <?php $this->head() ?>
    <style>
        #map_canvas {
            width: 100%;
            height: 70vh;
            margin-bottom: 1rem;
            border-radius: 20px;
            box-shadow: 0 8px 4px 5px #eee;
        }
    </style>
</head>

<body class="fixed-left">
    <?php $this->beginBody() ?>
    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div>
    <div id="wrapper">
        <div class="content">
            <div class="page-content-wrapper ">

                <div class="container-fluid">
                    <div class="content-wrapper">
                        <section class="content">
                            <div style="margin: 1.5rem;"></div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="cardx cardx-1 text-center">
                                                <div class="cardx__icon"><i class="fa fa-building"></i>
                                                    <span class="font-weight-bold" style="font-size: 1rem;">
                                                        <?= $model->nama_proyek ?>
                                                    </span>
                                                </div>
                                                <p class="cardx__title text-center"><?= $model->deskripsi_proyek ?></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                            <div class="cardx cardx-2 text-center">
                                                <div class="cardx__icon"><i class="fa fa-dollar"></i>
                                                    <span class="font-weight-bold" style="font-size: 1rem;">Realisasi Dana</span>
                                                </div>
                                                <div class="cardx__title text-center">
                                                    <div class="row mx-auto">
                                                        <div class="col-6">
                                                            <p class="text-left ml-5">Total Anggaran</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <p class="text-right mr-5"><?= $model->getTotalAnggaran() ?></p>
                                                        </div>
                                                        <div class="col-6">
                                                            <p class="text-left ml-5">Total Pengeluaran</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <p class="text-right mr-5"><?= $model->getTotalPengeluaran() ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                            <div class="cardx cardx-3 text-center">
                                                <div class="cardx__icon"><i class="fa fa-clock-o"></i>
                                                    <span class="font-weight-bold" style="font-size: 1rem;">Sisa Waktu</span>
                                                </div>
                                                <div class="cardx__title text-center">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <p class=""><?= $model->getSisaHari() ?> Hari</p>
                                                        </div>
                                                        <div class="col-12">
                                                            <p class="">
                                                                <?= Yii::$app->formatter->asIddate($model->tanggal_awal_kontrak) ?>
                                                                -
                                                                <?= Yii::$app->formatter->asIddate($model->tanggal_akhir_kontrak) ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                                            <div class="cardx cardx-4 text-center" style="min-height: 120px;">
                                                <div class="cardx__icon mt-3"><i class="fa fa-money"></i>
                                                    <span class="font-weight-bold" style="font-size: 1rem;">Nilai Kontrak</span>
                                                </div>
                                                <div class="cardx__title text-center mt-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <p class=""><?= \Yii::$app->formatter->asRp($model->nilai_kontrak) ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                                            <div class="cardx cardx-1 text-center" style="min-height: 120px;">
                                                <div class="cardx__icon"><i class="fa fa-bar-charts"></i>
                                                    <span class="font-weight-bold" style="font-size: 1rem;">Deviasi Progress</span>
                                                </div>
                                                <div class="cardx__title text-center">
                                                    <div class="row mx-auto">
                                                        <div class="col-8">
                                                            <p class="text-left ml-4">Week Period</p>
                                                        </div>
                                                        <div class="col-4">
                                                            <p class="text-left"><?= $progress_minggu_ini['data'] ?>%</p>
                                                        </div>
                                                        <div class="col-8">
                                                            <p class="text-left ml-4">Deviasi</p>
                                                        </div>
                                                        <div class="col-4">
                                                            <p class="text-left"><?= $progress_minggu_ini['deviasi'] ?>%</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                                            <div class="cardx cardx-5 text-center" style="min-height: 120px;">
                                                <div class="cardx__icon mt-3"><i class="fa fa-hourglass-half"></i>
                                                    <span class="font-weight-bold" style="font-size: 1rem;">Realisasi Proyek</span>
                                                </div>
                                                <div class="cardx__title text-center mt-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <p class=""><?= $model->getRealisasiProyek() ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mt-4">
                                            <h2>Kurva S</h2>
                                            <div id="chart" style="width: calc(100% / 1 - 15px);">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mt-4">
                                            <h2>Lokasi</h2>
                                            <div id="map_canvas" style="width: calc(100% / 1 - 15px);">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div><!-- container -->


            </div> <!-- Page content Wrapper -->
        </div>
    </div>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
<?php

use richardfan\widget\JSRegister;

$this->registerJsFile("@web/homepage/charts/apexcharts.js");
?>
<?php JSRegister::begin(); ?>
<script>
    var options = {
        colors: ['#b84644', '#45A387'],
        series: [{
            name: 'Target Progress',
            data: <?= $target_perminggu ?>
        }, {
            name: 'Progress',
            data: <?= $progress_perminggu ?>
        }],
        chart: {
            height: 350,
            width: '100%',
            type: 'area'
        },
        dataLabels: {
            enabled: false
        },
        markers: {
            size: [4, 7]
        },
        stroke: {
            curve: 'smooth'
        },
        xaxis: {
            type: 'datetime',
            categories: <?= $daftar_tanggal  ?>,
        },
        tooltip: {
            x: {
                format: 'dd/MM/yy'
            },
        },
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();

    $(function() {
        let lat = <?= ($model->latitude_proyek) ? $model->latitude_proyek : 0 ?>,
            lng = <?= ($model->longitude_proyek) ? $model->longitude_proyek : 0; ?>,
            latlng = new google.maps.LatLng(lat, lng);
        let mapOptions = {
                center: new google.maps.LatLng(lat, lng),
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                panControl: true,
                panControlOptions: {
                    position: google.maps.ControlPosition.TOP_RIGHT
                },
                zoomControl: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.LARGE,
                    position: google.maps.ControlPosition.TOP_left
                }
            },
            map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions),
            marker = new google.maps.Marker({
                position: latlng,
                map: map,
            });
    });
</script>
<?php JSRegister::end(); ?>