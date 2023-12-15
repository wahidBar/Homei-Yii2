<?php

use app\components\Constant;
use app\models\KonsultasiChat;
use richardfan\widget\JSRegister;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

$user = Constant::getUser();
if ($user->role_id == Constant::ROLE_KONSULTAN) :
    $total_chat = KonsultasiChat::find()->joinWith(['konsultasi'])->andWhere([
        'and',
        ['!=', 't_konsultasi_chat.user_id', Yii::$app->user->id],
        [
            'read' => 0,
            't_konsultasi.id_konsultan' => $user->id,
        ],
    ])->count();
endif;

?>

<!-- Top Bar Start -->
<div class="topbar">

    <nav class="navbar-custom">

        <ul class="list-inline float-right mb-0">

            <?php if ($user->role_id == Constant::ROLE_KONSULTAN) : ?>
                <li class="list-inline-item notification-list">
                    <a class="nav-link arrow-none waves-effect" href="<?= Url::to(['/konsultasi']) ?>">
                        <i class="ti-comment-alt noti-icon"></i>
                        <span id="header-count-chat" class="badge badge-success noti-icon-badge"><?= $total_chat ?? 0 ?></span>
                    </a>
                </li>
            <?php endif ?>

            <li class="list-inline-item dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="ti-bell noti-icon"></i>
                    <span class="badge badge-success noti-icon-badge notificationcount">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg" style="width: auto;">
                    <div class="dropdown-item noti-title">
                        <h5><span class="badge badge-danger float-right notificationcount">0</span>Notifikasi</h5>
                    </div>

                    <div class="notificationcontent">
                        <!-- <a class="dropdown-item" href="#">Akun Saya</a> -->
                    </div>
                    <div class="dropdown-divider"></div>
                    <!-- <a href="javascript:void(0);" class="dropdown-item notify-item">
                        Lihat Semua
                    </a> -->
                    <?= Html::a('Lihat Semua', Url::to(['notification/index']), ['class' => 'dropdown-item notify-item']); ?>

                </div>
            </li>

            <li class="list-inline-item dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <?php
                    $pathFile = \Yii::getAlias('@webroot') . '/uploads/' . Yii::$app->user->identity->photo_url;
                    // echo "<br>Absolute Path:" . $pathFile;
                    if (file_exists($pathFile)) {
                    ?>
                        <img src="<?= \Yii::$app->request->baseUrl . "/uploads/" . Yii::$app->user->identity->photo_url ?>" class="rounded-circle" alt="">
                    <?php
                    } else {
                    ?>
                        <img src="<?= \Yii::$app->request->baseUrl . "/uploads/default.png" ?>" class="rounded-circle" alt="">
                    <?php
                    }
                    ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    <!-- item-->
                    <div class="dropdown-item noti-title">
                        <h5>Welcome</h5>
                    </div>
                    <?= Html::a(
                        '<i class="mdi mdi-account-circle m-r-5 text-muted"></i> Profile',
                        ['site/profile'],
                        ['class' => 'dropdown-item']
                    ) ?>
                    <div class="dropdown-divider"></div>
                    <?= Html::a(
                        '<i class="mdi mdi-logout m-r-5 text-muted"></i> Sign out',
                        ['/site/logout'],
                        ['data-method' => 'post', 'class' => 'dropdown-item', 'child']
                    ) ?>
                </div>
            </li>

        </ul>

        <ul class="list-inline menu-left mb-0">
            <li class="float-left">
                <button class="button-menu-mobile open-left waves-light waves-effect">
                    <i class="mdi mdi-menu"></i>
                </button>
            </li>
            <!-- <li class="hide-phone app-search">
                <form role="search" class="">
                    <input type="text" placeholder="Search..." class="form-control">
                    <a href=""><i class="fa fa-search"></i></a>
                </form>
            </li> -->
        </ul>

        <div class="clearfix"></div>

    </nav>

</div>
<!-- Top Bar End -->

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
            var data = responseJSON.data.data;
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

        });
        notifikasi(id);
    }
    notifikasi(1);
</script>
<?php JSRegister::end();
