<?php

use dmstr\helpers\Html;
use richardfan\widget\JSRegister;
use yii\helpers\Url;

$setting = \app\models\SiteSetting::find()->one();
?>
<!-- Header Stick -->
<header class="header-stick header-stick6">
    <div class=" section-content section-content--w1140">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 clearfix">
                    <h1 class="logo pull-left">
                        <a href="<?= Url::to(["/"]) ?>">
                            <img alt="Logo" src="<?= Yii::getAlias("@file/$setting->logo") ?>" width="100px">
                        </a>
                    </h1>
                    <nav class="menu-desktop pull-right">
                        <ul class="ul--inline ul--no-style">
                            <?= $this->render('navigation') ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- End Header Stick -->
<!-- Header Mobile -->
<header class="header-mobile">
    <div class="container-fluid clearfix">
        <h1 class="logo pull-left">
            <a href="<?= Url::to(["/home"]) ?>">
                <img alt="Logo" src="<?= Yii::getAlias("@file/$setting->logo") ?>" width="100px">
            </a>
        </h1>
        <a class="menu-mobile__button">
            <i class="fa fa-bars"></i>
        </a>
        <nav class="menu-mobile hidden">
            <ul class="ul--no-style text-left">
                <?= $this->render('navigation') ?>
            </ul>
        </nav>
    </div>
</header>
<!-- End Header Mobile -->
<!-- Header Desktop -->
<header class="header-desktop header6">
    <div class=" section-content section-content--w1140">
        <div class="container-fluid clearfix">
            <h1 class="logo pull-left">
                <a href="<?= \Yii::$app->request->baseUrl . "/home" ?>">
                    <img alt="Logo" src="<?= \Yii::$app->request->baseUrl . "/uploads/" . $setting->logo ?>" width="100px">
                </a>
            </h1>
            <nav class="menu-desktop menu-desktop--show pull-right">
                <ul class="ul--inline ul--no-style">
                    <?= $this->render('navigation') ?>
                </ul>
            </nav>
        </div>
    </div>
</header>
<!-- End Header Desktop -->

<?php
if (Yii::$app->user->identity->id != null) : ?>
    <?php JSRegister::begin(); ?>
    <script>
        const sleep = (milliseconds) => {
            return new Promise(resolve => setTimeout(resolve, milliseconds))
        }
        async function notifikasi(id) {
            await sleep(5000);
            $.getJSON("<?= Url::to(["/site/notifikasi/"]) ?>", function(responseJSON) {
                let isi;
                $(".notificationcount").html(responseJSON.data.jumlah_notif);
                let data = responseJSON.data.data;

                if (data != null) {
                    var count = Object.keys(data).length;
                    for (var i = 0, text = ""; i < count; i++) {
                        var a = i + 1;
                        text += '<a class="dropdown-item" href="<?= Url::to(["/notification/redirect"]) ?>?id=' + data[i].id + '">' + data[i].title + '</a>';
                    }
                    $(".notificationcontent").html(text);
                } else {
                    text = '<a class="dropdown-item" href="#">Tidak ada notifikasi</a>';
                    $(".notificationcontent").html(text);
                }
                id++;
                notifikasi(id);
            });
        }
        notifikasi(1);
    </script>
<?php JSRegister::end();
endif ?>