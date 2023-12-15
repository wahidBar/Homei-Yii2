<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use yii\helpers\Html;
use app\components\annex\ActiveForm;
use \app\components\annex\Tabs;

/**
 * @var yii\web\View $this
 * @var app\models\Galeri $model
 * @var app\components\annex\ActiveForm $form
 */

?>

<?php $form = ActiveForm::begin([
    'id' => 'Galeri',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary alert alert-error'
]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="clearfix"></div>
<div class="d-flex  flex-wrap">

    <?= fieldUploaImage($model, $form, "gambar") ?>
    <?= $form->field($model, 'judul', \app\components\Constant::COLUMN())->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'style', \app\components\Constant::COLUMN())->dropDownList(['square' => 'Square', 'vertical' => 'Vertical', 'horizontal' => 'Horizontal', 'big' => 'Big'], ['prompt' => 'Pilih Style']) ?>
    <?= $form->field($model, 'keterangan', \app\components\Constant::COLUMN(1))->textarea(['rows' => 6]) ?>

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