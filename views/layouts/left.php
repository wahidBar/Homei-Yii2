<?php

use app\components\Constant;
use app\models\RoleUser;
use yii\helpers\Html;
$setting = \app\models\SiteSetting::find()->one();
?>

<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">

    <div class="left side-menu">
        <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
            <i class="ion-close"></i>
        </button>

        <!-- LOGO -->
        <div class="topbar-left">
            <div class="text-center">
                <a href="#" class="logo">
                    <?= Html::img( \Yii::$app->request->baseUrl . "/uploads/" . $setting->logo, ["height" => '36']) ?>
                </a>
                <!-- <a href="index.html" class="logo"><img src="assets/images/logo.png" height="24" alt="logo"></a> -->
            </div>
        </div>

        <div class="sidebar-inner slimscrollleft">

            <div id="sidebar-menu">

                <?php
                // $roles = RoleUser::find()->where(['id_user'=>\Yii::$app->user->identity->id])->select('id_role')->column();
                $roles = [Constant::getUser()->role_id];

                $items = \app\components\SidebarMenu::getMenu($roles);
                
                ?>
                <?= app\components\annex\Menu::widget(
                    [
                        'options' => ['class' => 'sidebar-menu'],
                        'items' => $items,
                    ]
                ) ?>
            </div>
            <div class="clearfix"></div>
        </div> <!-- end sidebarinner -->
    </div>
    <!-- Left Sidebar End -->
</div>