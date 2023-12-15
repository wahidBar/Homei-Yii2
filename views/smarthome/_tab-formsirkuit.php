<?php

use app\components\annex\ActiveForm;
use yii\bootstrap\Html;
?>

<?php $form = ActiveForm::begin([
    'id' => 'Smarthome-ubahsirkuit',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary alert alert-error'
]);
?>

<?php echo $form->errorSummary($modelSirkuit); ?>

<div class="clearfix"></div>
<div class="d-flex  flex-wrap">
    <?= $form->field($modelSirkuit, 'nama', \app\components\Constant::COLUMN(1))->textInput(['maxlength' => true]) ?>
    <?php if ($modelSirkuit->isNewRecord) : ?>
        <?= $form->field($modelSirkuit, 'kode_produk', \app\components\Constant::COLUMN(1))->textInput(['maxlength' => true]) ?>
        <?= $form->field($modelSirkuit, 'kode_pairing', \app\components\Constant::COLUMN(1))->textInput(['maxlength' => true]) ?>
    <?php endif; ?>
    <div class="clearfix"></div>
</div>
<hr />
<div class="row">
    <div class="col-md-offset-3 col-md-10">
        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>