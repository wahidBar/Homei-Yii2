<?php

use dmstr\helpers\Html;
use richardfan\widget\JSRegister;
use yii\grid\GridView;
use yii\helpers\Url;

?>
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

$this->registerCssFile("@web/homepage/css/construction.css");
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
                            <a href="<?= Url::to(["/home/index"]) ?>">Home</a>
                        </li>
                        <span>/</span>
                        <li>
                            <a href="<?= Url::to(["/home/bahan-material"]) ?>">Bahan Material</a>
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
    <div class="container-fluid">
        <div id="tableHover" class="col-lg-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="title title-3 title--dark">
                                Pilih Bahan Material
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area mt-4">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-12">
                            <?= $this->render('index/_sidebar', compact("materials")) ?>
                        </div>
                        <div class="col-lg-9 col-md-8 col-sm-12">
                            <?= $this->render('index/_content', compact("response", "summary", "carts", "jumlah_carts", "subtotal_cart")) ?>
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
<?php JSRegister::begin(); ?>
<script>
    $(function() {
        var Accordion = function(el, multiple) {
            this.el = el || {};
            this.multiple = multiple || false;
            var links = this.el.find('.link');
            links.on('click', {
                el: this.el,
                multiple: this.multiple
            }, this.dropdown)
        }
        Accordion.prototype.dropdown = function(e) {
            var $el = e.data.el;
            $this = $(this), $next = $this.next();
            $next.slideToggle();
            $this.parent().toggleClass('open');
            if (!e.data.multiple) {
                $el.find('.submenu').not($next).slideUp().parent().removeClass('open');
            };
        }
        var accordion = new Accordion($('#accordion'), false);
    });
</script>
<?php JSRegister::end(); ?>