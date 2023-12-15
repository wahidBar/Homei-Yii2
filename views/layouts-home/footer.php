<!-- Footer -->
<?php
$setting = \app\models\SiteSetting::find()->all();
?>
<footer class="footer-3">
  <div class=" section-content section-content--w1140">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-md-6">
          <h2 class="logo-footer">
            <a href="<?= \Yii::$app->request->baseUrl . "/home" ?>">
              <img alt="Logo" src=<?= \Yii::$app->request->baseUrl . "/uploads/" . $setting[0]['logo_putih'] ?> width="100px">
            </a>
          </h2>
          <p>
            <?= $setting[0]['tentang_web'] ?>
          </p>
          <h5 class="title-footer">
            IKUTI KAMI
          </h5>
          <div class="social-footer">
            <?php if ($setting[0]['facebook']) : ?>
              <a href="<?= $setting[0]['facebook'] ?>" target="_blank">
                <i class="zmdi zmdi-facebook"></i>
              </a>
            <?php endif; ?>
            <?php if ($setting[0]['twitter']) : ?>
              <a href="<?= $setting[0]['twitter'] ?>" target="_blank">
                <i class="zmdi zmdi-twitter"></i>
              </a>
            <?php endif ?>
            <?php if ($setting[0]['instagram']) : ?>
              <a href="<?= $setting[0]['instagram'] ?>" target="_blank">
                <i class="zmdi zmdi-instagram"></i>
              </a>
            <?php endif ?>
            <?php if ($setting[0]['youtube']) : ?>
              <a href="<?= $setting[0]['youtube'] ?>" target="_blank">
                <i class="zmdi zmdi-youtube"></i>
              </a>
            <?php endif ?>
            <?php if ($setting[0]['tiktok']) : ?>
              <a href="<?= $setting[0]['tiktok'] ?>" target="_blank">
                <svg viewBox="4 4 42 42" xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="#ccc">
                  <path d="M41 4H9C6.243 4 4 6.243 4 9v32c0 2.757 2.243 5 5 5h32c2.757 0 5-2.243 5-5V9c0-2.757-2.243-5-5-5m-3.994 18.323a7.482 7.482 0 0 1-.69.035 7.492 7.492 0 0 1-6.269-3.388v11.537a8.527 8.527 0 1 1-8.527-8.527c.178 0 .352.016.527.027v4.202c-.175-.021-.347-.053-.527-.053a4.351 4.351 0 1 0 0 8.704c2.404 0 4.527-1.894 4.527-4.298l.042-19.594h4.02a7.488 7.488 0 0 0 6.901 6.685v4.67" />
                </svg>
              </a>
            <?php endif ?>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <h5 class="title-footer m-b-26">
            KONTAK KAMI
          </h5>
          <p class="con__item">
            <i class="fa fa-building" aria-hidden="true"></i>
            <?= $setting[0]['alamat'] ?>
          </p>
          <p class="con__item">
            <i class="fa fa-phone" aria-hidden="true"></i>
            <?= $setting[0]['no_telp'] ?>
          </p>
          <p class="con__item">
            <i class="fa fa-envelope-o" aria-hidden="true"></i>
            <?= $setting[0]['email'] ?>
          </p>

          <?php "<h5 class=\"title-footer m-b-26\">
            REKENING KAMI
          </h5>" ?>
          <?php $pembayarans = []; // \app\models\MasterPembayaran::find()->where(['status' => 1])->all(); 
          ?>
          <?php foreach ($pembayarans as $bank) : ?>
            <p class="con__item"><strong><?= $bank->nama_bank ?></strong> : <?= $bank->nomor_rekening ?> (<?= $bank->atas_nama ?>)</p>
          <?php endforeach ?>
        </div>
        <div class="col-lg-4 col-md-6">
          <h5 class="title-footer m-b-30">
            HOMEi
          </h5>
          <p class="con__item">
            <a class="text-white" href="<?= \Yii::$app->request->BaseUrl ?>/home/kebijakan-privasi">Kebijakan Privasi</a>
          </p>
          <p class="con__item">
            <a class="text-white" href="<?= \Yii::$app->request->BaseUrl ?>/home/syarat-ketentuan">Syarat & Ketentuan</a>
          </p>

          <h5 class="title-footer">
            Download Aplikasi Kami
          </h5>
          <p class="con__item">
            <a href="<?= $setting[0]['playstore'] ?>" target="_blank">
              <img alt="Logo" src=<?= \Yii::$app->request->baseUrl . "/uploads/google-play.png" ?> width="200px" class="mt-3">
            </a>
          </p>
        </div>
      </div>
      <div class="copyright-2">
        <div>
          Copyright © <?= date('Y') ?>
          <span><?= $setting[0]['judul'] ?></span>. All rights reserved.
        </div>
      </div>

    </div>
  </div>
</footer>
<!-- End Footer -->