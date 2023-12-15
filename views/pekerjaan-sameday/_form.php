<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use yii\helpers\Html;
use app\components\annex\ActiveForm;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var app\models\PekerjaanSameday $model
 * @var app\components\annex\ActiveForm $form
 */

$this->registerJsFile(Yii::getAlias("@web/tinymce/tinymce.min.js"));
$this->registerJs("
      tinymce.init({
        selector: '#pekerjaansameday-uraian_pekerjaan'
      });
");

\app\assets\MapAsset::register($this);

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

<?php $form = ActiveForm::begin([
    'id' => 'PekerjaanSameday',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary alert alert-error'
]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="clearfix"></div>
<div class="d-flex  flex-wrap">
    <?= $form->field($model, 'foto_lokasi', \app\components\Constant::COLUMN(1))->widget(FileInput::class, [
        "options" => [
            "accept" => "image/*"
        ],
        "pluginOptions" => [
            "maxFileSize" => 2048 * 2,
        ]
    ]) ?>

    <?= $form->field($model, 'id_kategori', \app\components\Constant::COLUMN(3))->widget(Select2::class, [
        'name' => 'class_name',
        'model' => $model,
        'attribute' => 'id_kategori',
        'data' => \yii\helpers\ArrayHelper::map(app\models\MasterKategoriLayananSameday::find()->all(), 'id', 'nama_kategori_layanan'),
        'options' => [
            'placeholder' => $model->getAttributeLabel('id_kategori'),
            'multiple' => false,
            'disabled' => (isset($relAttributes) && isset($relAttributes['id_kategori'])),
        ]
    ]) ?>

    <?= $form->field($model, 'id_pelanggan', \app\components\Constant::COLUMN(3))->widget(\kartik\select2\Select2::classname(), [
        'model' => $model,
        'data' => \yii\helpers\ArrayHelper::map(app\models\User::find()
            ->where([
                'id' => $model->id_pelanggan,
                'flag' => 1,
                'role_id' => 3,
            ])->all(), 'id', 'name'),
        'options' => [
            'placeholder' => $model->getAttributeLabel('id_pelanggan'),
            'multiple' => false,
        ],
        "pluginOptions" => [
            'minimumInputLength' => 3,
            "allowClear" => true,
            'language' => [
                'errorLoading' => new \yii\web\JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => Url::to(['/user/get-user']),
                'dataType' => 'json',
                'data' => new \yii\web\JsExpression('function(params) { 
                    let id = $("#pekerjaansameday-id_pelanggan").val();
                    return {q:params.term, id: id}; 
                }')
            ],
            'escapeMarkup' => new \yii\web\JsExpression('function (markup) { return markup; }'),
            'templateResult' => new \yii\web\JsExpression('function(city) { return city.text; }'),
            'templateSelection' => new \yii\web\JsExpression('function (city) { return city.text; }'),
        ]
    ]); ?>

    <?= $form->field($model, 'nama_pelanggan', \app\components\Constant::COLUMN(3))->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'uraian_pekerjaan', \app\components\Constant::COLUMN(1))->textarea(['rows' => 6]) ?>

    <div class="col-md-12 col-lg-12 m-1 text-center">
        <b>Cari Lokasi</b> : <input id="searchTextField" class="form-control" type="text" size="50" style="text-align: left;width:357px;direction: ltr;margin-bottom:1rem;margin: 1rem auto" class="text-center">
        <div id="map_canvas"></div>
    </div>
    <div class="clearfix"></div>
    <?= $form->field($model, 'alamat_pelanggan', \app\components\Constant::COLUMN(1))->textarea(['rows' => 3]) ?>
    <?= $form->field($model, 'latitude')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'longitude')->hiddenInput()->label(false) ?>
    <div class="clearfix"></div>
</div>
<hr />
<div class="row">
    <div class="col-md-offset-3 col-md-10">
        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']); ?>
        <?= Html::a('<i class="fa fa-chevron-left"></i> Kembali', ['index'], ['class' => 'btn btn-default']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php \richardfan\widget\JSRegister::begin() ?>
<script>
    $('#pekerjaansameday-id_pelanggan').on("change", (event) => {
        let user_data = fetch("<?= Url::to(['/home/api/get-user', 'id' => '']) ?>" + event.target.value).then(response => response.json()).then(response => {
            $("#pekerjaansameday-nama_pelanggan").val(response.data.name);
            $("#pekerjaansameday-alamat_pelanggan").val(response.data.address);
        })
    });
</script>
<?php \richardfan\widget\JSRegister::end() ?>

<?php $js = <<<JS
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
        // $('#proyek-company_address').val(pass);
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
