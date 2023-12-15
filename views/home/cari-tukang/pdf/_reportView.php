<?php
if ($model->bukti_pembayaran == null && $model->status < \app\models\PekerjaanSameday::STATUS_SELESAI) :
  $status = "belum-bayar.png";
elseif ($model->bukti_pembayaran != null && $model->status == \app\models\PekerjaanSameday::STATUS_PEMBAYARAN) :
  $status = "pengecekan.png";
elseif ($model->bukti_pembayaran != null && $model->status  == \app\models\PekerjaanSameday::STATUS_SELESAI) :
  $status = "lunas.png";
// elseif ($model->status == \app\models\PekerjaanSameday::STATUS_PEMBAYARAN_DIBATALKAN) :
//   $status = "batal.png";
elseif ($model->status == \app\models\PekerjaanSameday::STATUS_PEMBAYARAN_DP_EXPIRED) :
  $status = "expired.png";
endif;


use Da\QrCode\QrCode;
use yii\helpers\Url;

$qrCode = (new QrCode(Url::to(["/home/cari-tukang/check-valid", "id" => $model->kode_unik], true)))
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
                  <!-- <td class="text-right"></td> -->
                </tr>
                <tr>
                  <td class="text-right"><?= \app\components\Tanggal::toReadableDate($model->tanggal_pembayaran_dp) ?></td>
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
                    <p class="lead marginbottom">Kepada : <?= $model->nama_pelanggan ?></p>
                    <p><?= $model->alamat_pelanggan ?></p>
                    <p>No. Telp: <?= $model->pelanggan->no_hp ?></p>
                    <p>Email: <?= $model->pelanggan->email ?></p>
                  </td>
                  <!-- <td class="text-right payment-details">
                    <p class="lead marginbottom payment-info">Detail Pembayaran</p>
                    <p>Tanggal: </p>
                    <p>Batas Pembayaran: </p>
                    <p>Total:</p>
                    <p>Nama Akun: </p>
                  </td> -->
                </tr>
              </thead>
            </table>

            <div class="row table-row">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th class="text-center" style="width:5%">#</th>
                    <th style="width:30%">Kategori Layanan</th>
                    <th class="text-right" style="width:15%">Jenis Pembayaran</th>
                    <th class="text-right" style="width:10%">Jumlah</th>
                    <th class="text-right" style="width:15%">Tanggal Pembayaran</th>
                    <th class="text-right" style="width:10%">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="text-center">1</td>
                    <td><?= $model->kategori->nama_kategori_layanan ?></td>
                    <td class="text-right">DP</td>
                    <td class="text-right"><?= app\components\Angka::toReadableHarga($model->nominal_dp) ?></td>
                    <td class="text-right"><?= app\components\Tanggal::toReadableDate($model->tanggal_pembayaran_dp) ?></td>
                    <td class="text-right">
                      <?php
                      if ($model->bukti_dp == null && $model->nominal_dp != null) {
                        echo "Belum Dibayar";
                      } elseif ($model->revisi_pembayaran_dp == null) {
                        echo "Dibayar";
                      } elseif ($model->revisi_pembayaran_dp != null) {
                        echo "Revisi Pembayaran";
                      } elseif ($model->status == \app\models\PekerjaanSameday::STATUS_PEMBAYARAN_DP && $model->revisi_pembayaran_dp == null) {
                        echo "Menunggu Konfirmasi";
                      }
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-center">2</td>
                    <td><?= $model->kategori->nama_kategori_layanan ?></td>
                    <td class="text-right">Pembayaran Total</td>
                    <td class="text-right"><?= app\components\Angka::toReadableHarga($model->biaya - $model->nominal_dp) ?></td>
                    <td class="text-right"><?= app\components\Tanggal::toReadableDate($model->tanggal_pembayaran) ?></td>
                    <td class="text-right">
                      <?php
                      // if ($model->status == \app\models\PekerjaanSameday::STATUS_PEMBAYARAN && $model->revisi_pembayaran == null) :
                      //   echo "Pengecekan";
                      if ($model->status == \app\models\PekerjaanSameday::STATUS_PEMBAYARAN && $model->bukti_pembayaran == null) :
                        echo "Belum Dibayar";
                      elseif ($model->status == \app\models\PekerjaanSameday::STATUS_PEMBAYARAN && $model->bukti_pembayaran != null) :
                        if ($model->revisi_pembayaran == null) :
                          echo "Pengecekan";
                        else :
                          echo "Revisi Pembayaran";
                        endif;
                      endif;
                      if ($model->status == \app\models\PekerjaanSameday::STATUS_SELESAI) :
                        echo "Dibayar";
                      endif;
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2"></td>
                    <td class="text-right">Total</td>
                    <td class="text-right"><?= \app\components\Angka::toReadableHarga($model->biaya) ?></td>
                  </tr>
                  <tr>
                    <td colspan="4">
                      Terbilang :
                      <span class=" font-italic" style="font-weight: bold;">
                        <?= \app\components\Angka::toTerbilang($model->biaya) . " Rupiah" ?>
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