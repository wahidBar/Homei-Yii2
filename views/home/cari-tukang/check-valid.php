<?php

use app\components\annex\ActiveForm;
use dmstr\helpers\Html;
use kartik\file\FileInput;
use richardfan\widget\JSRegister;
use yii\helpers\Url;
use app\components\annex\Modal;
use app\components\Tanggal;
use yii\grid\GridView;

date_default_timezone_set("Asia/Jakarta");
?>


<!-- Cart Wrap -->
<section class="cart-wrap">
    <div class=" section-content section-content--w1140">
        <h2 class="text-center">CEK KEASLIAN DOKUMEN</h2>
        <div class="container text-center" style="min-height: 40vh;">
            <?php if ($model) : ?>
                <table class="table">
                    <tr>
                        <th>Kategori Layanan</th>
                        <td class="text-left"><?= $model->kategori->nama_kategori_layanan ?></td>
                    </tr>
                    <tr>
                        <th>Nama Penerima</th>
                        <td class="text-left"><?= $model->pelanggan->username ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Pemesanan</th>
                        <td class="text-left"><?= Tanggal::toReadableDate($model->created_at, false) ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td class="text-left"><?= $model->getStatus() ?></td>
                    </tr>
                </table>
            <?php else : ?>
                <table class="table">
                    <tr>
                        <td>Data Tidak Ditemukan</td>
                    </tr>
                </table>
            <?php endif ?>
        </div>
    </div>
</section>
<!-- End Cart Wrap -->
<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
$this->registerJsFile("@web/homepage/js/cart-input.js", ['position' => \yii\web\View::POS_END]);
?>