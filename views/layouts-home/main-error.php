<?php

use app\assets\ErrorAsset;
use dmstr\widgets\Alert;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

ErrorAsset::register($this);

?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language?>">

<head>
    <link rel="icon" type="image/png"
        href="<?=\Yii::$app->request->hostInfo . \Yii::$app->homeUrl . ($webProfile->app_icon ? $webProfile->app_icon : "uploads/logo.png")?>" />
    <meta charset="<?=Yii::$app->charset?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?=Html::csrfMetaTags()?>
    <title><?=Html::encode($this->title)?></title>
    <?php $this->head()?>
    <style>
        /*======================
            404 page
        =======================*/


        .page_404 {
            padding: 40px 0;
            background: #fff;
            font-family: 'Arvo', serif;
        }

        .page_404 img {
            width: 100%;
        }

        .four_zero_four_bg {

            background-image: url(https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif);
            height: 400px;
            background-position: center;
        }


        .four_zero_four_bg h1 {
            font-size: 80px;
        }

        .four_zero_four_bg h3 {
            font-size: 80px;
        }

        .link_404 {
            color: #fff !important;
            padding: 10px 20px;
            background: #39ac31;
            margin: 20px 0;
            display: inline-block;
        }

        .contant_box_404 {
            margin-top: -50px;
        }
    </style>
</head>

<body>

    <?php $this->beginBody()?>

    <?=Alert::widget()?>
    <section class="page_404">
        <div class="container">
            <?=$content?>
        </div>
    </section>

    <?php $this->endBody()?>
</body>

</html>
<?php $this->endPage()?>