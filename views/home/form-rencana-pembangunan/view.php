<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use app\components\annex\ActiveForm;
use app\components\annex\Modal;
use dmstr\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use app\components\annex\Tabs;
use richardfan\widget\JSRegister;

/**
 * @var yii\web\View $this
 * @var app\models\Proyek $model
 */
$this->title = 'Proyek : ' . $model->label;
$this->registerCssFile("@web/homepage/css/sweetalert2.min.css");
$this->registerCss("

.overlay {
    position:absolute;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
}
");
$setting = \app\models\SiteSetting::find()->all();
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
                            <a href="<?= \Yii::$app->request->BaseUrl ?>/home/form-rencana-pembangunan/index">Form Rencana Pembangunan</a>
                        </li>
                        <span>/</span>
                        <li class="active">
                            <a href="#">Detail</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Navigation -->
<!-- Contact content -->
<section class="contact-content">
    <div class="container">
        <div class="col-md-12">
            <h2 class="title title-3 title--dark">
                Detail Rencana Pembangunan
            </h2>
        </div>
        <div class="col-lg-12 col-12 layout-spacing">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <h4>Konsep Desain & Alamat Proyek</h4>
                    <div class="card m-b-30">
                        <div class="card-body">
                            <table class="table table-responsive table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Konsep Desain</th>
                                        <td><?= $model->konsepDesign->nama_konsep ?></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Lantai</th>
                                        <td><?= $model->lantai->nama ?></td>
                                    </tr>
                                    <tr>
                                        <th>Provinsi</th>
                                        <td><?= $model->wilayahProvinsi->nama ?></td>
                                    </tr>
                                    <tr>
                                        <th>Kota</th>
                                        <td><?= $model->wilayahKota->nama ?></td>
                                    </tr>
                                    <tr>
                                        <th>Kecamatan</th>
                                        <td><?= $model->wilayahKecamatan->nama ?></td>
                                    </tr>
                                    <tr>
                                        <th>Desa</th>
                                        <td><?= $model->wilayahDesa->nama ?></td>
                                    </tr>
                                    <tr>
                                        <th>Detail Alamat Pelanggan</th>
                                        <td><?= $model->alamat_pelanggan ?></td>
                                    </tr>
                                    <tr>
                                        <th>Detail Alamat Proyek</th>
                                        <td><?= $model->alamat_proyek ?></td>
                                    </tr>
                                    <?php
                                    if ($model->is_beli_material != 1) :
                                    ?>
                                        <tr>
                                            <th>Nilai DP</th>
                                            <td><?= Yii::$app->formatter->asRp($model->dp_pembayaran) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Bukti Pembayaran DP</th>
                                            <td><?= Yii::$app->formatter->asDownload($model->bukti_pembayaran) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Pembayaran DP</th>
                                            <td><?= \app\components\Tanggal::toReadableDate($model->tanggal_pembayaran) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Status Pembayaran DP</th>
                                            <td>
                                                <?php
                                                if ($model->status_pembayaran == 0) {
                                                    echo '<span class="badge badge-pill badge-warning">DP Belum Dibayar atau Diset Admin</span>';
                                                }
                                                if ($model->status_pembayaran == 1) {
                                                    echo '<span class="badge badge-pill badge-info">Dalam Pengecekan</span>';
                                                }
                                                if ($model->status_pembayaran == 2) {
                                                    echo '<span class="badge badge-pill badge-success">DP Telah Dibayar</span>';
                                                }
                                                if ($model->status_pembayaran == 3) {
                                                    echo '<span class="badge badge-pill badge-danger">DP Ditolak, Mohon Upload Ulang!</span>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Keterangan Pembayaran</th>
                                            <td>
                                                <?= $model->keterangan_pembayaran ?>
                                            </td>
                                        </tr>
                                        <?php if ($model->alasan_tolak != null) : ?>
                                            <tr>
                                                <th>Alasan Tolak</th>
                                                <td><?= $model->alasan_tolak ?></td>
                                            </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <?= $model->getStatus() ?>
                                            </td>
                                        </tr>
                                        <?php if ($model->alasan_tolak_tor != null) : ?>
                                            <tr>
                                                <th>Alasan Tolak TOR</th>
                                                <td><?= $model->alasan_tolak_tor ?></td>
                                            </tr>
                                    <?php
                                        endif;
                                    endif;
                                    ?>
                                </tbody>
                            </table>
                            <hr />
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 mb-3">
                    <h4>Data Diri & Rencana Pembangunan</h4>
                    <div class="card m-b-30">
                        <div class="card-body">
                            <?= $this->render('_datadiri', compact('model')) ?>
                        </div>
                    </div>
                </div>
                <?php if ($penawaran != null) { ?>
                    <div class="col-md-6 col-sm-12 mb-3">
                        <h4>Pilihan Penawaran</h4>
                        <div class="card m-b-30">
                            <div class="card-body">
                                <?= $this->render('_penawaran', compact('penawaran', 'dpenawarans')) ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-md-6 col-sm-12 col-12 mb-3">
                    <h4>Daftar Ruangan</h4>
                    <div class="card m-b-30">
                        <div class="card-body">
                            <?= $this->render('_ruangan', compact('model')) ?>
                        </div>
                    </div>
                </div>
                <?php
                if ($model->is_beli_material != 1) {
                    if ($model->dokumen_tor != null) { ?>
                        <div class="col-md-12 col-sm-12 col-12 mb-3 text-center">
                            <h4>Dokumen Term Of Reference</h4>
                            <div class="card m-b-30">
                                <div class="card-body">
                                    <?= $this->render('_dokumen-tor', compact('model')) ?>
                                </div>
                            </div>
                            <?php if ($model->status == $model::STATUS_UPLOAD_TOR) { ?>
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#tolak">
                                    TOR Butuh Revisi
                                </button>
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#setuju">
                                    Setujui Dokumen TOR
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="tolak" tabindex="-1" role="dialog" aria-labelledby="tolak-dokumen-tor" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Tolak Dokumen TOR</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin?
                                                <?php $form = ActiveForm::begin([
                                                    'id' => 'IsianLanjutan',
                                                    'layout' => 'horizontal',
                                                    'enableClientValidation' => false,
                                                    'errorSummaryCssClass' => 'error-summary alert alert-error'
                                                ]);
                                                ?>
                                                <?php echo $form->errorSummary($model); ?>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card m-b-30">
                                                            <div class="card-body">
                                                                <div class="d-flex  flex-wrap">
                                                                    <?= $form->field($model, 'alasan_tolak', \app\components\Constant::COLUMN(1))->textInput(['type' => 'textarea', 'style' => 'height:100px']) ?>
                                                                    <div class="clearfix"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="card m-b-30">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-12 text-left ml-4">
                                                                        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']); ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <?php ActiveForm::end(); ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="setuju" tabindex="-1" role="dialog" aria-labelledby="setuju" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="setuju">Setujui Dokumen TOR</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menyetujui dokumen Term Of Reference ini?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                                <a href="<?= \Yii::$app->request->baseUrl . "/home/form-rencana-pembangunan/setuju-tor?id=" . $model->kode_unik ?>" class="btn btn-info text-white">Setujui Dokumen</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php
                    if ($model->status == $model::STATUS_SETUJU_TOR || $model->status == $model::STATUS_REVISI_RENCANA_PEMBANGUNAN) {
                        if ($model->dp_pembayaran != null && $model->status_pembayaran == 0 || $model->status_pembayaran == 3) {
                    ?>
                            <div class="col-md-12 text-center">
                                <?= Html::a('Bayar DP', ['upload-pembayaran-dp', 'id' => $model->kode_unik], ['class' => 'btn btn-primary text-white mb-2']) ?>
                            </div>
                        <?php } elseif ($model->status_pembayaran == 2) { ?>
                            <div class="col-md-12 text-center">
                                <?= Html::a('Update Tanggal Rencana Pembangunan', ['tanggal-rencana-pembangunan', 'id' => $model->kode_unik], ['class' => 'btn btn-primary text-white mb-2']) ?>
                            </div>
                    <?php }
                    } ?>
                    <?php if ($model->status < $model::STATUS_RENCANA_PEMBANGUNAN && $model->status_pembayaran != 2) { ?>
                        <div class="col-md-12 col-sm-12 col-12 mb-3 text-center">
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#batal">
                                Batalkan Rencana Pembangunan
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="batal" tabindex="-1" role="dialog" aria-labelledby="batal" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Batalkan Rencana Pembangunan</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah Anda yakin ingin membatalkan rencana pembangunan ini?
                                            <?php $formBatal = ActiveForm::begin([
                                                'id' => 'BatalIsianLanjutan',
                                                'layout' => 'horizontal',
                                                'enableClientValidation' => false,
                                                'errorSummaryCssClass' => 'error-summary alert alert-error'
                                            ]);
                                            ?>
                                            <?php echo $formBatal->errorSummary($modelBatal); ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card m-b-30">
                                                        <div class="card-body">
                                                            <div class="d-flex  flex-wrap">
                                                                <?= $formBatal->field($modelBatal, 'alasan_tolak', \app\components\Constant::COLUMN(1))->textInput(['type' => 'textarea', 'style' => 'height:100px']) ?>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="card m-b-30">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-12 text-center">
                                                                    <?= Html::submitButton('<i class="fa fa-save"></i> Batalkan', ['class' => 'btn btn-danger']); ?>
                                                                    <?= Html::a('<i class="fa fa-chevron-left"></i> Kembali', ['index'], ['class' => 'btn btn-default']) ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                            <?php ActiveForm::end(); ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="col-md-6 col-sm-12 col-12 mb-3">
                        <h4>Data Proyek</h4>
                        <div class="card m-b-30">
                            <div class="card-body">
                                <?= $this->render('_proyek', compact('model')) ?>
                            </div>
                        </div>
                    </div>
                    <?php if ($model->boq_proyek == null && $model->nomor_spk == null && $model->informasi_proyek == null) { ?>
                        <div class="col-md-12 text-center">
                            <?= Html::a('Isi Data Proyek', ['tambah-data-pembangunan'], ['class' => 'btn btn-primary text-white mb-2']) ?>
                        </div>
                <?php }
                } ?>
            </div>
        </div>
    </div>
</section>
<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
?>