<?php

use dmstr\helpers\Html;
?>
<table class="table table-responsive table-striped table-bordered">
    <tbody>
        <tr>
            <th>Foto Pengerjaan</th>
            <td><img src="<?= Yii::$app->formatter->asMyImage($model->foto_pengerjaan, false) ?>" width="300" alt="" srcset="" class="img img-fluid"></td>
        </tr>
        <tr>
            <th>Keterangan Pengerjaan</th>
            <td><?= $model->keterangan_pengerjaan ?></td>
        </tr>
        <tr>
            <th>Catatan Revisi</th>
            <td><?= $model->catatan_revisi ?></td>
        </tr>
        
        <?php
        if ($model->status == \app\models\PekerjaanSameday::STATUS_SELESAI) : ?>
            <tr>
                <th>Laporan Pekerjaan</th>
                <td><?= Html::a('Laporan Pekerjaan', ['laporan-pekerjaan', 'id' => $model->kode_unik], ['class' => 'btn btn-info text-white']) ?></td>
            </tr>
        <?php endif ?>
    </tbody>
</table>