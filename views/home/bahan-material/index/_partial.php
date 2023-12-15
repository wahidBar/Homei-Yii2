<?php

use yii\helpers\Url;
?>
<div class="col-lg-3 col-md-6 col-sm-6 col-6">
    <div class="pro__item">
        <div class="pro__img" style="
            background-image: url(<?= \Yii::$app->request->baseUrl . "/uploads/" .$item->gambar ?>);
            background-position: 50% 50%;
            background-size: cover;
        ">
            <div class="pro-link">
                <div class="pro-info pro-info--dark pro-info--center">
                    <a href="<?=
                                Url::to([
                                    "/home/bahan-material/add-to-cart",
                                    "barang" => Yii::$app->request->get("id"),
                                    "id" => $item->slug,
                                    "sub" => Yii::$app->request->get('sub'),
                                ])
                                ?>" class="btn btn-sm btn-warning">
                        <?= Yii::t("cruds", "Tambah ke Keranjang") ?>
                    </a>
                </div>
            </div>
        </div>
        <div class="pro__detail">
            <a href="<?=
                        Url::to([
                            "/home/bahan-material/view",
                            "barang" => Yii::$app->request->get("id"),
                            "id" => $item->slug,
                            "sub" => Yii::$app->request->get('sub'),
                        ]) ?>">
                <h5>
                    <?= $item->nama_barang ?>
                </h5>
            </a>

            <div class="pro__price">
                <span class="current">
                    <?php
                    echo \app\components\Angka::toReadableHarga($item->harga_proyek) . ' s/d ' . \app\components\Angka::toReadableHarga($item->harga_ritel) . "<br>";
                    if ($item->satuan->nama == "m2") {
                        echo ' per m<sup>2</sup>';
                    } elseif ($item->satuan->nama == "m3") {
                        echo ' per m<sup>3</sup>';
                    } else {
                        echo ' per ' . $item->satuan->nama;
                    }
                    ?>
                </span>
            </div>
        </div>
    </div>
    <!-- End Item -->
</div>