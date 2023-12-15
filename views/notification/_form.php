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
* @var app\models\Notification $model
* @var app\components\annex\ActiveForm $form
*/

?>

<?php $form = ActiveForm::begin([
    'id' => 'Notification',
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
	<?= $form->field($model, 'title', \app\components\Constant::COLUMN())->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'description', \app\components\Constant::COLUMN())->textarea(['rows' => 6]) ?>
	<?= $form->field($model, 'controller', \app\components\Constant::COLUMN())->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'params', \app\components\Constant::COLUMN())->textarea(['rows' => 6]) ?>
	<?= $form->field($model, 'read', \app\components\Constant::COLUMN())->textInput() ?>
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