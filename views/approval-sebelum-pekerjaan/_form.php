<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use yii\helpers\Html;
use app\components\annex\ActiveForm;
use kartik\file\FileInput;
use richardfan\widget\JSRegister;

/**
 * @var yii\web\View $this
 * @var app\models\ApprovalSebelumPekerjaan $model
 * @var app\components\annex\ActiveForm $form
 */

$progressSelect2 = \app\models\ProyekKemajuan::searchForApproval($model->id_proyek);

?>

<?php $form = ActiveForm::begin([
    'id' => 'ApprovalSebelumPekerjaan',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary alert alert-error'
]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="clearfix"></div>
<div class="d-flex  flex-wrap">

    <?= $form->field($model, 'id_proyek', ['template' => '{input}'])->hiddenInput(); ?>
    <?= $form->field($model, 'foto_material', \app\components\Constant::COLUMN(1))->widget(FileInput::class, [
        // kartik file input configuration
        'options' => [
            'accept' => 'image/*',
            'multiple' => false,
            'disabled' => (isset($relAttributes) && isset($relAttributes['foto_material'])),
        ],
    ]) ?>
    <?= $form->field($model, 'id_progress', \app\components\Constant::COLUMN(1))->widget(\kartik\select2\Select2::classname(), [
        'name' => 'class_name',
        'model' => $model,
        'attribute' => 'id_progress',
        'data' => $progressSelect2,
        'options' => [
            'placeholder' => $model->getAttributeLabel('id_progress'),
            'multiple' => false,
            'disabled' => (isset($relAttributes) && isset($relAttributes['id_progress'])),
        ]
    ]); ?>
    <?= $form->field($model, 'keterangan', \app\components\Constant::COLUMN(1))->textarea(['rows' => 6]) ?>
    <div class="clearfix"></div>
</div>
<hr />
<div class="row">
    <div class="col-md-offset-3 col-md-10">
        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']); ?>
        <?= Html::a('<i class="fa fa-chevron-left"></i> Kembali', ['proyek', 'id' => $model->id_proyek], ['class' => 'btn btn-default']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php JSRegister::begin() ?>
<script>
    let optgroupState = {};

    $("body").on('click', '.select2-container--open .select2-results__group', function() {
        $(this).siblings().toggle();
        let id = $(this).closest('.select2-results__options').attr('id');
        let index = $('.select2-results__group').index(this);
        optgroupState[id][index] = !optgroupState[id][index];
    })

    $('#select-test').on('select2:open', function() {
        $('.select2-dropdown--below').css('opacity', 0);
        setTimeout(() => {
            let groups = $('.select2-container--open .select2-results__group');
            let id = $('.select2-results__options').attr('id');
            if (!optgroupState[id]) {
                optgroupState[id] = {};
            }
            $.each(groups, (index, v) => {
                optgroupState[id][index] = optgroupState[id][index] || false;
                optgroupState[id][index] ? $(v).siblings().show() : $(v).siblings().hide();
            })
            $('.select2-dropdown--below').css('opacity', 1);
        }, 0);
    })
</script>
<?php JSRegister::end() ?>