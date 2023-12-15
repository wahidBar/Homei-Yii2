<?php

use dmstr\helpers\Html;
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
          $text = "active";
        }

        if ($text != "") break;
      }
    } else {
      $target = Url::to([$target]);
      if ($withindex) $target .= "/index";
      if (stripos($current_url, $target) !== false) {
        $text = "active";
      }
    }

    return $text;
  }
}
?>

<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link <?= checkCurrentNav("/site-setting/update/1") ?>" href="<?= Url::to(["/site-setting/update/1"]) ?>">
      Setting Umum
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= checkCurrentNav(["/slides", "/slides/create", "/slides/view", "/slides/update"]) ?>" href="<?= Url::to(["/slides"]) ?>">
    Slides
  </a>
</li>
<li class="nav-item">
  <a class="nav-link <?= checkCurrentNav(["/tentang-homei", "/tentang-homei/create", "/tentang-homei/view", "/tentang-homei/update"]) ?>" href="<?= Url::to(["/tentang-homei"]) ?>">
    Tentang Homei
  </a>
</li>
  <li class="nav-item">
    <a class="nav-link <?= checkCurrentNav(["/tab-home", "/tab-home/create", "/tab-home/view", "/tab-home/update"]) ?>" href="<?= Url::to(["/tab-home"]) ?>">
      Tab Home
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= checkCurrentNav(["/galeri", "/galeri/create", "/galeri/view", "/galeri/update"]) ?>" href="<?= Url::to(["/galeri"]) ?>">
      Galeri
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= checkCurrentNav(["/contoh-produk", "/contoh-produk/create", "/contoh-produk/view", "/contoh-produk/update", "/detail-contoh-produk/create", "/detail-contoh-produk/update"]) ?>" href="<?= Url::to(["/contoh-produk"]) ?>">
      Contoh Produk
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= checkCurrentNav(["/testimonials", "/testimonials/create", "/testimonials/view", "/testimonials/update"]) ?>" href="<?= Url::to(["/testimonials"]) ?>">
      Testimoni
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= checkCurrentNav(["/partners", "/partners/create", "/partners/view", "/partners/update"]) ?>" href="<?= Url::to(["/partners"]) ?>">
      Partner Homei
    </a>
  </li>
  
</ul>