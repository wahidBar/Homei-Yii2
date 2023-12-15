<?php

use app\components\annex\ActiveForm;
use yii\helpers\Html;
use app\assets\MapAsset;
use yii\grid\GridView;
use richardfan\widget\JSRegister;

$setting = \app\models\SiteSetting::find()->all();
$this->registerCssFile("@web/homepage/css/sweetalert2.min.css");
$this->registerCss("

.overlay {
    position:absolute;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
}
");
MapAsset::register($this);

?>

<style>
    #map_canvas {
        width: 100%;
        height: 70vh;
        margin-bottom: 1rem;
        border-radius: 20px;
        box-shadow: 0 8px 4px 5px #eee;
    }
</style>
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
                            <a href="<?= \Yii::$app->request->BaseUrl ?>/home/form-rencana-pembangunan/index">Form Rencana Pembangunan</a>
                        </li>
                        <span>/</span>
                        <li class="active">
                            <a href="#">Buat</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Navigation -->
<div class="widget-header mt-2">
    <div class="row">
        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
            <h2 class="text-center">Formulir Rencana Pembangunan</h2>
        </div>
    </div>
</div>
<!-- Contact content -->
<section class="contact-content">
    <div class="container">
        <?php $form = ActiveForm::begin([
            'id' => 'IsianLanjutan',
            'layout' => 'horizontal',
            'enableClientValidation' => false,
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]);
        ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="row">
            <div class="col-md-6">
                <h4>Informasi Data Pelanggan</h4>
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="d-flex  flex-wrap">
                            <?= $this->render('_form_umum', compact('form', 'model')) ?>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h4>Data Ruangan</h4>
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="d-flex  flex-wrap">
                            <?= $this->render('_form_ruangan', compact('form', 'model')) ?>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <h4>Posisi Proyek</h4>
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="d-flex  flex-wrap">
                            <?= $form->field($model, 'latitude', ['template' => '{input}'])->hiddenInput(['maxlength' => true])->label(false) ?>
                            <?= $form->field($model, 'longitude', ['template' => '{input}'])->hiddenInput(['maxlength' => true])->label(false) ?>
                            <b>Cari Lokasi</b> : <br><input id="searchTextField" class="form-control" type="text" size="50" style="text-align: left;width:100%;direction: ltr;margin-bottom:1rem;">
                            <div id="map_canvas"></div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <h4>Informasi Alamat</h4>
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="d-flex  flex-wrap">
                            <?= $this->render('_form_wilayah', compact('form', 'model')) ?>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <h4>Informasi Tanah</h4>
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="d-flex  flex-wrap">
                            <?= $this->render('_form_isian', compact('form', 'model')) ?>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <h4>Informasi Proyek</h4>
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="d-flex  flex-wrap">
                            <?= $this->render('_form_proyek', compact('form', 'model')) ?>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']); ?>
                                <?= Html::a('<i class="fa fa-chevron-left"></i> Kembali', ['index'], ['class' => 'btn btn-default']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <?php ActiveForm::end(); ?>
    </div>
</section>
<?php
// $this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
$js = <<<JS
$(function() {
let id_lat = $('#isianlanjutan-latitude'),
    id_lng = $('#isianlanjutan-longitude'),
    lat = (id_lat.val() != "") ? id_lat.val() : -7.2674864,
    lng = (id_lng.val() != "")  ? id_lng.val() : 112.752035,
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
let input = document.getElementById('searchTextField');
let autocomplete = new google.maps.places.Autocomplete(input, {
    types: ["geocode"]
});
autocomplete.bindTo('bounds', map);
let infowindow = new google.maps.InfoWindow();
google.maps.event.addListener(autocomplete, 'place_changed', function(event) {
        infowindow.close();
        let place = autocomplete.getPlace();
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }
        moveMarker(place.name, place.geometry.location);
        id_lat.val(place.geometry.location.lat());
        id_lng.val(place.geometry.location.lng());
        let pass = document.getElementById('searchTextField').value;
        $('#isianlanjutan-alamat_proyek').val(place.formatted_address);
    });
    google.maps.event.addListener(map, 'click', function(event) {
        id_lat.val(event.latLng.lat());
        id_lng.val(event.latLng.lng());
        infowindow.close();
        let geocoder = new google.maps.Geocoder();
        geocoder.geocode({
            "latLng": event.latLng
        }, function(results, status) {
            
            if (status == google.maps.GeocoderStatus.OK) {
                
                let lat = results[0].geometry.location.lat(),
                    lng = results[0].geometry.location.lng(),
                    placeName = results[0].address_components[0].long_name,
                    latlng = new google.maps.LatLng(lat, lng);
                moveMarker(placeName, latlng);
                $("#searchTextField").val(results[0].formatted_address);
                $('#isianlanjutan-alamat_proyek').val(results[0].formatted_address);
            }
        });
    });
    function moveMarker(placeName, latlng) {
        marker.setPosition(latlng);
        infowindow.setContent(placeName);
        //infowindow.open(map, marker);
    }
});
JS;

$this->registerJs($js);
?>