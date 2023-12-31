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
* @var app\models\Penawaran $model
* @var app\components\annex\ActiveForm $form
*/

?>

<?php $form = ActiveForm::begin([
    'id' => 'Penawaran',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary alert alert-error'
]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="clearfix"></div>
<div class="d-flex  flex-wrap">

	<?=         // modified by Defri Indra
        $form->field($model, 'id_isian_lanjutan', \app\components\Constant::COLUMN())->widget(\kartik\select2\Select2::classname(), [
            'name' => 'class_name',
            'model' => $model,
            'attribute' => 'id_isian_lanjutan',
            'data' => \yii\helpers\ArrayHelper::map(app\models\IsianLanjutan::find()->all(), 'id', 'label'),
            'options' => [
                'placeholder' => $model->getAttributeLabel('id_isian_lanjutan'),
                'multiple' => false,
                'disabled' => (isset($relAttributes) && isset($relAttributes['id_isian_lanjutan'])),
            ]
        ]); ?>
	<?= $form->field($model, 'estimasi_waktu', \app\components\Constant::COLUMN())->textInput() ?>
	
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