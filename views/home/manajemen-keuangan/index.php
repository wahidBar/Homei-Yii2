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
<!-- Contact content -->
<section class="contact-content">
    <div class="container">
        <div id="tableHover" class="col-lg-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h2 class="text-center">Manajemen Keuangan</h2>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area mt-4">
                    <div class="row mb-5">                        
                        <div class="col-md-4">
                            <div style="border: 1px solid #aaa;border-radius: 1.2rem;padding: .5rem; margin-top: 2rem">
                                <div style="display: block;text-align: center">
                                    <h2>Total Pemasukan</h2>
                                    <h5>Lorem ipsum dolor sit amet.</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div style="border: 1px solid #aaa;border-radius: 1.2rem;padding: .5rem; margin-top: 2rem">
                                <div style="display: block;text-align: center">
                                    <h2>Total Pengeluaran</h2>
                                    <h5>Lorem, ipsum dolor.</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div style="border: 1px solid #aaa;border-radius: 1.2rem;padding: .5rem; margin-top: 2rem">
                                <div style="display: block;text-align: center">
                                    <h2>Total Item Pengeluaran</h2>
                                    <h5>Lorem, ipsum dolor.</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Pemasukan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Pengeluaran</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Purchase Order</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <?= $this->render('_pemasukan', compact('kmasuks')) ?>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

                        </div>
                        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">

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