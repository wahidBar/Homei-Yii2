<?php

use richardfan\widget\JSRegister;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\captcha\Captcha;

$setting = \app\models\SiteSetting::find()->one();
?>

<main class="main-container">
    <div class="login-wrapper">

        <div class="left-container">
            <div class="header">
                <!-- <a class="arrow" href="#">←</a> -->
                <?= Html::a(
                    '<svg fill="#000000" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" width="24px" height="24px">    <path d="M 12 2 A 1 1 0 0 0 11.289062 2.296875 L 1.203125 11.097656 A 0.5 0.5 0 0 0 1 11.5 A 0.5 0.5 0 0 0 1.5 12 L 4 12 L 4 20 C 4 20.552 4.448 21 5 21 L 9 21 C 9.552 21 10 20.552 10 20 L 10 14 L 14 14 L 14 20 C 14 20.552 14.448 21 15 21 L 19 21 C 19.552 21 20 20.552 20 20 L 20 12 L 22.5 12 A 0.5 0.5 0 0 0 23 11.5 A 0.5 0.5 0 0 0 22.796875 11.097656 L 12.716797 2.3027344 A 1 1 0 0 0 12.710938 2.296875 A 1 1 0 0 0 12 2 z"/></svg>',
                    ['home/index'],
                    ['class' => 'arrow']
                ) ?>
                <?= Html::a(
                    'Login',
                    ['site/login'],
                    ['class' => 'register']
                ) ?>
            </div>
            <div class="main">
                <h2>Login</h2>
                <p>Selamat datang! Silahkan mengisi form dibawah atau login dengan google.</p>
                
                <?php $form = ActiveForm::begin([
                    'id' => 'forgot-form',
                    'enableClientValidation' => false,
                ]); ?>


                <label for="Email">Email</label>
                <input class="form-control" name="Lupa[email]" id="ContactForm_email" type="email">

                <div class="login-now">
                    <?= Html::submitButton('Submit', ['name' => 'login-button', 'id' => 'logIn']) ?>
                </div>

                <span class="line"></span>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="footer">
                <div class="social-media">
                    <h3>Atau Login dengan</h3>
                    <div class="links-wrapper">
                        <!-- local -->
                        <!-- <a href="https://accounts.google.com/o/oauth2/v2/auth?client_id=451233796463-ng74m66j8fgc0v2cp5fdbcfg6s2i9v3f.apps.googleusercontent.com&redirect_uri=http://localhost/homei/web/home/google&scope=profile email openid&response_type=code&access_type=offline&include_granted_scopes=true"> -->
                        <!-- server -->
                        <a href="https://accounts.google.com/o/oauth2/v2/auth?client_id=192180383913-l6cvmkl7m5oq2bfo61to3iv6vnn8vukn.apps.googleusercontent.com&redirect_uri=http://homei.co.id/web/home/google&scope=profile email openid&response_type=code&access_type=offline&include_granted_scopes=true">

                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 48 48">
                                <defs>
                                    <path id="a" d="M44.5 20H24v8.5h11.8C34.7 33.9 30.1 37 24 37c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 2.9l6.4-6.4C34.6 4.1 29.6 2 24 2 11.8 2 2 11.8 2 24s9.8 22 22 22c11 0 21-8 21-22 0-1.3-.2-2.7-.5-4z" />
                                </defs>
                                <clipPath id="b">
                                    <use xlink:href="#a" overflow="visible" />
                                </clipPath>
                                <path clip-path="url(#b)" fill="#FBBC05" d="M0 37V11l17 13z" />
                                <path clip-path="url(#b)" fill="#EA4335" d="M0 11l17 13 7-6.1L48 14V0H0z" />
                                <path clip-path="url(#b)" fill="#34A853" d="M0 37l30-23 7.9 1L48 0v48H0z" />
                                <path clip-path="url(#b)" fill="#4285F4" d="M48 48L17 24l-4-3 35-10z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!--Right container(img) -->
        <div class="side-container" style="background-image: url(<?= \Yii::$app->request->baseUrl . "/uploads/" . $setting->gambar_login ?>)">
            <div class="side-text-container">
                <div class="short-line">
                    <hr>
                </div>

                <div class="text">
                    <h3><?= $setting->tagline ?></h3>
                    <p><?= $setting->tagline2 ?></p>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
$this->registerJsFile("@web/homepage/js/sweetalert2.all.min.js");
?>
<?php JSRegister::begin(); ?>
<script>
     $(document).ready(function() {
      var success = "<?= \Yii::$app->session->getFlash('success') ?>";
      var error = "<?= \Yii::$app->session->getFlash('error') ?>";
      if (error !== "") {
        Swal.fire("Gagal!", "<?= \Yii::$app->session->getFlash('error') ?>", "error");
      } 
      if (success !== "") {
        Swal.fire("Berhasil!", "<?= \Yii::$app->session->getFlash('success') ?>", "success");
      }
    });
</script>
<?php JSRegister::end(); ?>