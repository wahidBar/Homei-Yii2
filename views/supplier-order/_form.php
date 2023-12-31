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
* @var app\models\SupplierOrder $model
* @var app\components\annex\ActiveForm $form
*/

?>

<?php $form = ActiveForm::begin([
    'id' => 'SupplierOrder',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary alert alert-error'
]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="clearfix"></div>
<div class="d-flex  flex-wrap">

	<?=         // modified by Defri Indra
        $form->field($model, 'user_id', \app\components\Constant::COLUMN())->widget(\kartik\select2\Select2::classname(), [
            'name' => 'class_name',
            'model' => $model,
            'attribute' => 'user_id',
            'data' => \yii\helpers\ArrayHelper::map(app\models\User::find()->all(), 'id', 'name'),
            'options' => [
                'placeholder' => $model->getAttributeLabel('user_id'),
                'multiple' => false,
                'disabled' => (isset($relAttributes) && isset($relAttributes['user_id'])),
            ]
        ]); ?>
	<?= $form->field($model, 'kode_unik', \app\components\Constant::COLUMN())->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'no_nota', \app\components\Constant::COLUMN())->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'status', \app\components\Constant::COLUMN())->textInput() ?>
	<?= $form->field($model, 'deleted_at', \app\components\Constant::COLUMN())->textInput(['type' => 'date']) ?>
	<?= $form->field($model, 'deleted_by', \app\components\Constant::COLUMN())->textInput() ?>
    <div class="clearfix"></div>
</div>
<hr/>
<div class="row">
    <div class="col-md-offset-3 col-md-10">
        <?=  Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']); ?>
        <?=  Html::a('<i class="fa fa-chevron-left"></i> Kembali', ['index'], ['class' => 'btn btn-default']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>