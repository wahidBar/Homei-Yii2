<?php
$to_time = strtotime($model->deadline_bayar);
$from_time = strtotime(date('Y-m-d H:i:s'));
$minute = round(abs($to_time - $from_time) / 60, 2);

$status = "belum-bayar.png";
if ($model->status == \app\models\SupplierOrder::STATUS_BELUM_BAYAR) :
  $status = "belum-bayar.png";
elseif ($model->status == \app\models\SupplierOrder::STATUS_MENUNGGU_KONFIRMASI_ADMIN) :
  $status = "pengecekan.png";
elseif ($model->status == \app\models\SupplierOrder::STATUS_LUNAS || $model->status == \app\models\SupplierOrder::STATUS_SELESAI) :
  $status = "lunas.png";
elseif ($model->status == \app\models\SupplierOrder::STATUS_PEMBAYARAN_DIBATALKAN) :
  $status = "batal.png";
elseif ($model->status == \app\models\SupplierOrder::STATUS_PEMBAYARAN_EXPIRED) :
  $status = "expired.png";
endif;
if ($model->status == \app\models\SupplierOrder::STATUS_PEMBAYARAN_DIBATALKAN && $model->alasan_tolak !=null) :
  $status = "ditolak.png";
endif;

use Da\QrCode\QrCode;
use yii\helpers\Url;

$qrCode = (new QrCode(Url::to(["/home/bahan-material/check-valid", "id" => $model->kode_unik], true)))
  ->setSize(250)
  ->setMargin(5)
  ->useForegroundColor(0, 0, 0);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
  <div class="container bootstrap snippets" style="background-image: url('<?= Yii::getAlias("@web/pdf-status/$status") ?>'); background-repeat: no-repeat;background-position:center center">
    <div class="row">
      <div class="col-sm-12">
        <div class="panel panel-default invoice" id="invoice">
          <div class="panel-body">
            <table style="width: 100%;">
              <thead>
                <tr>
                  <th style="width:50%"></th>
                  <th style="width:50%"></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td rowspan="2"><img alt="Logo" src="<?= Yii::getAlias("@file/$setting->logo") ?>" width="100px"></td>
                  <td class="text-right"><?= $model->no_nota ?></td>
                </tr>
                <tr>
                  <td class="text-right"><?= \app\components\Tanggal::toReadableDate($model->tanggal_bayar) ?></td>
                </tr>
              </tbody>
            </table>

            <hr>
            <table class="table">
              <thead>
                <tr>
                  <td class="from" style="width:33%">
                    <p class="lead marginbottom">Dari : <?= $setting->judul ?></p>
                    <p><?= $setting->alamat ?></p>
                    <p>No. Telp: <?= $setting->no_telp ?></p>
                    <p>Email: <?= $setting->email ?></p>
                  </td>
                  <td class="to" style="width: 33%;">
                    <p class="lead marginbottom">Kepada : <?= $model->nama_penerima ?></p>
                    <p><?= $model->alamat ?></p>
                    <p>No. Telp: <?= $user->no_hp ?></p>
                    <p>Email: <?= $user->email ?></p>
                  </td>
                  <td class="text-right payment-details">
                    <p class="lead marginbottom payment-info">Detail Pembayaran</p>
                    <p>Tanggal: <?= \app\components\Tanggal::toReadableDate($model->tanggal_bayar) ?></p>
                    <p>Batas Pembayaran: <?= \app\components\Tanggal::toReadableDate($model->deadline_bayar) ?></p>
                    <p>Total: <?= \app\components\Angka::toReadableHarga($model->total_harga) ?></p>
                    <p>Nama Akun: <?= $user->name ?></p>
                  </td>
                </tr>
              </thead>
            </table>

            <div class="row table-row">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th class="text-center" style="width:5%">#</th>
                    <th style="width:30%">Barang</th>
                    <th class="text-right" style="width:5%">Satuan</th>
                    <th class="text-right" style="width:10%">Jumlah</th>
                    <th class="text-right" style="width:20%">Harga</th>
                    <th class="text-right" style="width:20%">Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  foreach ($orders as $order) : ?>
                    <tr>
                      <td class="text-center"><?= $no++ ?></td>
                      <td><?= $order->supplierBarang->nama_barang ?></td>
                      <td class="text-right"><?= $order->supplierBarang->satuan->nama ?></td>
                      <td class="text-center"><?= $order->jumlah ?></td>
                      <td class="text-right"><?php $harga = $order->subtotal / $order->jumlah;
                                              echo \app\components\Angka::toReadableHarga($harga) ?></td>
                      <td class="text-right"><?= \app\components\Angka::toReadableHarga($order->subtotal) ?></td>
                    </tr>
                  <?php endforeach ?>
                  <tr>
                    <td colspan="4"></td>
                    <td class="text-right">Total</td>
                    <td class="text-right"><?= \app\components\Angka::toReadableHarga($model->total_harga) ?></td>
                  </tr>
                  <tr>
                    <td colspan="6">
                      Terbilang :
                      <span class=" font-italic" style="font-weight: bold;">
                        <?= \app\components\Angka::toTerbilang($model->total_harga) . " Rupiah" ?>
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>

            </div>

            <div class="row">
              <div class="col-xs-6 margintop">
                <p class="lead marginbottom">Terimakasih Telah Order Kepada Kami.</p>
                <p><?= $setting->tagline2 ?></p>
              </div>
              <div class="col-xs-6" style="padding-top: 25px;">
                <img src="<?= $qrCode->writeDataUri() ?>" alt="" style="width: 125px; height:125px;">
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>