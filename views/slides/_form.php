<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use yii\helpers\Html;
use app\components\annex\ActiveForm;
use \app\components\annex\Tabs;
use richardfan\widget\JSRegister;

/**
 * @var yii\web\View $this
 * @var app\models\Slides $model
 * @var app\components\annex\ActiveForm $form
 */

?>

<?php $form = ActiveForm::begin([
    'id' => 'Slides',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary alert alert-error'
]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="clearfix"></div>
<div class="d-flex  flex-wrap">
    <?= fieldUploaImage($model, $form, "image") ?>

    <?= $form->field($model, 'title', \app\components\Constant::COLUMN())->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'subtitle', \app\components\Constant::COLUMN())->textInput(['maxlength' => true]) ?>
    <div class="col-md-12">
        <?= $form->field($model, 'type', \app\components\Constant::COLUMN())->checkbox() ?>
    </div>
    <div id="inputweb" class="col-md-12">
        <?= $form->field($model, 'button_title', \app\components\Constant::COLUMN(1))->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'button_link', \app\components\Constant::COLUMN(1))->textInput(['maxlength' => true]) ?>
    </div>
    <div class="clearfix"></div>
    <div id="inputandroid" class="col-md-12">
        <div class="col-md-12">
            <?= $form->field($model, 'redirect_type', \app\components\Constant::COLUMN())->checkbox() ?>
        </div>
        <?= $form->field($model, 'component', \app\components\Constant::COLUMN(1))->textInput(['maxlength' => true]) ?>
        <div class="table-responsive">
            <table class="table table-stripped table-bordered">
                <thead style="background-color: rgb(0, 20, 100);color:white">
                    <th style="color:white">Nama</th>
                    <th style="color:white">Nilai</th>
                    <td style="text-align:center">
                        <span class="add-one"><i class="fa fa-plus"></i></span>
                        <span class="delete"><i class="fa fa-minus"></i></span>
                    </td>
                </thead>
                <tbody class="dynamic-stuff">
                    <?php if ($model->params != null) :
                        $i = 0; ?>
                        <?php foreach (json_decode($model->params) as $key => $val) : ?>
                            <tr class="dynamic-element">
                                <td>
                                    <?= $form->field($model, "params[$i][0]", \app\components\Constant::COLUMN(1))->textInput(['value' => $key, 'style' => 'min-width:150px'])->label(false) ?>
                                </td>
                                <td>
                                    <?= $form->field($model, "params[$i][1]", \app\components\Constant::COLUMN(1))->textInput(['value' => $val, 'style' => 'min-width:150px'])->label(false) ?>
                                </td>
                                <td style="text-align:center">
                                    <span class="add-one"><i class="fa fa-plus"></i></span>
                                    <span class="delete"><i class="fa fa-minus"></i></span>
                                </td>
                            </tr>
                        <?php $i++;
                        endforeach ?>
                    <?php else : ?>
                        <tr class="dynamic-element">
                            <td>
                                <?= $form->field($model, "params[0][0]", \app\components\Constant::COLUMN(1))->textInput()->label(false) ?>
                            </td>
                            <td>
                                <?= $form->field($model, "params[0][1]", \app\components\Constant::COLUMN(1))->textInput()->label(false) ?>
                            </td>
                            <td style="text-align:center">
                                <span class="add-one"><i class="fa fa-plus"></i></span>
                                <span class="delete"><i class="fa fa-minus"></i></span>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<hr />
<div class="row">
    <div class="col-12 text-center">
        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']); ?>
        <?= Html::a('<i class="fa fa-chevron-left"></i> Kembali', ['index'], ['class' => 'btn btn-default']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>


<?php
$baseName = \yii\helpers\StringHelper::basename(get_class($model));
$idname = strtolower($baseName);
$template = $this->render('_dynamic_field', ['model' => $model]);
?>

<?php JSRegister::begin() ?>
<script>
    $('#inputandroid').attr('style', 'display:none');
    $('#inputweb').attr('style', 'display:block');
    $('#slides-type').on('change', (event) => {
        if ($(event.target).is(":checked")) {
            $('#inputandroid').attr('style', 'display:block');
            $('#inputweb').attr('style', 'display:none');
        } else {
            $('#inputandroid').attr('style', 'display:none');
            $('#inputweb').attr('style', 'display:block');
        }
    });
    $(document).ready(function() {

        if ($('#slides-type').is(":checked")) {
            $('#inputandroid').attr('style', 'display:block');
            $('#inputweb').attr('style', 'display:none');
        } else {
            $('#inputandroid').attr('style', 'display:none');
            $('#inputweb').attr('style', 'display:block');
        }

        let i = <?= (isset($i) ? $i : 0)  ?>;
        $('.add-one').click(function() {
            let cloning = $(`<?= $template ?>`);
            cloning.find('#<?= $idname ?>-<?= isset($attributeName) ? $attributeName : "params" ?>-0-0')[0].setAttribute('name', '<?= $baseName ?>[<?= isset($attributeName) ? $attributeName : "params" ?>][' + i + '][0]');
            cloning.find('#<?= $idname ?>-<?= isset($attributeName) ? $attributeName : "params" ?>-0-1')[0].setAttribute('name', '<?= $baseName ?>[<?= isset($attributeName) ? $attributeName : "params" ?>][' + i + '][1]');
            cloning.find('#<?= $idname ?>-<?= isset($attributeName) ? $attributeName : "params" ?>-0-0')[0].value = '';
            cloning.find('#<?= $idname ?>-<?= isset($attributeName) ? $attributeName : "params" ?>-0-1')[0].value = '';
            cloning.appendTo('.dynamic-stuff').show();
            i++;
            attach_add();
            attach_delete();
        });

        function attach_add() {
            $('.add-one').off();
            $('.add-one').click(function() {
                let cloning = $(`<?= $template ?>`);
                cloning.find('#<?= $idname ?>-<?= isset($attributeName) ? $attributeName : "params" ?>-0-0')[0].setAttribute('name', '<?= $baseName ?>[<?= isset($attributeName) ? $attributeName : "params" ?>][' + i + '][0]');
                cloning.find('#<?= $idname ?>-<?= isset($attributeName) ? $attributeName : "params" ?>-0-1')[0].setAttribute('name', '<?= $baseName ?>[<?= isset($attributeName) ? $attributeName : "params" ?>][' + i + '][1]');
                cloning.find('#<?= $idname ?>-<?= isset($attributeName) ? $attributeName : "params" ?>-0-0')[0].value = '';
                cloning.find('#<?= $idname ?>-<?= isset($attributeName) ? $attributeName : "params" ?>-0-1')[0].value = '';
                cloning.appendTo('.dynamic-stuff').show();
                i++;
                attach_add();
                attach_delete();
            });
        }

        $('.delete').click(function() {
            $('.delete').off();
            $(this).closest('.dynamic-element').remove();
            attach_add();
            attach_delete();
        });

        function attach_delete() {
            $('.delete').off();
            $('.delete').click(function() {
                $(this).closest('.dynamic-element').remove();
            });
        }
    });
</script>
<?php JSRegister::end() ?>