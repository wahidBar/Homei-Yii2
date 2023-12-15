<?php

use app\components\annex\ActiveForm;
use dmstr\helpers\Html;
use richardfan\widget\JSRegister;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\MaskedInput;

Modal::begin([
    "id" => "modal",
    "size" => "modal-dialog-centered",
    "header" => "<h3>Hitung Kebutuhan Anda</h3>"
]);
?>
<div id="modalcontent">
    <div class="form-group">
        <label for="">
            Masukkan Volume yang anda butuhkan :
        </label>
        <!-- <input type="number" name="volume" class="form-control" id="inputvolume"> -->
        <?=
        MaskedInput::widget([
            'name' => 'volume',
            'id' => 'inputvolume',
            'class' => 'form-control',
            'clientOptions' => [
                'alias' =>  'decimal',
                'groupSeparator' => ',',
                'autoGroup' => true
            ],
        ]);
        ?>
    </div>
    <div class="form-group">
        <button id="hitung" class="btn btn-primary mr-1 mt-1">
            Hitung
        </button>
    </div>

    <div class="">
        Hasil : <span id="hasilhitung"></span>
    </div>

</div>
<?php Modal::end() ?>
<!-- Breadcrumb -->
<section class="breadcrumbs-wrap">
    <div class=" section-content section-content--w1140">
        <div class="container clearfix">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-8 col-6" style="border-top: 1px solid #ededed;">
                    <a href="<?=
                                Url::to([
                                    "/home/bahan-material/index",
                                ])
                                ?>" class="btn btn-sm btn-warning mb-3" style="margin-top:25px">
                        <?= Yii::t("cruds", "Kembali") ?>
                    </a>
                </div>
                <?= $this->render('components/_keranjang', [
                    'jumlah_carts' => $jumlah_carts,
                    'carts' => $carts
                ]) ?>
            </div>
        </div>
    </div>
