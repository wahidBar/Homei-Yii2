<table class="table table-responsive table-striped table-bordered">
    <thead>
        <tr>
            <th>Nama Awal</th>
            <td><?= $model->nama_awal ?></td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>Nama Akhir</th>
            <td><?= $model->nama_akhir ?></td>
        </tr>
        <tr>
            <th>No HP</th>
            <td><?= $model->no_hp ?></td>
        </tr>
        <tr>
            <th>Panjang Tanah</th>
            <td><?= $model->panjang ?> m</td>
        </tr>
        <tr>
            <th>Lebar Tanah</th>
            <td><?= $model->lebar ?> m</td>
        </tr>
        <tr>
            <th>Luas Tanah</th>
            <td><?= $model->luas_tanah ?> m<sup>2</sup></td>
        </tr>
        <tr>
            <th>Budget</th>
            <td><?= \app\components\Angka::toReadableHarga($model->budget) ?></td>
        </tr>
        <?php
        if ($model->is_beli_material != 1) :
        ?>
            <tr>
                <th>Rencana Survey</th>
                <td>
                    <?php if ($model->rencana_survey) {
                        echo  \app\components\Tanggal::toReadableDate($model->rencana_survey);
                    } else {
                        echo "Belum Diisi Admin";
                    } ?>
                </td>
            </tr>
            <tr>
                <th>Rencana Pembangunan</th>
                <td><?= \app\components\Tanggal::toReadableDate($model->rencana_pembangunan) ??  "-" ?></td>
            </tr>
        <?php endif ?>
        <tr>
            <th>Keterangan</th>
            <td><?= $model->keterangan ?></td>
        </tr>
    </tbody>
</table>