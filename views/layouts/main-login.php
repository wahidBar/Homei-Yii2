<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

\app\assets\LoginAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <?php
    $setting = \app\models\SiteSetting::find()->one();
    ?>
    <meta name="title" content="Temukan Kemudahan Renovasi Maupun Monitoring Project Lewat HOMEi">
    <meta name="description" content="Jasa Renovasi Rumah, Tukang Harian, Konsultan serta Penjualan Bahan Material dengan harga yang bersaing dapat melalui smartphone">
    <meta name="keywords" content="Renovasi Bangunan,Tukang Harian,Kontraktor,Konsultan,Bahan Material,Jasa,Proyek,Project,Desain,Design,Produk,Monitoring">
    <meta name="robots" content="index, follow">
    <meta property="og:image" content="https://homei.co.id/web/uploads/site/20220611_b7f5113f83376fc4334a2f305303bd99b533a5c4.png">
    <meta http-equiv="content-language" content="id">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="Indonesian">

    <link rel="icon" type="image/png" href=<?= \Yii::$app->request->baseUrl . "/uploads/" . $setting->icon ?> />
    <title><?= $setting->judul ?></title>
    <script>
        var baseUrl = "<?= Yii::$app->urlManager->baseUrl ?>";
    </script>
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>
    <?= $content ?>
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
