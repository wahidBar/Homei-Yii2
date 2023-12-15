<?php

use app\components\annex\LinkPager;
use yii\helpers\Url;

?>

<div class="pro-sorting clearfix">   
    <?= $this->render("_header-product", compact("summary", "carts", "jumlah_carts", "subtotal_cart")) ?>
</div>

<div class="pro-list">
    <div class="row" style="margin-bottom: 2rem;">
        <?php foreach ($response->model as $item) { ?>
            <?= $this->render("_partial", compact("item")) ?>
        <?php } ?>
    </div>
</div>

<?= $response->pagination ?>