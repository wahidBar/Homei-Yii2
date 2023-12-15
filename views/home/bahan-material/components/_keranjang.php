<div class="col-lg-4 col-lg-4 col-md-4 col-sm-3 col-6" style="border-top: 1px solid #ededed;">
    <div class="sort-right pull-right">
        <div class="mini-cart pull-right">
            <a href="#" class="font-weight-bold" id="cart-button" onclick="return false;">
                <i class="fa fa-shopping-cart mr-3"></i>
                <span class="mini-cart-counter"><?= $jumlah_carts ?></span>
                <?= Yii::t("cruds", "Keranjang") ?>
            </a>
            <!-- cart -->
            <div class="cart-dropdown cart-dropdown--hidden">
                <ul class="cart-list ul--no-style">
                    <?php

                    foreach ($carts as $cart) { ?>
                        <li id="cart-item-<?= $cart->kode_unik ?>">
                            <div class="cart__item">
                                <div class="img-thumb">
                                    <img alt="<?= $cart->supplierBarang->nama_barang ?>" src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $cart->supplierBarang->gambar ?>">
                                    <a class="mini-cart-counter mini-cart-counter--gray" href="<?= \yii\helpers\Url::to([
                                                                                                    "/home/bahan-material/hapus-item",
                                                                                                    "id" => $cart->kode_unik,
                                                                                                    'remember_url' => \yii\helpers\Url::current()
                                                                                                ]) ?>">
                                        -
                                    </a>
                                </div>
                                <div class="pro-detail">
                                    <h6>
                                        <a href="<?= \Yii::$app->request->BaseUrl . "/home/supplier-ritel/view?id=" . $cart->supplierBarang->slug . "&barang=" . $_GET['id'] ?>">
                                            <?= $cart->supplierBarang->nama_barang ?>
                                        </a>
                                    </h6>
                                    <p>
                                        <em>
                                            <?php
                                            $satuan_id = $cart->supplierBarang->satuan_id;
                                            $master_satuan = \app\models\MasterSatuan::find()->where(['id' => $satuan_id])->one();
                                            ?>
                                            <?= Yii::t("cruds", "Harga satuan") ?>(<?= $master_satuan->nama ?>) : <br><?= \app\components\Angka::toReadableHarga($cart->supplierBarang->harga_ritel) ?>
                                        </em>
                                    </p>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
                <div class="total-checkout">
                    <p><?= Yii::t("cruds", "Lihat lebih lengkap di keranjang") ?></p>
                    <!-- <span id="cart-sumtotal">
                        <?= \app\components\Angka::toReadableHarga($subtotal_cart) ?>
                    </span> -->
                    <div class="checkout text-center mt-2">
                        <a href="<?= \yii\helpers\Url::to([
                                        "/home/bahan-material/keranjang"
                                    ]) ?>
                                            " class="au-btn au-btn--small au-btn--pill au-btn--border au-btn--gray"><?= Yii::t("cruds", "Keranjang") ?></a>
                    </div>
                </div>

            </div>
            <!-- end cart -->
        </div>
    </div>
</div>
<?php \richardfan\widget\JSRegister::begin(); ?>
<script>
    // window.removeCart = function(uniq) {
    //     let form = new FormData;
    //     form.append('_method', 'DELETE');

    //     fetch("<?= \yii\helpers\Url::to(['ajax-remove-item']) ?>?uniq=" + uniq, {
    //             method: "POST",
    //             body: form
    //         })
    //         .then(response => response.json())
    //         .then(response => {
    //             if (response.success === false) {
    //                 alert(response.message);
    //             } else {
    //                 alert(response.message);
    //                 $('#cart-item-' + uniq).remove();
    // $("#cart-sumtotal").text(response.data.sumtotal)
    //                 }
    //             });
    //     }
</script>
<?php \richardfan\widget\JSRegister::end(); ?>