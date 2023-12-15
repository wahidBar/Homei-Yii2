<?php

$value = floatval($value);
$previous_value = floatval($previous_value);
if ($value == $previous_value) {
    $status = "fa-balance-scale";
    $status_color = "text-secondary";
} else {
    $status = $value > $previous_value ? "fa-arrow-up" : "fa-arrow-down";
    $status_color = $value > $previous_value ? "text-success" : "text-danger";
}


?>
<div class="col-lg-6 col-md-6 mb-2">
    <div class="card card-default" style="border-radius: 15rem;">
        <div class="card-body" style="display: flex;align-items: center;padding-left: 1rem; padding-right: 3rem">
            <div class="text-left iconize">
                <i class="fa <?= $icon ?> <?= $iconClass ?? "text-warning" ?>" style="font-size: 2rem"></i>
            </div>
            <div class="d-inline-block" style="padding-left: 1rem;flex:1">
                <span style="font-size: .8rem">
                    <?= $title ?>
                </span>
                <br />
                <strong style="font-size: 1.2rem;">
                    <span id="valuebox<?= $id ?>">
                        <?= $value ?>
                    </span>
                    <?= $unit ?></strong>
            </div>
            <div>
                <i  id="statusbox<?= $id ?>" class="fa <?= $status ?> <?= $status_color ?>" style="font-size: 2rem"></i>
            </div>
        </div>
    </div>
</div>