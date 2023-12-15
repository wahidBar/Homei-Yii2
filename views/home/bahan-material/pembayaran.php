<?php

use app\components\annex\ActiveForm;
use dmstr\helpers\Html;
use kartik\file\FileInput;
use richardfan\widget\JSRegister;
use yii\helpers\Url;

\app\assets\MapAsset::register($this);
$pengaturan = \app\models\SiteSetting::find()->one();

date_default_timezone_set("Asia/Jakarta");

$to_time = strtotime($order->deadline_bayar);
$from_time = strtotime(date('Y-m-d H:i:s'));
$minute = round(abs($to_time - $from_time) / 60, 2);

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


<!-- Breadcrumb -->
<section class="breadcrumbs-wrap">
    <div class=" section-content section-content--w1140">
        <div class="container clearfix">
            <div class="link-back">
                <a href="<?=
                            Url::to([
                                "/home/bahan-material/index",
                            ])
                            ?>" class="au-btn au-btn--pill au-btn--small au-btn--yellow" style="margin-top:25px">
                    <?= Yii::t("cruds", "Kembali") ?>
                </a>
            </div>
        </div>
    </div>
</section>
<!-- End Breadcrumb -->
<!-- Cart Wrap -->
<section class="cart-wrap">
    <div class=" section-content section-content--w1140">
        <div class="container">
            <div class="table-responsive">
                <table class="table table-cart">
                    <thead>
                        <tr>
                            <th><?= Yii::t("cruds", "No Nota") ?></th>
                            <th><?= Yii::t("cruds", "Total Bayar") ?></th>
                            <th><?= Yii::t("cruds", "Bukti Pembayaran") ?></th>
                            <th><?= Yii::t("cruds", "Batas Pembayaran") ?></th>
                            <th><?= Yii::t("cruds", "Alamat") ?></th>
                            <th><?= Yii::t("cruds", "Status") ?></th>
                            <?php if ($order->alasan_tolak != null) { ?>
                                <th><?= Yii::t("cruds", "Alasan Tolak") ?></th>
                            <?php } ?>
                            <th><?= Yii::t("cruds", "Invoice") ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $order->no_nota ?></td>
                            <td><?= \app\components\Angka::toReadableHarga($order->total_harga) ?></td>
                            <td>
                                <?php
                                $link =  $order->bukti_bayar;
                                $absolutelink = Yii::getAlias("@file/$link");
                                if (\app\components\Constant::checkFile($link)) {
                                    echo "<a href='$absolutelink' class='btn btn-primary text-white' target='_blank'>Download</a>";
                                } else {
                                    echo "<span  class='badge badge-warning'>Belum Dibayar</span>";
                                }
                                ?>
                            </td>
                            <td><?= \app\components\Tanggal::toReadableDate($order->deadline_bayar) ?></td>
                            <td><?= $order->alamat ?></td>
                            <td>
                                <?php
                                // if ($minute <= $pengaturan->batas_pembayaran) {
                                //     if ($order->status == 0) {
                                //         echo "<span  class='badge badge-warning'>Belum Dibayar</span>";
                                //     } elseif ($order->status == 1) {
                                //         echo "<span  class='badge badge-info'>Pengecekan Pembayaran</span>";
                                //     } elseif ($order->status == 2) {
                                //         echo "<span  class='badge badge-success'>Pembayaran Lunas</span>";
                                //     } elseif ($order->status == 3) {
                                //         echo "<span  class='badge badge-danger'>Pembayaran Ditolak</span>";
                                //     }
                                // } else {
                                //     echo "<span  class='badge badge-danger'>Pembayaran Kadaluarsa</span>";
                                // }
                                if (($order->status == $order::STATUS_BELUM_BAYAR || $order->status == $order::STATUS_PEMBAYARAN_DIBATALKAN) && time() > strtotime($order->deadline_bayar)) {
                                    $order->status = $order::STATUS_PEMBAYARAN_EXPIRED;
                                    $order->save();
                                }

                                if ($order->status == $order::STATUS_BELUM_BAYAR) :
                                    echo Html::a('Bayar', ['pembayaran', 'id' => $order->kode_unik], ['class' => 'btn btn-sm btn-warning text-center']);
                                elseif ($order->status == $order::STATUS_MENUNGGU_KONFIRMASI_ADMIN) :
                                    echo "<span  class='badge badge-info'>Dalam Pengecekan</span>";
                                elseif ($order->status == 2) :
                                    echo Html::a('Cek Pengiriman', ['proses-pengiriman', 'id' => $order->kode_unik], ['class' => 'btn btn-sm btn-info text-white']);
                                elseif ($order->status == $order::STATUS_PEMBAYARAN_DIBATALKAN) :
                                    echo Html::a('Ditolak (Bayar Ulang)', ['pembayaran', 'id' => $order->kode_unik], ['class' => 'btn btn-sm btn-danger text-white']);
                                elseif ($order->status == $order::STATUS_PEMBAYARAN_EXPIRED) :
                                    echo "<span  class='badge badge-danger'>Pembayaran Kadaluarsa</span>";
                                elseif ($order->status == 4) :
                                    echo "<span  class='badge badge-success'>Pesanan Diterima</span>";
                                endif;
                                ?>
                            </td>
                            <?php if ($order->alasan_tolak != null) { ?>
                                <td><?= $order->alasan_tolak ?></td>
                            <?php } ?>
                            <td>
                                <?= Html::a('Lihat Invoice', ['cetak-invoice', 'id' => $order->kode_unik], ['class' => 'btn btn-sm btn-info text-white']); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="table-responsive">
                        <table class="table table-cart">
                            <thead>
                                <tr>
                                    <th><?= Yii::t("cruds", "Barang") ?></th>
                                    <th><?= Yii::t("cruds", "Jumlah") ?></th>
                                    <th><?= Yii::t("cruds", "Subtotal") ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($daftar_barangs as $barang) { ?>
                                    <tr>
                                        <td><?= $barang->supplierBarang->nama_barang ?> </td>
                                        <td><?= $barang->jumlah ?> <?= $barang->supplierBarang->satuan->nama ?> </td>
                                        <td><?= \app\components\Angka::toReadableHarga($barang->subtotal) ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="2" class="font-weight-bold">Total</td>
                                    <td class="font-weight-bold"><?= \app\components\Angka::toReadableHarga($order->total_harga) ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="col-12 mb-3">
                        <h4>Transfer Pembayaran :</h4>
                        <?php foreach ($pembayarans as $bank) : ?>
                            <p><strong><?= $bank->nama_bank ?></strong> : <?= $bank->nomor_rekening ?> (<?= $bank->atas_nama ?>)</p>
                        <?php endforeach ?>
                    </div>
                    </div>
                </div>
                <?php

                if ($minute <= $pengaturan->batas_pembayaran) {
                    if ($order->status == 0 || $order->status == 3) {
                ?>
                        <div class="col-lg-6">
                            <div class="container">
                                <?php $form = ActiveForm::begin([
                                    'id' => 'SupplierOrder',
                                    'layout' => 'horizontal',
                                    'enableClientValidation' => true,
                                    'errorSummaryCssClass' => 'error-summary alert alert-error'
                                ]);
                                ?>
                                <?php echo $form->errorSummary($order); ?>
                                <div class="row">
                                    <div class="">
                                        <div class="col-lg-12 col-12 layout-spacing">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h4>Upload Pembayaran</h4>
                                                    <div class="card m-b-30">
                                                        <div class="card-body">
                                                            <div class="d-flex  flex-wrap">
                                                                <div class="clearfix"></div>
                                                                <?= $form->field($order, 'bukti_bayar', \app\components\Constant::COLUMN(1))->widget(FileInput::class, [
                                                                    'options' => [
                                                                        'preview' => false
                                                                        // 'accept' => 'image/*'
                                                                    ]
                                                                ]) ?>
                                                                <?= $form->field($order, 'keterangan_bayar', \app\components\Constant::COLUMN(1))->textarea(['rows' => 6, 'value' => $order->no_nota]) ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="card m-b-30">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="ml-4 col-md-12 text-left">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php }
                } ?>
            </div>
        </div>
    </div>
</section>
<!-- End Cart Wrap -->
<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile("@web/homepage/js/cart-input.js", ['position' => \yii\web\View::POS_END]);
?>
<?php
$js = <<<JS
$(function() {
    console.log($('#checkout-latitude').val());
let id_lat = $('#checkout-latitude'),
    id_lng = $('#checkout-longitude'),
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
        $('#checkout-alamat_pengiriman').val(pass);
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
                $('#checkout-alamat_pengiriman').val(results[0].formatted_address);
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