<?php

use dmstr\helpers\Html;
use yii\helpers\Url;

?>
<div class="list-group" style="border-style: solid;border-color: #ebcd1e;border-radius: 5px;">
    <p class="text-center text-dark" style="font-size: 2rem;background-color: #ebcd1e">Menu</p>
    <?php
    $current_url = Url::current();
    $target = \Yii::$app->request->BaseUrl . "/home/proyek-saya/detail-proyek";
    if (stripos($current_url, $target) !== false) {
        $link_dashboard = " link-active";
    } ?>
    <?php
    $current_url = Url::current();
    $target = \Yii::$app->request->BaseUrl . "/home/proyek-saya/keuangan";
    if (stripos($current_url, $target) !== false) {
        $link_uang = " link-active";
    } ?>
    <?php
    $current_url = Url::current();
    $target = \Yii::$app->request->BaseUrl . "/home/proyek-saya/pantau-proyek";
    if (stripos($current_url, $target) !== false) {
        $link_cctv = " link-active";
    } ?>
    <?php
    $current_url = Url::current();
    $target = \Yii::$app->request->BaseUrl . "/home/proyek-saya/pembayaran";
    if (stripos($current_url, $target) !== false) {
        $link_bayar = " link-active";
    } ?>
    <?= Html::a('Dashboard', ['detail-proyek', 'id' => $model->kode_unik], ['class' => 'list-group-item list-group-item-action'.$link_dashboard]) ?>
    <?= Html::a('Keuangan', ['keuangan', 'id' => $model->kode_unik], ['class' => 'list-group-item list-group-item-action'.$link_uang]) ?>
    <?= Html::a('Pantau Proyek', ['pantau-proyek', 'id' => $model->kode_unik], ['class' => 'list-group-item list-group-item-action'.$link_cctv]) ?>
    <?= Html::a('Pembayaran', ['pembayaran', 'id' => $model->kode_unik], ['class' => 'list-group-item list-group-item-action'.$link_bayar]) ?>
</div>