<?php

use app\components\annex\ActiveForm;
use dmstr\helpers\Html;
use richardfan\widget\JSRegister;
use yii\bootstrap\Modal;
use yii\helpers\Url;

\app\assets\MapAsset::register($this);
?>

<?php Modal::begin([
    "id" => "modal",
    "header" => "<h3>Tambahkan SPK</h3>"
]);
?>
<div id="modalcontent">
    <input type="hidden" id="inputuniqid">
    <div class="form-group">
        <label for="">
            No. SPK
        </label>
        <input type="text" name="inputnospk" class="form-control" id="inputnospk">
    </div>
    <div class="form-group">
        <label for="">
            Keterangan Proyek
        </label>
        <textarea name="inputketeranganproyek" id="inputketeranganproyek" cols="30" rows="10"></textarea>
    </div>
    <div class="form-group">
        <button id="hitung" class="btn btn-primary mr-1 mt-1" id="insertspk" onclick="insertspk()">
            Simpan
        </button>
    </div>
</div>
<?php Modal::end() ?>
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
                            ?>" class="btn btn-sm btn-warning" style="margin-top:25px">
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
            <?php $form = ActiveForm::begin([
                'id' => 'SupplierOrderCart',
                'layout' => 'horizontal',
                'enableClientValidation' => true,
                'errorSummaryCssClass' => 'error-summary alert alert-error'
            ]);
            ?>


            <table class="table table-responsive">
                <?php if ($jumlah_carts == 0) : ?>
                    <tr>
                        <td colspan="6" style="text-align: center;"><?= Yii::t("cruds", "Keranjang Kosong") ?></td>
                    </tr>
                    <tbody>
                    <?php else : ?>
                        <?php foreach ($models as $i => $model) : ?>
                            <tr id="<?= $model->kode_unik ?>">
                                <td class="text-left pro-cart">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-4">
                                            <div class="img-cart">
                                                <img alt="Product 1" src="<?= Yii::getAlias("@file/" . $model->supplierBarang->gambar) ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-8 col-6">
                                            <h5>
                                                <?= $model->supplierBarang->nama_barang ?>
                                            </h5>
                                            <div class="quantity">
                                                <h5>
                                                    <?= Yii::t("cruds", "Jumlah : ") ?>
                                                </h5>
                                                <?= Html::input("text", "input_" . $model->kode_unik, $model->jumlah, ["onkeydown" => "update(event, '$model->kode_unik')", "id" => "jumlah_" . $model->kode_unik]) ?>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-2">
                                            <div class="quantity-nav">
                                                <div class="quantity-button quantity-up" onclick="tambah('<?= $model->kode_unik ?>')">
                                                    <i class="zmdi zmdi-chevron-up"></i>
                                                </div>
                                                <div class="quantity-button quantity-down" onclick="kurang('<?= $model->kode_unik ?>')">
                                                    <i class="zmdi zmdi-chevron-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-6">
                                            <span class="font-weight-bold"><?= Yii::t("cruds", "Hrg. Ritel : ") ?>
                                                <?php
                                                $min_beli_vol = $model->supplierBarang->minimal_beli_volume;
                                                $max_beli_vol = $model->supplierBarang->minimal_beli_volume - 1;
                                                echo \app\components\Angka::toReadableHarga($model->supplierBarang->harga_ritel);
                                                if ($model->supplierBarang->satuan->nama == "m2") {
                                                    echo ' / m<sup>2</sup>';
                                                } elseif ($model->supplierBarang->satuan->nama == "m3") {
                                                    echo ' / m<sup>3</sup>';
                                                } else {
                                                    echo ' / ' . $model->supplierBarang->satuan->nama;
                                                }
                                                ?>
                                            </span><br>
                                            <?php if ($model->supplierBarang->minimal_beli_satuan > 0) :
                                                echo "<div style='font-size:12px'>" . Yii::t("cruds", "Maks beli : ") . $max_beli_vol . "</div>";
                                            endif; ?>
                                        </div>
                                        <div class="col-lg-4 col-6">
                                            <?php if ($model->supplierBarang->minimal_beli_satuan > 0) : ?>
                                                <span class="font-weight-bold"><?= Yii::t("cruds", "Hrg. Proyek : ") ?>
                                                <?php
                                                echo \app\components\Angka::toReadableHarga($model->supplierBarang->harga_proyek);
                                                if ($model->supplierBarang->satuan->nama == "m2") {
                                                    echo ' / m<sup>2</sup>';
                                                } elseif ($model->supplierBarang->satuan->nama == "m3") {
                                                    echo ' / m<sup>3</sup>';
                                                } else {
                                                    echo ' / ' . $model->supplierBarang->satuan->nama;
                                                }
                                                echo "</span><br>";
                                                echo "<div style='font-size:12px'>" . Yii::t("cruds", "Min beli : ") . $min_beli_vol . "</div>";
                                            endif; ?>
                                        </div>
                                        <div class="col-12">
                                            <div class="text-left font-weight-bold">
                                                <h5 id="rupiah_<?= $model->kode_unik ?>">
                                                    Subtotal : <?= \app\components\Angka::toReadableHarga($model->subtotal) ?>
                                                </h5>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-6 col-6">
                                            <a href="<?= Url::to([
                                                            "/home/bahan-material/hapus-item",
                                                            "id" => $model->kode_unik,
                                                        ]) ?>" class="remove btn btn-danger btn-sm btn-block text-center text-white">
                                                <i class="fa fa-trash"></i> Hapus
                                            </a>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-6">
                                            <div class="btn btn-sm btn-primary btn-block <?= $model->labelTampilkanTombolSpk() ?>" style="font-size: .8rem;" id="btn-spk-<?= $model->kode_unik ?>" onclick="showformspk('<?= $model->kode_unik ?>')">
                                                Input SPK
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                    <?php endforeach;
                    endif;
                    ?>
                    </tbody>
            </table>

            <?php if ($jumlah_carts) : ?>
                <!-- <div class="cart-button clearfix mt-5">
                    <button type="submit" class="au-btn au-btn--pill au-btn--big au-btn--yellow pull-right ">
                        <?= Yii::t("cruds", "Update Keranjang") ?>
                    </button>
                </div> -->
            <?php endif ?>
            <?php ActiveForm::end(); ?>

            <?php if ($jumlah_carts) : ?>
                <div class="cart-total">
                    <h4 class="text-left sbold m-b-20"><?= Yii::t("cruds", "KERANJANG TOTAL") ?></h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-cart-total m-b-30">
                            <tr>
                                <?php if ($proyek != null) : ?>
                                    <td>
                                        <?= Yii::t("cruds", "Proyek") ?>
                                    </td>
                                    <td>
                                        <?= $proyek ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                            <tr>
                                <td>
                                    <?= Yii::t("cruds", "Total") ?>
                                </td>
                                <td class="sbold total" id="totalharga">
                                    <?= \app\components\Angka::toReadableHarga($total_cart) ?>
                                </td>
                            </tr>
                            <form role="form" action="<?= \yii\helpers\Url::to(["/home/bahan-material/checkout"]) ?>" method="POST">
                                <tr>
                                    <td colspan="2">
                                        <b><?= Yii::t("cruds", "Cari Lokasi") ?></b> : <input id="searchTextField" class="form-control" type="text" size="50" style="text-align: left;width:357px;direction: ltr;margin-bottom:1rem;">
                                        <div id="map_canvas"></div>
                                        <input type="hidden" name="latitude" id="checkout-latitude">
                                        <input type="hidden" name="longitude" id="checkout-longitude">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?= Yii::t("cruds", "Nama Penerima") ?>
                                    </td>
                                    <td class="sbold total">
                                        <input type="text" name="nama_penerima" class="form-control" cols="30" rows="5" id="checkout-nama_penerima" value="<?= Yii::$app->user->identity->username ?>" required />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?= Yii::t("cruds", "Alamat Pengiriman") ?>
                                    </td>
                                    <td class="sbold total">
                                        <textarea name="alamat_pengiriman" class="form-control" cols="30" rows="5" id="checkout-alamat_pengiriman" required></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <button type="submit" class="btn btn-lg btn-warning"><?= Yii::t("cruds", "Checkout") ?></button>
                                    </td>
                                </tr>
                            </form>
                        </table>
                    </div>
                </div>
            <?php endif ?>
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
<?php JSRegister::begin(); ?>
<script>
    window.tambah = function(uniq) {
        fetch("<?= Url::to(['increment-product']) ?>?uniq=" + uniq)
            .then(response => response.json())
            .then(response => {
                if (response.success === false) {
                    alert(response.message);
                } else {
                    $('#totalharga').text(response.data.sumtotal);
                    $('#rupiah_' + uniq).text(response.data.subtotal);
                    $('#jumlah_' + uniq).val(response.data.jumlah);
                }

                /**
                 * menampilkan tombol
                 * insert spk
                 */
                if (("showbtn" in response.data)) {
                    $('#btn-spk-' + uniq).attr('class', 'btn btn-sm btn-primary btn-block');
                } else {
                    $('#btn-spk-' + uniq).attr('class', 'btn btn-sm btn-primary btn-block d-none');
                }
            });
    }

    window.kurang = function(uniq) {
        fetch("<?= Url::to(['decrement-product']) ?>?uniq=" + uniq)
            .then(response => response.json())
            .then(response => {
                if (response.success === false) {
                    alert(response.message);
                } else {
                    if (!("deleted" in response.data)) {
                        $('#totalharga').text(response.data.sumtotal);
                        $('#rupiah_' + uniq).text(response.data.subtotal);
                        $('#jumlah_' + uniq).val(response.data.jumlah);
                    } else {
                        $('#' + uniq).remove();
                        $('#totalharga').text(response.data.sumtotal);
                    }

                    /**
                     * menampilkan tombol
                     * insert spk
                     */
                    if (("showbtn" in response.data)) {
                        $('#btn-spk-' + uniq).attr('class', 'btn btn-sm btn-primary btn-block');
                    } else {
                        $('#btn-spk-' + uniq).attr('class', 'btn btn-sm btn-primary btn-block d-none');
                    }
                }
            });
    }

    window.update = function(event, uniq) {
        if (event.key == "Enter") {

            fetch("<?= Url::to(['update-product']) ?>?uniq=" + uniq + "&jumlah=" + $('#jumlah_' + uniq).val())
                .then(response => response.json())
                .then(response => {
                    if (response.success === false) {
                        alert(response.message);
                    } else {
                        if (!("deleted" in response.data)) {
                            $('#totalharga').text(response.data.sumtotal);
                            $('#rupiah_' + uniq).text(response.data.subtotal);
                            $('#jumlah_' + uniq).val(response.data.jumlah);
                        } else {
                            $('#' + uniq).remove();
                            $('#totalharga').text(response.data.sumtotal);
                        }

                        /**
                         * menampilkan tombol
                         * insert spk
                         */
                        if (("showbtn" in response.data)) {
                            $('#btn-spk-' + uniq).attr('class', 'btn btn-sm btn-primary btn-block');
                        } else {
                            $('#btn-spk-' + uniq).attr('class', 'btn btn-sm btn-primary btn-block d-none');
                        }
                    }
                });
        }
    }

    window.showformspk = function(uniq) {
        $('#inputuniqid').val(uniq);
        $("#modal").modal({
            show: true
        });
    }

    window.insertspk = function() {
        let uniq = $('#inputuniqid').val();
        let nospk = $('#inputnospk').val();
        let keterangan_proyek = $('#inputketeranganproyek').val();

        if (uniq == undefined || nospk == undefined || keterangan_proyek == undefined) {
            return alert("Anda wajib mengisi semua form");
        }

        if ((uniq && nospk && keterangan_proyek) == false) {
            return alert("Anda wajib mengisi semua form");
        }

        let body = new FormData;
        body.append('no_spk', nospk);
        body.append('keterangan_proyek', keterangan_proyek);

        fetch("<?= Url::to(['insert-spk']) ?>?uniq=" + uniq, {
                method: "POST",
                body,
            })
            .then(response => response.json())
            .then(response => {
                if (response.success === false) {
                    alert(response.message);
                } else {
                    $('#modal').modal('hide');
                    alert(response.message);
                    $('#rupiah_' + uniq).text(response.data.subtotal);
                    $('#totalharga').text(response.data.sumtotal);
                    $('#btn-spk-' + uniq).attr('class', 'btn btn-primary mr-1 mb-1 d-none');
                }
            });
    }

    $(document).ready(function() {


        var boq = "<?= \Yii::$app->session->getFlash('boq') ?>";
        if (boq !== "") {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success mr-2',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })
            swalWithBootstrapButtons.fire({
                title: '<strong>Peringatan!</strong>',
                icon: 'warning',
                html: '<?= \Yii::$app->session->getFlash('boq') ?>' +
                    '<br>Apakah Anda ingin memesan dalam jumlah besar? <br>(Anda akan mendapatkan potongan harga jika membeli dalam jumlah besar)',
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: '<a class="text-white" href="<?= Url::to(["/home/bahan-material/keranjang", "id" => $kode_boq,]) ?>">Ya</a>',
                cancelButtonText: 'Tidak',
            })
        }
    });
</script>
<?php JSRegister::end(); ?>