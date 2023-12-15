<table class="table table-responsive table-striped table-bordered">
    <tbody>
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
            <td>
                <?php
                if ($model->layanan_yang_diberikan) {
                    echo $model->layanan_yang_diberikan;
                } else {
                    echo "Belum diisi tukang";
                }
                ?>
            </td>
        </tr>
        <?php if ($model->status == \app\models\PekerjaanSameday::STATUS_PEMBAYARAN_DP && $model->catatan_revisi != null) { ?>
            <tr>
                <th>Catatan Revisi Pelayanan</th>
                <td><?= $model->catatan_revisi ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>