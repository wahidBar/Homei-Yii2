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
            <?= Html::a(
                    '←',
                    ['site/login'],
                    ['class' => 'arrow']
                ) ?>
                <?= Html::a(
                    'Login',
                    ['site/login'],
                    ['class' => 'register']
                ) ?>
            </div>
            <div class="main">
                <h2>Ganti Password</h2>
                <p>Silahkan masukkan password baru anda di form berikut.</p>
                 
                <?php $form = ActiveForm::begin([
                    'id' => 'Ganti-form',
                ]); ?>

                <div class="row">
                    <label for="Email">Password baru</label>
                    <input class="form-control" name="Ganti[password]" id="ContactForm_email" type="password">
                    <input name="Ganti[tokenhid]" id="ContactForm_email" type="hidden" value="<?php echo $model->token ?>">
                    
                    <div class="login-now">
                        <?= Html::submitButton('Submit', ['name' => 'login-button', 'id' => 'logIn']) ?>
                    </div>
                    <span class="line"></span>
                </div>
                
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