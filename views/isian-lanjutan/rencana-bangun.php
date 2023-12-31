<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use yii\helpers\Html;
use app\components\annex\ActiveForm;
use \app\components\annex\Tabs;
use kartik\depdrop\DepDrop;

/**
 * @var yii\web\View $this
 * @var app\models\IsianLanjutan $model
 * @var app\components\annex\ActiveForm $form
 */

?>

<?php $form = ActiveForm::begin([
    'id' => 'IsianLanjutan',
    'layout' => 'horizontal',
    'enableClientValidation' => false,
    'errorSummaryCssClass' => 'error-summary alert alert-error'
]);
?>
<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card m-b-30">
            <div class="card-body">
                <div class="d-flex  flex-wrap">
                <?= $form->field($model, 'rencana_pembangunan', \app\components\Constant::COLUMN(3))->textInput(['type' => 'date']) ?>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card m-b-30">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-offset-3 col-md-10">
                        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']); ?>
                        <?= Html::a('<i class="fa fa-chevron-left"></i> Kembali', ['index'], ['class' => 'btn btn-default']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<?php ActiveForm::end(); ?>