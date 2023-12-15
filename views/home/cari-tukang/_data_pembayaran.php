<?php
date_default_timezone_set("Asia/Jakarta");

use yii\helpers\Html;
?>
<table class="table table-responsive table-striped table-bordered">
    <tbody>
        <tr>
            <th>Nilai DP</th>
            <td><?= \Yii::$app->formatter->asRp($model->nominal_dp) ?></td>
        </tr>
        <tr>
            <th>Biaya Akhir</th>
            <td><?= \Yii::$app->formatter->asRp($model->biaya - $model->nominal_dp) ?></td>
        </tr>
        <tr>
            <th>Total Biaya</th>
            <td><?= \Yii::$app->formatter->asRp($model->biaya) ?></td>
        </tr>
        <tr>
            <th>Bukti Pembayaran DP</th>
            <td>
                <?php
                // if ($model->status != $model::STATUS_PEMBAYARAN_DP_EXPIRED || $model->status == $model::STATUS_SELESAI) {
                //     if ($model->bukti_dp == null && $model->nominal_dp != null) {
                //         echo Html::a('Bayar DP', ['bayar-dp', 'id' => $model->kode_unik], ['class' => 'btn btn-info text-white']);
                //     } elseif ($model->revisi_pembayaran_dp == null) {
                        echo \Yii::$app->formatter->asDownload($model->bukti_dp);
                //     } else {
                //         echo Html::a('Bayar DP', ['bayar-dp', 'id' => $model->kode_unik], ['class' => 'btn btn-info text-white']);
                //     }
                // } else {
                //     echo "Pembayaran Kadaluarsa. Mohon ulangi order kembali";
                // }
                ?>
            </td>
        </tr>
        <tr>
            <th>Tanggal Pembayaran DP</th>
            <td><?= \app\components\Tanggal::toReadableDate($model->tanggal_pembayaran_dp) ?></td>
        </tr>
        <tr>
            <th>Batas Pembayaran DP</th>
            <td><?= \app\components\Tanggal::toReadableDate($model->deadline_pembayaran_dp) ?></td>
        </tr>
        <tr>
            <th>Revisi Pembayaran DP</th>
            <td><?= $model->revisi_pembayaran_dp ?></td>
        </tr>
        <tr>
            <th>Bukti Pembayaran Total</th>
            <td>
                <?php
                if ($model->status == \app\models\PekerjaanSameday::STATUS_PEMBAYARAN && $model->revisi_pembayaran == null) {
                    echo \Yii::$app->formatter->asDownload($model->bukti_pembayaran);
                }
                ?>
            </td>
        </tr>
        <tr>
            <th>Tanggal Pembayaran</th>
            <td><?= \app\components\Tanggal::toReadableDate($model->tanggal_pembayaran) ?></td>
        </tr>
        <tr>
            <th>Revisi Pembayaran</th>
            <td><?= $model->revisi_pembayaran ?></td>
        </tr>
        <tr>
            <th>Invoice</th>
            <td>
                <?php
                if ($model->nominal_dp != null) {
                    echo Html::a('Invoice', ['cetak-invoice', 'id' => $model->kode_unik], ['class' => 'btn btn-info text-white']);
                } else {
                    echo "-";
                }
                ?>
            </td>
        </tr>
    </tbody>
</table>