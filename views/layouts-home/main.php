<?php

use richardfan\widget\JSRegister;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

\app\assets\HomeAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <?php
    $setting = \app\models\SiteSetting::find()->all();
    ?>
    <link rel="icon" type="image/png" href=<?= \Yii::$app->request->baseUrl . "/uploads/" . $setting[0]['icon'] ?> />
    <meta charset="<?= Yii::$app->charset ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <meta name="title" content="Temukan Kemudahan Renovasi Maupun Monitoring Project Lewat HOMEi">
    <meta name="description" content="Jasa Renovasi Rumah, Tukang Harian, Konsultan serta Penjualan Bahan Material dengan harga yang bersaing dapat melalui smartphone">
    <meta name="keywords" content="Renovasi Bangunan,Tukang Harian,Kontraktor,Konsultan,Bahan Material,Jasa,Proyek,Project,Desain,Design,Produk,Monitoring">
    <meta name="robots" content="index, follow">
    <meta property="og:image" content='<?= "https://homei.co.id".\Yii::$app->request->baseUrl . "/uploads/" . $setting[0]['logo_putih'] ?>'>
    <meta http-equiv="content-language" content="id">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="Indonesian">
    <title><?= Html::encode($this->title) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        var baseUrl = "<?= Yii::$app->urlManager->baseUrl ?>";
    </script>
    <?php $this->head() ?>

</head>

<body>
    <?php $this->beginBody() ?>

    <!-- Page Loader -->
    <div id="page-loader">
        <div class="page-loader__inner">
            <div class="page-loader__spin"></div>
        </div>
    </div>
    <!-- End Page Loader -->

    <div class="page-wrap">
        <?= $this->render('header') ?>
        <?= $this->render(
            'content.php',
            [
                'content' => $content,
            ]
        ) ?>
        <!-- Back to top -->
        <a href="" id="btn-to-top">
            <i class="fa fa-chevron-up"></i>
        </a>
        <!-- End Back to top -->
    </div>
    <?= $this->render('footer') ?>
    <!-- Back to top -->
    <!-- <a href="" id="btn-to-top">
      <i class="fa fa-chevron-up"></i>
    </a> -->
    <!-- End Back to top -->
    <?php $this->endBody() ?>
</body>

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

</html>
<?php $this->endPage() ?>
