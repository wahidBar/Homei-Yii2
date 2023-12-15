<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Kategori</th>
            <th>Item</th>
            <th>Tanggal</th>
            <th>Jumlah</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($kmasuks as $model) { ?>
            <tr>
                <td><?= $model->kategori->nama_kategori ?></td>
                <td><?= $model->item ?></td>
                <td><?= $model->tanggal ?></td>
                <td><?= \app\components\Angka::toReadableHarga($modal->jumlah) ?></td>
                <td><?= $model->keterangan ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>