<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

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
$this->registerJsFile("//maps.googleapis.com/maps/api/js?key=AIzaSyAjKSlPBmdJkSO1BY6Qt9gWBlmgVw6KXO4&libraries=places&region=id&language=en");

$this->title = 'Proyek : ' . $model->label;
$this->registerCss("

.overlay {
    position:absolute;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
}
");
?>
<?php
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
            <!-- flash message -->
            <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
                <span class="alert alert-info alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <?= \Yii::$app->session->getFlash('deleteError') ?>
                </span>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6 col-sm-12">
                <h4>Konsep Desain & Alamat Proyek</h4>
                    <div class="card m-b-30">
                        <div class="card-body">
                            <table class="table table-striped table-bordered">
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
                                </tbody>
                            </table>
                            <hr />
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 mb-3">
                <h4>Daftar Ruangan</h4>
                    <div class="card m-b-30">
                        <div class="card-body">
                            <?= $this->render('_ruangan', compact('model')) ?>
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
            </div>
        </div>
    </div>
    </div>
</section>
<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
?>