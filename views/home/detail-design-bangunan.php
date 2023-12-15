<?php
$setting = \app\models\SiteSetting::find()->all();
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
            </div>
        </div>
    </div>
</section>
<!-- End Navigation -->
<!-- Cart Wrap -->
<section class="cart-wrap">
    <div class=" section-content section-content--w1140">
        <div class="container">
            <!-- display success message -->
            <?php

            use dmstr\helpers\Html;

            if (Yii::$app->session->hasFlash('success')) : ?>
                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <p><i class="icon fa fa-check"></i>Saved!</p>
                    <?= Yii::$app->session->getFlash('success') ?>
                </div>
            <?php endif; ?>

            <!-- display error message -->
            <?php if (Yii::$app->session->hasFlash('error')) : ?>
                <div class="alert alert-danger alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <h4><i class="icon fa fa-check"></i>Saved!</h4>
                    <?= Yii::$app->session->getFlash('error') ?>
                </div>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-hover table-cart table-order m-b-30">
                    <tbody>
                        <tr>
                            <td class="name">
                                Label
                            </td>
                            <td>
                                <?= $model->label ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="name">
                                Konsep Design
                            </td>
                            <td>
                                <?= $model->konsepDesign->nama_konsep ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="name">
                                Provinsi
                            </td>
                            <td>
                                <?= $model->provinsi->nama ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="name">
                                Kota
                            </td>
                            <td>
                                <?= $model->kota->nama ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="name">
                                Panjang
                            </td>
                            <td>
                                <?= $model->panjang ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="name">
                                Lebar
                            </td>
                            <td>
                                <?= $model->lebar ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="name">
                                Satuan
                            </td>
                            <td>
                                <?= $model->satuan->nama ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="name">
                                Budget
                            </td>
                            <td>
                                <?= "Rp. " . $model->budget ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="name">
                                Keterangan
                            </td>
                            <td>
                                <?= $model->keterangan ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!-- End Cart Wrap -->
<?php
$this->registerJsFile("@web/homepage/vendor/jquery-3.2.1.min.js", ['position' => \yii\web\View::POS_END]);
?>