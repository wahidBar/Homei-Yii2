<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use dmstr\helpers\Html;
use app\components\annex\Tabs;
use richardfan\widget\JSRegister;

/**
 * @var yii\web\View $this
 * @var app\models\Proyek $model
 */

$this->title = 'Manajemen Keuangan';
$this->params['breadcrumbs'][] = ['label' => 'Keuangan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->nama_proyek, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';
$setting = \app\models\SiteSetting::find()->all();
$this->registerCssFile("@web/homepage/css/sweetalert2.min.css");
$this->registerCss("

.overlay {
    position:absolute;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
}
");
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
                            <a href="<?= \Yii::$app->request->BaseUrl ?>/home/proyek-saya/index">Proyek Saya</a>
                        </li>
                        <span>/</span>
                        <li class="active">
                            <a href="#">Pembayaran</a>
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
        <div class="row">
            <div class="col-md-12">
                <h2 class="title title-3 title--dark">
                    Pembayaran
                </h2>
            </div>
            <div class="col-lg-3 col-md-3 pb-3">
                <?= $this->render('../_sidemenu', compact('model')) ?>
            </div>
            <div class="col-lg-9 col-md-9">
                <div class="col-lg-12 col-12 layout-spacing">
                    <?php $this->beginBlock('Dp') ?>
                    <div class="row">
                        <div class="col-12">
                            <h4>Pembayaran DP</h4>
                            <div class="card m-b-30">
                                <div class="card-body">
                                    <br>
                                    <?= $this->render('_dp', compact('model')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $this->endBlock() ?>

                    <?php $this->beginBlock('Termin') ?>
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Termin</h4>
                            <div class="card m-b-30">
                                <div class="card-body">
                                <?= $this->render('_termin', compact('model')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $this->endBlock() ?>


                    <?= Tabs::widget(
                        [
                            'id' => 'relation-tabs',
                            'encodeLabels' => false,
                            'linkOptions' => [
                                'class' => 'nav-link',
                                'style' => '
                            border: groove;
                            border-color: #239ade;
                            border-top: 10px;
                            border-left: 0px;
                            border-right: 0px;
                        ',
                            ],
                            'items' => [
                                [
                                    'label'   => '<b class="">Pembayaran DP</b>',
                                    'content' => $this->blocks['Dp'],
                                    'active'  => false,
                                ],
                                [
                                    'label'   => '<b class="">Termin</b>',
                                    'content' => $this->blocks['Termin'],
                                    'active'  => true,
                                ],
                            ]
                        ]
                    );
                    ?>

                </div>
            </div>
        </div>
    </div>
</section>
