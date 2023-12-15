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
 * @var app\models\ProyekCctv $model
 * @var app\components\annex\ActiveForm $form
 */

?>

<?php $form = ActiveForm::begin([
    'action' => \yii\helpers\Url::current(["id_project" => $model->id_proyek]),
    'id' => 'ProyekCctv',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary alert alert-error'
]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="clearfix"></div>
<div class="d-flex  flex-wrap">
    <?= $form->field($model, 'id_proyek', ['template' => '{input}'])->hiddenInput()->label(false); ?>
    <?= $form->field($model, 'lokasi', \app\components\Constant::COLUMN())->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'tipe', \app\components\Constant::COLUMN())->dropDownList(\app\models\ProyekCctv::getTipes()) ?>
    <?= $form->field($model, 'link', \app\components\Constant::COLUMN(1))->textInput(['maxlength' => true]) ?>
    <div class="clearfix"></div>
</div>
<hr />
<div class="row">
    <div class="col-md-offset-3 col-md-10">
        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']); ?>
        <?= Html::a('<i class="fa fa-chevron-left"></i> Kembali', ['proyek/view', 'id' => $model->id_proyek], ['class' => 'btn btn-default']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>