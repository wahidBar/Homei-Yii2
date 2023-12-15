<?php

use yii\helpers\Html;
use richardfan\widget\JSRegister;
use yii\helpers\Url;

if (function_exists("checkCurrentNav") == false) {
    function checkCurrentNav($target, $withindex = false)
    {
        $text = "";
        $current_url = Url::current();
        if ($withindex) $current_url .= "/index";

        if (is_array($target)) {
            foreach ($target as $item) {
                $item = Url::to([$item]);
                if (stripos($current_url, $item) !== false) {
                    $text = "link-active";
                }

                if ($text != "") break;
            }
        } else {
            $target = Url::to([$target]);
            if ($withindex) $target .= "/index";
            if (stripos($current_url, $target) !== false) {
                $text = "link-active";
            }
        }

        return $text;
    }
}
?>

<li class="li-has-sub">
    <a class="<?= checkCurrentNav('/home', true) ?>" href="<?= Url::to(["home/index"], true) ?>">
        Home
    </a>
</li>
<li class="li-has-sub">
    <a class="<?= checkCurrentNav("/home/konsultasi") ?>" href="<?= Url::to(["/home/formulir-konsultasi"]) ?>">
        Konsultasi
    </a>
</li>
<li class="li-has-sub">
    <a class="<?= checkCurrentNav(["/home/portofolio", "/home/detail-portofolio"]) ?>" href="<?= Url::to(["/home/portofolio"]) ?>">
        Portofolio
    </a>
</li>
<li class="li-has-sub">
    <a class="<?= checkCurrentNav("/home/bahan-material/index") ?>" href="<?= Url::to(["/home/bahan-material/index"]) ?>">
        Bahan Material
    </a>
</li>
<li class="li-has-sub">
    <a class="<?= checkCurrentNav("/home/cari-tukang/index") ?>" href="<?= Url::to(["/home/cari-tukang/index"]) ?>">
        Cari Tukang?
    </a>
</li>
<li class="li-has-sub">
    <a class="<?= checkCurrentNav("/home/tutorial/index") ?>" href="<?= Url::to(["/home/tutorial/index"]) ?>">
        Tutorial
    </a>
</li>
<?php
if (Yii::$app->user->identity->id == null) { ?>
    <li class="li-has-sub"><?= Html::a('Login/Register', ['site/login'], ['class' => 'scroll-link']) ?></li>
<?php } else { ?>
    <li class="nav-item dropdown" style="font-weight: 600;">
        <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
            Akun
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <!-- item-->
            <div class="dropdown-item noti-title">
                <h5>Selamat Datang <?= Yii::$app->user->identity->name ?></h5>
            </div>
            <div class="dropdown-divider"></div>
            <?= Html::a(
                'Akun Saya',
                ['home/profile'],
                ['class' => 'dropdown-item ' . checkCurrentNav("/home/profile")]
            ) ?>
            <?= Html::a(
                'Rencana Pembangunan',
                ['home/form-rencana-pembangunan/index'],
                ['class' => 'dropdown-item ' . checkCurrentNav("/home/form-rencana-pembangunan")]
            ) ?>
            <?= Html::a(
                'Proyek Saya',
                ['home/proyek-saya/index'],
                ['class' => 'dropdown-item ' . checkCurrentNav("/home/proyek-saya")]
            ) ?>
            <?= Html::a(
                'Daftar Pesanan',
                ['home/bahan-material/daftar-pesanan'],
                ['class' => 'dropdown-item ' . checkCurrentNav("/home/daftar-pesanan")]
            ) ?>
            <?= Html::a(
                'Tukang Saya',
                ['home/cari-tukang/tukang-saya'],
                ['class' => 'dropdown-item ' . checkCurrentNav("/home/cari-tukang")]
            ) ?>
            <?php if (Yii::$app->user->identity->getProyekAnggota()->exists() || Yii::$app->user->identity->role_id == 1) : ?>
                <?= Html::a(
                    'Dashboard',
                    ['/site/index'],
                    ['class' => 'dropdown-item ' . checkCurrentNav("/site/index")]
                ) ?>
            <?php endif ?>
            <?= Html::a(
                'Tutorial',
                ['home/tutorial/index'],
                ['class' => 'dropdown-item ' . checkCurrentNav("/home/tutorial")]
            ) ?>
            <?= Html::a(
                'Sign out',
                ['/site/logout'],
                ['data-method' => 'post', 'class' => 'dropdown-item', 'child']
            ) ?>
        </div>
    </li>
    <li class="nav-item dropdown" style="font-weight: 600;">
        <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
            <i class='fa fa-bell pr-3'></i><span class="notificationcount"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <!-- item-->
            <div class="dropdown-item noti-title">
                <h5>Anda Memiliki <span class="notificationcount"></span> Notifikasi</h5>
            </div>
            <div class="dropdown-divider"></div>
            <div class="notificationcontent">
                <!-- <a class="dropdown-item" href="#">Akun Saya</a> -->
            </div>
            <div class="dropdown-divider"></div>
            <?= Html::a('Lihat Semua', Url::to(['home/daftar-notifikasi']), ['class' => 'dropdown-item notify-item font-weight-bold']); ?>
        </div>
    </li>
<?php } ?>