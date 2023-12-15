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
* @var app\models\MasterSatuan $model
* @var app\components\annex\ActiveForm $form
*/

?>

<?php $form = ActiveForm::begin([
    'id' => 'MasterSatuan',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary alert alert-error'
]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="clearfix"></div>
<div class="d-flex  flex-wrap">

	<?= $form->field($model, 'nama', \app\components\Constant::COLUMN())->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'keterangan', \app\components\Constant::COLUMN())->textInput(['maxlength' => true]) ?>
	<?=         // modified by Defri Indra
        $form->field($model, 'jenis_satuan_id', \app\components\Constant::COLUMN())->widget(\kartik\select2\Select2::classname(), [
            'name' => 'class_name',
            'model' => $model,
            'attribute' => 'jenis_satuan_id',
            'data' => \yii\helpers\ArrayHelper::map(app\models\MasterJenisSatuan::find()->all(), 'id', 'nama'),
            'options' => [
                'placeholder' => $model->getAttributeLabel('jenis_satuan_id'),
                'multiple' => false,
                'disabled' => (isset($relAttributes) && isset($relAttributes['jenis_satuan_id'])),
            ]
        ]); ?>
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