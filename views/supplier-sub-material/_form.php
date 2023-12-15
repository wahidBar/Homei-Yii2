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
* @var app\models\SupplierSubMaterial $model
* @var app\components\annex\ActiveForm $form
*/

?>

<?php $form = ActiveForm::begin([
    'id' => 'SupplierSubMaterial',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary alert alert-error'
]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="clearfix"></div>
<div class="d-flex  flex-wrap">

	<?=         // modified by Defri Indra
        $form->field($model, 'material_id', \app\components\Constant::COLUMN())->widget(\kartik\select2\Select2::classname(), [
            'name' => 'class_name',
            'model' => $model,
            'attribute' => 'material_id',
            'data' => \yii\helpers\ArrayHelper::map(app\models\SupplierMaterial::find()->all(), 'id', 'nama'),
            'options' => [
                'placeholder' => $model->getAttributeLabel('material_id'),
                'multiple' => false,
                'disabled' => (isset($relAttributes) && isset($relAttributes['material_id'])),
            ]
        ]); ?>
	<?= $form->field($model, 'nama', \app\components\Constant::COLUMN())->textInput(['maxlength' => true]) ?>
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