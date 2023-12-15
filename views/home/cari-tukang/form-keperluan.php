<?php

use dmstr\helpers\Html;
use yii\grid\GridView;
use app\components\annex\ActiveForm;
use app\components\Constant;
use app\models\MasterKategoriLayananSameday;
use dosamigos\selectize\SelectizeDropDownList;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;

\app\assets\MapAsset::register($this);

$setting = \app\models\SiteSetting::find()->all();
$this->registerCssFile("@web/homepage/vendor/owl-carousel/animate.css");
$this->registerCssFile("@web/homepage/vendor/owl-carousel/owl.carousel.min.css");
$this->registerCssFile("@web/homepage/vendor/owl-carousel/owl.theme.default.min.css");
// $this->registerCssFile("@web/homepage/vendor/revolution/settings.css");
$this->registerCss("
.overlay {
    position:absolute;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
}
");

$this->registerJsFile(Yii::getAlias("@web/tinymce/tinymce.min.js"));
$this->registerJs("
      tinymce.init({
        selector: '#pekerjaansameday-uraian_pekerjaan'
      });
");
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
                        <li>
                            <a href="<?= \Yii::$app->request->BaseUrl ?>/home/cari-tukang/index">Cari Tukang</a>
                        </li>
                        <span>/</span>
                        <li class="active">
                            <a href="<?= \Yii::$app->request->BaseUrl ?>/home/cari-tukang/form-keperluan">Form Keperluan
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Navigation -->

<!-- Contact content -->
<section class="contact-content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="form-contact-wrap m-t-20">
                    <h4>Form Keperluan</h4>
                    <?php $form = ActiveForm::begin([
                        'id' => 'PekerjaanSameday',
                        'layout' => 'horizontal',
                        'enableClientValidation' => true,
                        'errorSummaryCssClass' => 'error-summary alert alert-error'
                    ]);
                    ?>
                    <?php echo $form->errorSummary($model); ?>

                    <?php
                    // echo $form->field($model, 'id_kategori', \app\components\Constant::COLUMN(1))->widget(
                    //     SelectizeDropDownList::class,
                    //     [
                    //         "items" => ArrayHelper::map(MasterKategoriLayananSameday::find()->all(), 'id', 'nama_kategori_layanan'),
                    //         "options" => [
                    //             "multiple" => true,
                    //             'prompt' => "--Pilih Kategori--",
                    //         ],
                    //         "clientOptions" => [
                    //             'persist' => false,
                    //             'maxItems' => null,
                    //             'plugins' => ['remove_button'],
                    //             'valueField' => 'id',
                    //             'labelField' => 'name',
                    //             'searchField' => ['name'],
                    //             'create' => false,
                    //         ],
                    //     ]
                    // ) 
                    ?>
                    <?= $form->field($model, 'nama_pelanggan', \app\components\Constant::COLUMN(1))->textInput(['maxlength' => true, 'class' => 'form-control mb-3', 'value' => ($user = Constant::getUser()) ? $user->name : ""]) ?>
                    <?= $form->field($model, 'foto_lokasi', \app\components\Constant::COLUMN(1))->widget(FileInput::class, [
                        "pluginOptions" => [
                            "maxFileSize" => 2048 * 5,
                        ],
                        "options" => [
                            "accept" => "image/*",
                        ]
                    ]) ?>
                    <?= $form->field($model, 'uraian_pekerjaan', \app\components\Constant::COLUMN(1))->textarea(['rows' => 6, 'class' => 'form-control mt-3 mb-3']) ?>
                    <div class="col-md-12 col-lg-12 m-1 text-center mt-3 mb-3">
                        <b>Cari Lokasi</b> : <input id="searchTextField" class="form-control" type="text" size="50" style="margin: 1rem auto" class="text-center">
                        <div id="map_canvas"></div>
                    </div>
                    <?= $form->field($model, 'alamat_pelanggan', \app\components\Constant::COLUMN(1))->textarea(['rows' => 6, 'class' => 'form-control mb-3']) ?>
                    <?= $form->field($model, 'latitude')->hiddenInput()->label(false) ?>
                    <?= $form->field($model, 'longitude')->hiddenInput()->label(false) ?>
                   
                    <hr />

                    <div class="text-left ml-4 mb-5">
                        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success m-0']); ?>
                        <?= Html::a('<i class="fa fa-chevron-left"></i> Kembali', ['index'], ['class' => 'btn btn-default']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="m-t-20">
                    <h4><?= $kategori->nama_kategori_layanan ?></h4>
                    <p><?= $kategori->deskripsi ?></p>
                    <img alt="Service 1" src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $kategori->icon ?>">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Contact Content -->

<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
?>
<?php
$js = <<<JS
$(function() {
    let id_lat = $('#pekerjaansameday-latitude'),
        id_lng = $('#pekerjaansameday-longitude'),
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
            $('#pekerjaansameday-alamat_pelanggan').val(pass);
        });
        google.maps.event.addListener(map, 'click', function(event) {
            id_lat.val(event.latLng.lat());
            id_lng.val(event.latLng.lng());
            infowindow.close();
            let geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                "latLng": event.latLng
            }, function(results, status) {
                // console.log(results, status);
                if (status == google.maps.GeocoderStatus.OK) {
                    // console.log(results);
                    let lat = results[0].geometry.location.lat(),
                        lng = results[0].geometry.location.lng(),
                        placeName = results[0].address_components[0].long_name,
                        latlng = new google.maps.LatLng(lat, lng);
                    moveMarker(placeName, latlng);
                    $("#searchTextField").val(results[0].formatted_address);
                    $('#pekerjaansameday-alamat_pelanggan').val(results[0].formatted_address);
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