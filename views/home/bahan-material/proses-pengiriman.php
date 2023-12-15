<?php

use dmstr\helpers\Html;

$setting = \app\models\SiteSetting::find()->all();

$this->registerCssFile("@web/homepage/css/timeline.css");
$this->registerCss("
.overlay {
    position:absolute;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
}
");
?>
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
                            <a href="#">Proses Pengiriman</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Navigation -->
<!-- Blog Grid 2 -->
<div class="blog1 blog2" style="background-color:#f1f1f1">
    <div class="container-fluid">
        <div class="widget-header mb-2">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title title-3 title--dark">
                        Proses Pengiriman
                    </h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <h4 class="ml-2 mb-2">Detail Pesanan</h4>
                                <tr>
                                    <th scope="col">Kode Pesanan</th>
                                    <td><?= $order->no_nota ?></td>
                                </tr>
                                <!-- <tr>
                                    <th scope="row">Dikirim Oleh</th>
                                    <td>Ekspedisi Homei</td>
                                </tr> -->
                                <tr>
                                    <th scope="row">Dikirim Ke</th>
                                    <td><?= $order->alamat ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Status Pengiriman</th>
                                    <td>
                                        <?php
                                        if ($order->status == 2) :
                                            echo "<span  class='badge badge-success'>Proses Pengantaran</span>";
                                        elseif ($order->status == 4) :
                                            echo "<span  class='badge badge-success'>Pesanan Diterima</span>";
                                        endif;
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Terima Pesanan</th>
                                    <td><?= Html::a('Terima Pesanan?', ['pesanan-diterima', 'id' => $order->kode_unik], ['class' => 'btn btn-sm btn-info text-white']); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="main-timeline">
                    <div class="timeline">
                        <h3>Detail Pelacakan Pengiriman Anda</h3>
                        <label>
                            <?php
                            $last_update = \app\models\SupplierPengiriman::find()->where(['kode_supplier_order' => $_GET['id']])->orderBy(['id' => SORT_DESC])->one();
                            echo \app\components\Tanggal::toReadableDate($last_update->created_at);
                            ?>
                        </label>
                        <div class="box">
                            <div class="container-timeline">
                                <div class="cards-timeline">
                                    <div class="row">
                                        <div class="col-1">
                                            <div class="lines">
                                                <div class="line"></div>
                                            </div>
                                        </div>
                                        <div class="col-11">
                                            <div class="lines">
                                                <div class="dot"></div>
                                            </div>
                                            <div class="card-timeline">
                                                <h4><?= \app\components\Tanggal::toReadableDate($order->tanggal_bayar) ?></h4>
                                                <p>Pembayaran Diterima</p>
                                            </div>

                                            <?php foreach ($models as $model) { ?>
                                                <div class="lines">
                                                    <div class="dot"></div>
                                                </div>
                                                <div class="card-timeline">
                                                    <h4><?= \app\components\Tanggal::toReadableDate($model->created_at) ?></h4>
                                                    <p>
                                                        <?= $model->keterangan ?>
                                                    </p>
                                                </div>
                                            <?php } ?>

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Blog Grid 2 -->

<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
?>