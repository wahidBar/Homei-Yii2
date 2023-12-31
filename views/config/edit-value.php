<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use app\components\annex\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Config $model
 */

$this->title = 'Edit Nilai';
$this->params['breadcrumbs'][] = ['label' => 'Config', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$type = $model->type;
$attributes = $model->attribute;
if ($attributes == null) {
    $attributes = [];
} else {
    $attributes = json_decode($attributes, true);
    if (json_last_error()) {
        $attributes = [];
    }
}
?>

<div class="row">
    <div class="col-md-12">
        <p>
            <?= Html::a('Kembali', \yii\helpers\Url::previous(), ['class' => 'btn btn-default']) ?>
        </p>
        <div class="card m-b-30">
            <div class="card-body">

                <?php $form = ActiveForm::begin([
                    'id' => 'Config',
                    'layout' => 'horizontal',
                    'enableClientValidation' => true,
                    'errorSummaryCssClass' => 'error-summary alert alert-error'
                ]);
                ?>
                <?php echo $form->errorSummary($model); ?>

                <div class="clearfix"></div>
                <div class="d-flex  flex-wrap">
                    <?= $form->field($model, 'value', \app\components\Constant::COLUMN(1))->$type($attributes)->label($model->name) ?>
                    <div class="clearfix"></div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-md-offset-3 col-md-10">
                        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']); ?>
                        <?= Html::a('<i class="fa fa-chevron-left"></i> Kembali', ['index'], ['class' => 'btn btn-default']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>