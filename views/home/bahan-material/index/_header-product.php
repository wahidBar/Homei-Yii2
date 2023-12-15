<?php

use yii\helpers\Url;
?>
<div class="row">
    <div class="col-lg-4 col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="sort-left pull-left">
            <span class="font-weight-bold">
                <select name="sort" id="sort" class="form-control">
                    <option value="0" <?= Yii::$app->request->get('sort') == "0" ? "selected" : "" ?>><?= Yii::t("cruds", "Sortir standar") ?></option>
                    <option value="1" <?= Yii::$app->request->get('sort') == "1" ? "selected" : "" ?>><?= Yii::t("cruds", "Sortir dari yang termurah") ?></option>
                    <option value="2" <?= Yii::$app->request->get('sort') == "2" ? "selected" : "" ?>><?= Yii::t("cruds", "Sortir dari yang termahal") ?></option>
                    <option value="3" <?= Yii::$app->request->get('sort') == "3" ? "selected" : "" ?>><?= Yii::t("cruds", "Sortir dari yang terlama") ?></option>
                </select>
            </span>
        </div>
    </div>
    <div class="col-lg-4 col-lg-4 col-md-4 col-sm-5 col-6">
        <div class="text-summary">
            <span class="font-weight-bold">
                <?= $summary ?>
            </span>
        </div>
    </div>
    <?= $this->render('../components/_keranjang', [
        "jumlah_carts" => $jumlah_carts,
        "subtotal_cart" => $subtotal_cart,
        "carts" => $carts,
    ]) ?>
</div>