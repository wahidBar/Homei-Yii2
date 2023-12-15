<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use yii\helpers\Html;
use app\components\annex\ActiveForm;
use \app\components\annex\Tabs;
use kartik\file\FileInput;

/**
 * @var yii\web\View $this
 * @var app\models\Proyek $model
 */

$this->title = 'Alasan Tolak Bayar';
$this->params['breadcrumbs'][] = ['label' => 'Isian Lanjutan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <p>
            <?= Html::a('Kembali', \yii\helpers\Url::previous(), ['class' => 'btn btn-default']) ?>
        </p>
        <div class="card m-b-30">
            <div class="card-body">
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
                    <div class="clearfix"></div>

                    <?= $form->field($model, 'alasan_tolak', \app\components\Constant::COLUMN(1))->textarea(['rows' => 6]) ?>
                    <div class="clearfix"></div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-md-offset-3 col-md-10">
                        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']); ?>
                        <?= Html::a('<i class="fa fa-chevron-left"></i> Kembali', ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>