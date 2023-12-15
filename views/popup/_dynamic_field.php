<?php
$baseName = \yii\helpers\StringHelper::basename(get_class($model));
$idname = strtolower($baseName);
$attributeName = "android_params";
?>
<tr class="dynamic-element">
    <td>
        <div class="col-md-12 field-<?= $idname ?>-<?= isset($attributeName) ? $attributeName : "params" ?>-0-0" style="padding:0px;">
            <div class="col-lg-12">
                <div class="col-md-12"></div>
                <div class="col-md-12"><input type="text" id="<?= $idname ?>-<?= isset($attributeName) ? $attributeName : "params" ?>-0-0" class="form-control" name="<?= $baseName ?>[<?= isset($attributeName) ? $attributeName : "params" ?>][0][0]" value="name">
                    <p class="help-block help-block-error "></p>
                </div>
            </div>
        </div>
    </td>
    <td>
        <div class="col-md-12 field-<?= $idname ?>-<?= isset($attributeName) ? $attributeName : "params" ?>-0-1" style="padding:0px;">
            <div class="col-lg-12">
                <div class="col-md-12"></div>
                <div class="col-md-12"><input type="text" id="<?= $idname ?>-<?= isset($attributeName) ? $attributeName : "params" ?>-0-1" class="form-control" name="<?= $baseName ?>[<?= isset($attributeName) ? $attributeName : "params" ?>][0][1]" value="defri">
                    <p class="help-block help-block-error "></p>
                </div>
            </div>
        </div>
    </td>
    <td>
        <span class="add-one"><i class="fa fa-plus"></i></span>
        <span class="delete"><i class="fa fa-minus"></i></span>
    </td>
</tr>