</section>
<!-- End Breadcrumb -->
<!-- Single Product -->
<section class="single-product">
    <div class=" section-content section-content--w1140">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-6">
                    <div class="single-product-img">
                        <img alt="Product Big" src="<?= Yii::getAlias("@file/" . $barang->gambar) ?>">
                    </div>
                </div>
                <div class="col-lg-7 col-md-6">
                    <div class="single-product-detail">
                        <h2><?= $barang->nama_barang ?></h2>
                        <div class="pro__price">
                            <span class="current">
                                <?= Yii::t("cruds", "Harga Ritel : ") ?>
                                <?php
                                $min_beli_vol = $barang->minimal_beli_volume;
                                $max_beli_vol = $barang->minimal_beli_volume - 1;
                                echo \app\components\Angka::toReadableHarga($barang->harga_ritel);
                                if ($barang->satuan->nama == "m2") {
                                    echo ' / m<sup>2</sup>';
                                } elseif ($barang->satuan->nama == "m3") {
                                    echo ' / m<sup>3</sup>';
                                } else {
                                    echo ' / ' . $barang->satuan->nama;
                                }
                                echo "<br>";
                                echo "<p style='font-size:12px'>" . Yii::t("cruds", "Maks. pembelian ritel : ") . $max_beli_vol . "</p>";
                                ?>

                                <?= Yii::t("cruds", "Harga Proyek : ") ?>
                                <?php
                                echo \app\components\Angka::toReadableHarga($barang->harga_proyek);
                                if ($barang->satuan->nama == "m2") {
                                    echo ' / m<sup>2</sup>';
                                } elseif ($barang->satuan->nama == "m3") {
                                    echo ' / m<sup>3</sup>';
                                } else {
                                    echo ' / ' . $barang->satuan->nama;
                                }
                                echo "<br>";
                                echo "<p style='font-size:12px'>" . Yii::t("cruds", "Min. pembelian proyek : ") . $min_beli_vol . "</p>";
                                echo Yii::t("cruds", "Stok : $barang->stok");
                                ?>
                            </span>
                        </div>
                        <p>
                            <?php
                            $num_char = 100;
                            $text = $barang->deskripsi;
                            echo substr($text, 0, $num_char) . ".";
                            ?>
                        </p>
                        <div class="single-product-form">
                            <?php
                            if ($barang->stok > 1) :
                                $form = ActiveForm::begin([
                                    'id' => 'Supplier',
                                    'layout' => 'horizontal',
                                    'enableClientValidation' => true,
                                    'errorSummaryCssClass' => 'error-summary alert alert-error',
                                    'fieldConfig' => [
                                        'horizontalCssClasses' => [
                                            'label' => '',
                                            'offset' => '',
                                            'wrapper' => '',
                                            'error' => '',
                                            'hint' => '',
                                        ],
                                    ],
                                ]);
                            ?>
                                <div class="quantity">
                                    <div class="row">
                                        <div class="col-lg-2 col-4">
                                            <?= $form->field($model, "jumlah", [
                                                'inputOptions' => [
                                                    'class' => '',
                                                    'type' => 'text',
                                                    'min' => 1,
                                                    'max' => $barang->stok,
                                                    'value' => $model->jumlah ?? 1,
                                                    'placeholder' => false,
                                                ],
                                                'labelOptions' => [
                                                    'class' => ''
                                                ],
                                                'options' => ['tag' => false]
                                            ])->textInput()->label(false) ?>
                                            <div class="quantity-nav">
                                                <div class="quantity-button quantity-up">
                                                    <i class="zmdi zmdi-chevron-up"></i>
                                                </div>
                                                <div class="quantity-button quantity-down">
                                                    <i class="zmdi zmdi-chevron-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-8">
                                            <?= Html::submitButton(Yii::t("cruds", "+ keranjang"), ['class' => 'btn btn-lg btn-warning text-dark']); ?>
                                        </div>
                                        <!-- <div class="col-lg-4 col-12">
                                            <a id="btnmodal" class="btn btn-lg btn-warning text-dark btn-block btn-hitung">
                                                <?= Yii::t("cruds", "Hitung") ?>
                                            </a>
                                        </div> -->
                                    </div>
                                    <!-- <input type="number" min="1" max="999" tep="1" value="1"> -->
                                </div>

                            <?php ActiveForm::end();
                            else :
                            ?>
                                <p class="font-weight-bold text-danger"><?= Yii::t("cruds", "Stok Kosong") ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="single-product-tab">
                            <ul class="nav nav-tabs" id="pro-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#description" role="tab" aria-controls="all" aria-expanded="true"><?= Yii::t("cruds", "Deskripsi") ?></a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pro-content">
                                <div class="tab-pane active" id="description" role="tabpanel" aria-labelledby="home-tab">
                                    <?= $barang->deskripsi ?>
                                </div>
                            </div>
                            <!-- End Tab Content   -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-center sbold m-t-70 m-b-5"><?= Yii::t("cruds", "Barang terkait") ?></h3>
                </div>
            </div>
            <div class="row">
                <?php foreach ($barang_terkaits as $barang) { ?>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                        <div class="pro__item">
                            <div class="pro__img">
                                <img alt="Product 1" src="<?= Yii::getAlias("@file/" . $barang->gambar) ?>">
                                <div class="pro-link">
                                    <div class="pro-info pro-info--dark pro-info--center">
                                        <a href=" 
                                        <?=
                                        Url::to([
                                            "/home/bahan-material/add-to-cart",
                                            "id" => $barang->slug,
                                            "barang" => Yii::$app->request->get("id"),
                                        ])
                                        ?>" class="btn btn-sm btn-warning">
                                            <?= Yii::t("cruds", "Tambah ke keranjang") ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="pro__detail">
                                <h5>
                                    <a href="
                                    <?=
                                    Url::to([
                                        "/home/bahan-material/view",
                                        "id" => $barang->slug,
                                    ])
                                    ?>">
                                        <?= $barang->nama_barang ?>
                                    </a>
                                </h5>
                                <div class="pro__price">
                                    <span class="current">
                                        <?php
                                        echo \app\components\Angka::toReadableHarga($barang->harga_ritel);
                                        if ($barang->satuan->nama == "m2") {
                                            echo ' / m<sup>2</sup>';
                                        } elseif ($barang->satuan->nama == "m3") {
                                            echo ' / m<sup>3</sup>';
                                        } else {
                                            echo ' / ' . $barang->satuan->nama;
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- End Item -->
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
<!-- End Single Product -->
<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile("@web/homepage/js/cart-input.js", ['position' => \yii\web\View::POS_END]);
?>
<?php JSRegister::begin() ?>
<script>
    $('#btnmodal').on('click', (event) => {
        $('#modal').modal({
            show: true
        });
    })

    $(window).ready(function() {

        $('#hitung').on('click', (event) => {
            var volume = $('#inputvolume').val();
            var volume = volume.replace(",", "");
            if (volume == "") return alert("Volume tidak boleh kosong");
            var form = new FormData;
            form.append('volume', volume);

            fetch("<?= Url::to(["hitung", "id" => $barang->id]) ?>", {
                    method: "POST",
                    body: form
                })
                .then(response => response.json())
                .then(response => {
                    if (response.success == false) {
                        return alert(response.message);
                    }

                    $("#hasilhitung").text(response.data)
                })
        })
    });
</script>
<?php JSRegister::end() ?>