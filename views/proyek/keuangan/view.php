<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use \yii\helpers\Html;
use app\components\annex\Tabs;
use richardfan\widget\JSRegister;

/**
 * @var yii\web\View $this
 * @var app\models\Proyek $model
 */
\app\assets\MapAsset::register($this);



$this->title = 'Keuangan';
$this->params['breadcrumbs'][] = ['label' => 'Keuangan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->nama_proyek, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';
?>

<?php \app\components\annex\Modal::begin(['id' => 'modal', 'header' => '<h3 id="modaltitle"></h3>', 'size' => 'modal-lg', 'options' => ['tabindex' => '']]) ?>
<div id="modalbody"></div>
<?php \app\components\annex\Modal::end() ?>
<style>
    #map_canvas {
        width: 100%;
        height: 70vh;
        margin-bottom: 1rem;
        border-radius: 20px;
        box-shadow: 0 8px 4px 5px #eee;
    }

    .iconproyek {
        margin: auto;
        display: block;
        background-color: #239aDe;
        border-radius: 100%;
        width: 120px;
        height: 120px;
        text-align: center
    }
</style>
<div class="giiant-crud proyek-view">

    <?= Html::a("Lihat Proyek", ["/proyek/view", "id" => $model->id], ["class" => "btn btn-default mb-2 mr-2"]) ?>

    <div class="clearfix"></div>

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <?= \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6 col-lg-3">
            <div class="m-b-30">
                <div class="bg-primary text-white" style="border: 1px solid #ddd;border-radius: .6rem;padding: 1.2rem;background-color: white">
                    <span>Total Anggaran</span>
                    <hr>
                    <span><?= Yii::$app->formatter->asRp($total_anggaran) ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-2">
            <div class="m-b-30">
                <div style="border: 1px solid #ddd;border-radius: .6rem;padding: 1.2rem;background-color: white">
                    <span>Sisa Anggaran</span>
                    <hr>
                    <span><?= Yii::$app->formatter->asRp($sisa_anggaran) ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="m-b-30">
                <div class="bg-success text-white" style="border: 1px solid #ddd;border-radius: .6rem;padding: 1.2rem;background-color: white">
                    <span>Pemasukkan</span>
                    <hr>
                    <span><?= Yii::$app->formatter->asRp($total_pemasukkan) ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="m-b-30">
                <div class="bg-warning text-white" style="border: 1px solid #ddd;border-radius: .6rem;padding: 1.2rem;background-color: white">
                    <span>Pengeluaran</span>
                    <hr>
                    <span><?= Yii::$app->formatter->asRp($total_pengeluaran) ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-3">
            <div class="m-b-30">
                <div class="bg-danger text-white" style="border: 1px solid #ddd;border-radius: .6rem;padding: 1.2rem;background-color: white">
                    <span>Total Hutang</span>
                    <hr>
                    <span><?= Yii::$app->formatter->asRp($total_hutang) ?></span>
                </div>
            </div>
        </div>
    </div>

    <?php $this->beginBlock('KeuanganMasuk') ?>
    <div class="row">
        <div class="col-md-4">
            <h4>Kategori</h4>
            <div class="card m-b-30">
                <div class="card-body">
                    <p>
                        <?= Html::button("Tambah", [
                            "class" => "mr-1 mb-1 btn btn-primary",
                            "title" => "Tambah Data",
                            'onclick' => new \yii\web\JsExpression("openmodal( '" . \yii\helpers\Url::to(['master-kategori-keuangan-masuk/create', 'MasterKategoriKeuanganMasuk' => ['id_proyek' => $model->id], 'id_project' => $model->id]) . "',  'Tambah')")
                        ]); ?>
                    </p>
                    <br>
                    <?= $this->render('_kategori_keuangan_masuk', compact('model')) ?>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <h4>Keuangan Masuk</h4>
            <div class="card m-b-30">
                <div class="card-body">
                    <p>
                        <?= Html::button("Tambah", [
                            "class" => "mr-1 mb-1 btn btn-primary",
                            "title" => "Tambah Data",
                            'onclick' => new \yii\web\JsExpression("openmodal( '" . \yii\helpers\Url::to(['proyek-keuangan-masuk/create', 'ProyekKeuanganMasuk' => ['id_proyek' => $model->id], 'id_project' => $model->id]) . "',  'Tambah')")
                        ]); ?>
                    </p>
                    <br>
                    <?= $this->render('_keuangan_masuk', compact('model')) ?>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endBlock() ?>

    <?php $this->beginBlock('KeuanganKeluar') ?>
    <div class="row">
        <div class="col-md-4">
            <h4>Kategori</h4>
            <div class="card m-b-30">
                <div class="card-body">
                    <p>
                        <?= Html::button("Tambah", [
                            "class" => "mr-1 mb-1 btn btn-primary",
                            "title" => "Tambah Data",
                            'onclick' => new \yii\web\JsExpression("openmodal( '" . \yii\helpers\Url::to(['master-kategori-keuangan-keluar/create', 'MasterKategoriKeuanganKeluar' => ['id_proyek' => $model->id], 'id_project' => $model->id]) . "',  'Tambah')")
                        ]); ?>
                    </p>
                    <br>
                    <?= $this->render('_kategori_keuangan_keluar', compact('model')) ?>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <h4>Keuangan Keluar</h4>
            <div class="card m-b-30">
                <div class="card-body">
                    <p>
                        <?= Html::button("Tambah", [
                            "class" => "mr-1 mb-1 btn btn-primary",
                            "title" => "Tambah Data",
                            'onclick' => new \yii\web\JsExpression("openmodal( '" . \yii\helpers\Url::to(['proyek-keuangan-keluar/create', 'ProyekKeuanganKeluar' => ['id_proyek' => $model->id], 'id_project' => $model->id]) . "',  'Tambah Pengeluaran')")
                        ]); ?>
                    </p>
                    <br>
                    <?= $this->render('_keuangan_keluar', compact('model')) ?>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endBlock() ?>

    <?php $this->beginBlock('PurchaseOrder') ?>
    <div class="row">
        <div class="col-md-4">
            <h4>Kategori</h4>
            <div class="card m-b-30">
                <div class="card-body">
                    <p>
                        <?= Html::button("Tambah", [
                            "class" => "mr-1 mb-1 btn btn-primary",
                            "title" => "Tambah Data",
                            'onclick' => new \yii\web\JsExpression("openmodal( '" . \yii\helpers\Url::to(['master-kategori-keuangan-keluar/create', 'MasterKategoriKeuanganKeluar' => ['id_proyek' => $model->id], 'id_project' => $model->id]) . "',  'Tambah')")
                        ]); ?>
                    </p>
                    <br>
                    <?= $this->render('_kategori_keuangan_keluar', compact('model')) ?>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <h4>Keuangan Keluar</h4>
            <div class="card m-b-30">
                <div class="card-body">
                    <p>
                        <?= Html::button("Tambah", [
                            "class" => "mr-1 mb-1 btn btn-primary",
                            "title" => "Tambah Data",
                            'onclick' => new \yii\web\JsExpression("openmodal( '" . \yii\helpers\Url::to(['proyek-keuangan-keluar/create-po', 'ProyekKeuanganKeluar' => ['id_proyek' => $model->id], 'id_project' => $model->id]) . "',  'Tambah PO')")
                        ]); ?>
                    </p>
                    <br>
                    <?= $this->render('_keuangan_po', compact('model')) ?>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endBlock() ?>

    <?= Tabs::widget(
        [
            'id' => 'relation-tabs',
            'encodeLabels' => false,
            'items' => [
                [
                    'label'   => '<b class="">Pemasukkan</b>',
                    'content' => $this->blocks['KeuanganMasuk'],
                    'active'  => true,
                ],
                [
                    'label'   => '<b class="">Pengeluaran</b>',
                    'content' => $this->blocks['KeuanganKeluar'],
                    'active'  => false,
                ],
                [
                    'label'   => '<b class="">Purchase Order</b>',
                    'content' => $this->blocks['PurchaseOrder'],
                    'active'  => false,
                ],
            ]
        ]
    );
    ?>

</div>




<?php

$lat = ($model->latitude_proyek) ? $model->latitude_proyek : 0;
$long = ($model->longitude_proyek) ? $model->longitude_proyek : 0;
?>

<?php JSRegister::begin() ?>
<script>
    $(function() {

        $('.modalButton').click(function() {
            console.log($(this).attr('value'));
            $('#modal').modal('show')
                .find('#modalContent')
                .load($(this).attr('value'));
        });
    })

    window.openmodal = function(href, title = "Modal") {
        $.ajax(href, {
            success: function(response) {
                $('#modaltitle').html(title);
                $('#modalbody').html(response);
                $('#modal').modal({
                    show: 1
                });
            }
        })
    }
</script>

<?php JSRegister::end() ?>