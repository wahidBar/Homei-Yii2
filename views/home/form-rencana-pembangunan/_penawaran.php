<table class="table table-responsive table-striped table-bordered">
    <thead>
        <tr>
            <th>Kode Penawaran</th>
            <td><?= $penawaran->kode_penawaran ?></td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>Estimasi Waktu</th>
            <td><?= $penawaran->estimasi_waktu ?> Hari</td>
        </tr>
        <tr>
            <th>Total Harga Penawaran</th>
            <td><?= \app\components\Angka::toReadableHarga($penawaran->total_harga_penawaran) ?></td>
        </tr>
        <tr>
            <th>Dibuat Pada</th>
            <td><?= \app\components\Tanggal::toReadableDate($penawaran->tgl_transaksi) ?></td>
        </tr>
        <tr>
            <td colspan="2" class="text-center">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#detailpenawaran">
                    Detail Penawaran
                </button>
            </td>
        </tr>
    </tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="detailpenawaran" tabindex="-1" role="dialog" aria-labelledby="detailpenawaran" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Detail Penawaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Material</th>
                            <th class="text-center">Kisaran Harga</th>
                            <th class="text-center">Jumlah</th>
                            <!-- <th class="text-center">Volume</th> -->
                            <th class="text-center">Sub Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dpenawarans as $model) {
                            $material = \app\models\SupplierBarang::find()->where(['t_supplier_barang.id' => $model->id_material])
                                ->joinWith(['satuan'])
                                ->select('t_master_satuan.nama')->column();
                        ?>
                            <tr>
                                <td><?= $model->supplierBarang->nama_barang ?></td>
                                <td><?= \app\components\Angka::toReadableHarga($model->supplierBarang->harga_proyek) . "/" . $material[0] ?></td>
                                <td><?= $model->jumlah ?></td>
                                <!-- <td><?= $model->volume ?></td> -->
                                <td class="name text-right"><?= \app\components\Angka::toReadableHarga($model->sub_harga) ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td class="name" colspan="3">
                                Biaya Tukang
                            </td>
                            <td class="name text-right">
                                <?= \app\components\Angka::toReadableHarga($penawaran->total_harga_penawaran - $penawaran->harga_penawaran) ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="name" colspan="3">
                                Total Harga
                            </td>
                            <td class="name text-right">
                                <?= \app\components\Angka::toReadableHarga($penawaran->total_harga_penawaran) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>