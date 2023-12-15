<?php

if ($model->params) $parameter_value = json_decode($model->params);
?>
<div class="table-responsive">
    <table class="table table-hover table-stripped table-bordered">
        <thead style="background-color: #9A83DA;color: #fff;">
            <th style="width: 25vw"><?= Yii::t("cruds", "Parameter") ?></th>
            <th style="width: 75vw"><?= Yii::t("cruds", "Nilai") ?></th>
        </thead>
        <tbody class="container-items">
            <?php foreach ($params as $i => $param) : ?>
                <?php
                if ($model->isNewRecord == false) {
                    $$param = $parameter_value->$param;
                } else {
                    $$param = 0;
                }
                ?>
                <tr class="item">
                    <td>
                        <?= $param ?>
                    </td>
                    <td>
                        <?= $form->field($model, "[$param]params", ["template" => "{input}"])->textInput(['value' => $$param, "required" => "required"]); ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>