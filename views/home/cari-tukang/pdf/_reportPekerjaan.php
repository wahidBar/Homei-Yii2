<?php
if ($model->bukti_pembayaran == null) :
  $status = "belum-bayar.png";
// elseif ($model->status == $model::STATUS_MENUNGGU_KONFIRMASI_ADMIN) :
//   $status = "pengecekan.png";
elseif ($model->bukti_pembayaran != null) :
  $status = "selesai.png";
// elseif ($model->status == $model::STATUS_PEMBAYARAN_DIBATALKAN) :
//   $status = "batal.png";
// elseif ($model->status == $model::STATUS_PEMBAYARAN_EXPIRED) :
//   $status = "expired.png";
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
  <div class="container bootstrap snippets">
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
                <tbody>
                  <tr>
                    <td colspan="2">
                      <h3>Data Pelanggan</h3>
                    </td>
                  </tr>
                  <tr>
                    <th>Pengguna</th>
                    <td><?= $model->pelanggan->name ?></td>
                  </tr>
                  <tr>
                    <th>Nama Pelanggan</th>
                    <td><?= $model->nama_pelanggan ?></td>
                  </tr>
                  <tr>
                    <th>Alamat</th>
                    <td><?= $model->alamat_pelanggan ?></td>
                  </tr>
                  <tr>
                    <th>Tanggal Survey</th>
                    <td><?= Yii::$app->formatter->asDatetime($model->tanggal_survey) ?></td>
                  </tr>
                  <tr>
                    <th>Status</th>
                    <td><?= $model->getStatus() ?></td>
                  </tr>
                  <tr>
                    <th>Revisi</th>
                    <td><?= $model->catatan_revisi ?></td>
                  </tr>
                  <tr>
                    <th>Layanan Dibuat</th>
                    <td><?= Yii::$app->formatter->asDatetime($model->created_at) ?></td>
                  </tr>

                  <tr>
                    <td colspan="2">
                      <h3>Data Pembayaran</h3>
                    </td>
                  </tr>
                  <tr>
                    <th>Nilai DP</th>
                    <td><?= \Yii::$app->formatter->asRp($model->nominal_dp) ?></td>
                  </tr>
                  <tr>
                    <th>Tanggal Pembayaran DP</th>
                    <td><?= \Yii::$app->formatter->asDatetime($model->tanggal_pembayaran_dp) ?></td>
                  </tr>
                  <tr>
                    <th>Revisi Pembayaran DP</th>
                    <td><?= $model->revisi_pembayaran_dp ?></td>
                  </tr>
                  <tr>
                    <th>Tanggal Pembayaran</th>
                    <td><?= \Yii::$app->formatter->asDatetime($model->tanggal_pembayaran) ?></td>
                  </tr>
                  <tr>
                    <th>Total Biaya</th>
                    <td><?= \Yii::$app->formatter->asRp($model->biaya) ?></td>
                  </tr>

                  <tr>
                    <td colspan="2">
                      <h3>Data Pekerjaan</h3>
                    </td>
                  </tr>
                  <tr>
                    <th>Kategori Layanan</th>
                    <td><?= $model->getViewKategori() ?></td>
                  </tr>
                  <tr>
                    <th>Foto Lokasi</th>
                    <td><img src="<?= Yii::$app->formatter->asMyImage($model->foto_lokasi, false) ?>" width="300" alt="" srcset="" class="img img-fluid"></td>
                  </tr>
                  <tr>
                    <th>Uraian Pekerjaan</th>
                    <td><?= $model->uraian_pekerjaan ?></td>
                  </tr>
                  <tr>
                    <th>Layanan yang Diberikan</th>
                    <td><?= $model->layanan_yang_diberikan ?></td>
                  </tr>

                  <tr>
                    <td colspan="2">
                      <h3>Hasil Pekerjaan</h3>
                    </td>
                  </tr>
                  <tr>
                    <th>Foto Pengerjaan</th>
                    <td><img src="<?= Yii::$app->formatter->asMyImage($model->foto_pengerjaan, false) ?>" width="300" alt="" srcset="" class="img img-fluid"></td>
                  </tr>
                  <tr>
                    <th>Keterangan Pengerjaan</th>
                    <td><?= $model->keterangan_pengerjaan ?></td>
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