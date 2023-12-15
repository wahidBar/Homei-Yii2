<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use app\components\annex\Modal;
use dmstr\helpers\Html;
use app\components\Tanggal;
use richardfan\widget\JSRegister;

Modal::begin([
    'id' => 'modalku',
    'header' => '<h3>Detail Penawaran</h3>',
    'size' => 'modal-lg'
]);
echo "<div id='modalContent'></div>";
Modal::end();

/**
 * @var yii\web\View $this
 * @var app\models\IsianLanjutan $model
 */
$label = $model->konsepDesign->nama_konsep . " ( {$model->user->name} / " . Tanggal::toReadableDate($model->created_at, false) . " )";
$this->title = 'Isian Lanjutan : ' . $label;
$this->params['breadcrumbs'][] = ['label' => 'Isian Lanjutan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$label, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';
?>

<?php \app\components\annex\Modal::begin(['id' => 'modal', 'header' => '<h3 id="modaltitle"></h3>', 'size' => 'modal-lg', 'options' => ['tabindex' => '' ,'role'=>'document']]) ?>
<div id="modalbody"></div>
<?php \app\components\annex\Modal::end() ?>

<div class="giiant-crud isian-lanjutan-view">

    <div class="clearfix"></div>

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <?= \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>
    <?php
    echo Html::a('Edit Data', ['update', 'id' => $model->id], ['class' => 'btn btn-info mr-2']);
    if ($model->is_beli_material == 0) :
        if ($model->status == $model::STATUS_USER_ISI) :
            echo Html::a('Edit Rencana Survey', ['/isian-lanjutan/rencana-survey/', 'id' => $model->id], ['class' => 'btn btn-primary mr-2']);
        endif;
        if ($model->status >= $model::STATUS_ADMIN_SURVEY && $model->status <= $model::STATUS_ADMIN_ISI_PENAWARAN) :
            echo Html::button(
                'Tambahkan Penawaran',
                [
                    'class' => 'btn btn-primary mr-2',
                    "title" => "Tambah Data",
                    'onclick' => new \yii\web\JsExpression("openmodal( '" . \yii\helpers\Url::to(['/penawaran/create', "Penawaran" => ['id_isian_lanjutan' => $model->id]]) . "',  'Tambah PO')")
                ]
            );
        endif;
        if ($model->status == $model::STATUS_SETUJU_TOR && $model->status_pembayaran != 2) :
            echo Html::a('Edit Nilai DP', ['/isian-lanjutan/edit-nilai-dp/', 'id' => $model->id], ['class' => 'btn btn-primary mr-2']);
        endif;
        if ($model->status_pembayaran == 2) :
            if ($model->status == $model::STATUS_SETUJU_TOR  && $model->dp_pembayaran != null) :
                echo Html::a('Edit Rencana Pembangunan', ['/isian-lanjutan/rencana-pembangunan/', 'id' => $model->id], ['class' => 'btn btn-primary mr-2']);
            endif;
        endif;
        if ($model->status >= $model::STATUS_DEAL_USER && $model->status <= $model::STATUS_UPLOAD_TOR || $model->status == $model::STATUS_TOR_BUTUH_REVISI) :
            echo Html::a('Edit Dokumen TOR', ['/isian-lanjutan/term-of-reference/', 'id' => $model->id], ['class' => 'btn btn-primary mr-2']);
        endif;
        if ($model->status == $model::STATUS_RENCANA_PEMBANGUNAN) {
            echo Html::a('Setujui Rencana Pembangunan', ['/isian-lanjutan/deal-pembangunan/', 'id' => $model->id], ['class' => 'btn btn-success']);
        }
        echo " ";
        if ($model->status == $model::STATUS_RENCANA_PEMBANGUNAN && $model->status != $model::STATUS_DEAL_RENCANA_PEMBANGUNAN) {
            echo Html::a('Tolak Rencana Pembangunan', ['/isian-lanjutan/tolak-pembangunan/', 'id' => $model->id], ['class' => 'btn btn-danger']);
        }

        if ($model->status_pembayaran == 1) {
            echo Html::a('Konfirmasi DP', ['konfirmasi-dp', 'id' => $model->id], ['class' => 'btn btn-success mr-2', 'data-confirm' => '' . 'Apakah Anda Yakin?' . '',]);
            echo Html::a('Tolak DP', ['tolak-dp', 'id' => $model->id], ['class' => 'btn btn-danger', 'data-confirm' => '' . 'Apakah Anda Yakin?' . '',]);
        }
    endif;
    ?>

    <?php
    if ($model->is_beli_material == 0) :
    ?>
        <?= $this->render('_view_info', compact('model')) ?>

        <div class="row">
            <div class="col-md-12">
                <h3>Daftar Penawaran</h3>
                <div class="card m-b-30">
                    <div class="card-body">
                        <?= $this->render('_view_penawaran', compact('model')) ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <?= $this->render('_view_info_proyek', compact('model')) ?>
    <?php endif ?>
</div>

<?php JSRegister::begin() ?>
<script>
    $(function() {

        $('.modalButton').click(function() {

            $('#modalku').modal('show')
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