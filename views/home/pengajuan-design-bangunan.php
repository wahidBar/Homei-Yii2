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
            <div class="table-responsive">
                <table class="table table-cart m-b-30">
                    <thead>
                        <tr>
                            <th>Detail</th>
                            <th>Label</th>
                            <th>Konsep Design</th>
                            <th>Provinsi</th>
                            <th>Kota</th>
                            <th>Budget</th>
                            <th>Penawaran Project</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        use dmstr\helpers\Html;

                        foreach ($models as $design) {
                        ?>
                            <tr>
                                <td class="text-left">
                                    <?= Html::a(
                                        'Detail',
                                        ['detail-pengajuan-design', 'id' => $design->id],
                                        [
                                            'class' => 'btn btn-info text-white',
                                            // 'data-confirm' => '' . 'Are you sure to delete this item?' . '',
                                            // 'data-method' => 'post',
                                        ]
                                    ); ?>
                                </td>
                                <td class="text-left">
                                    <?= $design->label; ?>
                                </td>
                                <td class="text-left">
                                    <?= $design->konsepDesign->nama_konsep; ?>
                                </td>
                                <td class="text-left">
                                    <?= $design->provinsi->nama; ?>
                                </td>
                                <td class="text-left">
                                    <?= $design->kota->nama; ?>
                                </td>
                                <td class="text-left">
                                    <?= $design->budget; ?>
                                </td>
                                <td>
                                    <?php

                                    if ($design->status == 1) :
                                        echo Html::a(
                                            'Lihat Penawaran',
                                            ['daftar-penawaran-project', 'id' => $design->id],
                                            [
                                                'class' => 'btn btn-info text-white m-1',
                                            ]
                                        );
                                    elseif ($design->status == 2) :
                                        echo Html::a(
                                            'Detail Deal Project',
                                            ['detail-deal-project', 'id' => $design->id],
                                            [
                                                'class' => 'btn btn-info text-white m-1',
                                            ]
                                        );
                                    else :
                                        echo  '<span class="badge badge-warning">Belum Ada Penawaran</span>';
                                    endif;
                                    ?>

                                </td>
                            </tr>
                        <?php } ?>

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