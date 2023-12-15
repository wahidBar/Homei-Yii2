<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use yii\helpers\Html;
use app\components\annex\ActiveForm;
use \app\components\annex\Tabs;
use richardfan\widget\JSRegister;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var app\models\ProyekKemajuan $model
 * @var app\components\annex\ActiveForm $form
 */

?>

<?php $form = ActiveForm::begin([
    'action' => \yii\helpers\Url::current(["id_project" => $model->id_proyek]),
    'id' => 'ProyekKemajuan',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary alert alert-error'
]);
?>
<?php echo $form->errorSummary($model); ?>
<?php if (isset($model->proyek)) : ?>
    <div class="p-2 alert-primary alert alert-dismissible fade show mb-4">
        Nilai Proyek adalah <?= \app\components\Angka::toReadableHarga($model->proyek->nilai_kontrak, false) ?>, biaya telah di gunakan untuk progress senilai <?= \app\components\Angka::toReadableHarga($model->proyek->getSisaBiayaProgress(), false) ?>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    </div>
    <div class="p-2 alert-warning alert alert-dismissible fade show mb-4">
        Bobot progress di inputkan telah mencapai <?= $model->proyek->getTotalBobot() ?> %
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    </div>
<?php endif ?>
<div class="clearfix"></div>
<div class="d-flex  flex-wrap">

    <?= $form->field($model, 'id_proyek', ['template' => '{input}'])->hiddenInput()->label(false); ?>
    <?= $form->field($model, 'id_parent', \app\components\Constant::COLUMN(1))->widget(\kartik\select2\Select2::classname(), [
        'model' => $model,
        'data' => \yii\helpers\ArrayHelper::map(app\models\ProyekKemajuan::find()
            ->where([
                'id' => $model->id_parent,
                'flag' => 1
            ])->all(), 'id', 'item'),
        'options' => [
            'placeholder' => $model->getAttributeLabel('id_parent'),
            'multiple' => false,
            'disabled' => (isset($relAttributes) && isset($relAttributes['id_satuan'])),
        ],
        "pluginOptions" => [
            'minimumInputLength' => 3,
            "allowClear" => true,
            'language' => [
                'errorLoading' => new \yii\web\JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => Url::to(['/proyek-kemajuan/get-parent']),
                'dataType' => 'json',
                'data' => new \yii\web\JsExpression('function(params) { 
                    let id = $("#proyekkemajuan-id_proyek").val();
                    return {q:params.term, id: id}; 
                }')
            ],
            'escapeMarkup' => new \yii\web\JsExpression('function (markup) { return markup; }'),
            'templateResult' => new \yii\web\JsExpression('function(city) { return city.text; }'),
            'templateSelection' => new \yii\web\JsExpression('function (city) { return city.text; }'),
        ]
    ]); ?>
    <?= $form->field($model, 'item', \app\components\Constant::COLUMN(1))->textarea(['rows' => 1]) ?>

    <?= $form->field($model, 'nilai_biaya', \app\components\Constant::COLUMN(1))->widget(\yii\widgets\MaskedInput::class, [
        'clientOptions' => [
            'alias' =>  'decimal',
            'groupSeparator' => ',',
            'autoGroup' => true
        ],
    ]) ?>

    <?= $form->field($model, 'volume', \app\components\Constant::COLUMN(3))->textInput() ?>
    <?= $form->field($model, 'bobot', \app\components\Constant::COLUMN(3))->textInput() ?>
    <?= $form->field($model, 'id_satuan', \app\components\Constant::COLUMN(3))->widget(\kartik\select2\Select2::classname(), [
        'name' => 'class_name',
        'model' => $model,
        'attribute' => 'id_satuan',
        'data' => \yii\helpers\ArrayHelper::map(app\models\MasterSatuan::find()->all(), 'id', 'nama'),
        'options' => [
            'placeholder' => $model->getAttributeLabel('id_satuan'),
            'multiple' => false,
            'disabled' => (isset($relAttributes) && isset($relAttributes['id_satuan'])),
        ]
    ]); ?>
    <?= $form->field($model, 'status_verifikasi', \app\components\Constant::COLUMN(1))->dropDownList(
        \app\models\ProyekKemajuan::getStatuses(),
        [
            'prompt' => 'Pilih Status',
        ]
    ) ?>
    <?php //$form->field($model, 'volume_kemajuan', \app\components\Constant::COLUMN())->textInput() 
    ?>
    <?php // $form->field($model, 'bobot_kemajuan', \app\components\Constant::COLUMN())->textInput() 
    ?>
    <div class="clearfix"></div>
</div>
<hr />
<div class="row">
    <div class="col-md-offset-3 col-md-10">
        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']); ?>
        <?= Html::a('<i class="fa fa-chevron-left"></i> Kembali', ['/proyek/view', 'id' => $model->id_proyek], ['class' => 'btn btn-default']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>


<?php JSRegister::begin() ?>
<script>
    $('#proyekkemajuan-nilai_biaya').on('keydown', (event) => {
        let nilai_kontrak = <?= isset($model->proyek) ? $model->proyek->nilai_kontrak : 0 ?>;
        let nilai_inputan = $('#proyekkemajuan-nilai_biaya').val().replaceAll(',', '');
        if (nilai_kontrak != 0) {
            let hasil = (nilai_inputan / nilai_kontrak) * 100;
            $('#proyekkemajuan-bobot').val(hasil)
        }
    });
    $('#proyekkemajuan-nilai_biaya').on('keyup', (event) => {
        let nilai_kontrak = <?= isset($model->proyek) ? $model->proyek->nilai_kontrak : 0 ?>;
        let nilai_inputan = $('#proyekkemajuan-nilai_biaya').val().replaceAll(',', '');
        if (nilai_kontrak != 0) {
            let hasil = (nilai_inputan / nilai_kontrak) * 100;
            $('#proyekkemajuan-bobot').val(hasil)
        }
    });
</script>
<?php JSRegister::end() ?>