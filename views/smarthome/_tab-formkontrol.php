<?php

use app\components\annex\ActiveForm;
use app\components\Constant;
use app\components\ConstantHomeis;
use richardfan\widget\JSRegister;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

?>

<div class="alert alert-warning">
    <strong>Perhatian!</strong>
    <p>
        Anda hanya dapat menambahkan 6 kontrol ke setiap sirkuit.
    </p>
</div>

<?php $form = ActiveForm::begin([
    'id' => 'Smarthome-ubahdetail',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary alert alert-error'
]);
?>

<?php echo $form->errorSummary($modelKontrol); ?>

<div class="clearfix"></div>
<div class="d-flex  flex-wrap">
    <?= $form->field($modelKontrol, 'id_sirkuit', \app\components\Constant::COLUMN(1))->dropDownList(ArrayHelper::map($model->getSmarthomeSirkuits()->active()->all(), 'id', 'nama'), [
        'prompt' => 'Pilih Sirkuit',
        'class' => 'form-control select2',
    ]) ?>
    <?= $form->field($modelKontrol, 'ikon', \app\components\Constant::COLUMN(1))->textInput(['maxlength' => true, 'class' => 'form-control icp-auto']) ?>
    <?= $form->field($modelKontrol, 'nama', \app\components\Constant::COLUMN(1))->textInput(['maxlength' => true]) ?>
    <?= $form->field($modelKontrol, 'pin', \app\components\Constant::COLUMN(1))->dropDownList($dropdownPin, ['prompt' => '-- Pilih PIN --']) ?>
    <div class="clearfix"></div>
</div>
<hr />
<div class="row">
    <div class="col-md-offset-3 col-md-10">
        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
JSRegister::begin([
    'key' => 'form-kontrol',
    'position' => \yii\web\View::POS_READY
]);
?>
<script>
    $(".icp-auto").iconpicker();

    function updateSelectPin(element) {
        var id_sirkuit = $(element).val();
        // get _detail from url parameter
        var _detail = "<?= Yii::$app->request->get('_detail') ?>";
        $.ajax({
            url: "<?= \yii\helpers\Url::to(['smarthome/cek-pin', 'id' => $model->id]) ?>",
            type: "POST",
            data: {
                id: id_sirkuit,
                selected: _detail
            },
            success: function(response) {
                var data = response;
                if (data.success) {
                    var pin = data.data;
                    var html = "";
                    html += "<option value=''>-- Pilih PIN --</option>";
                    for (var i = 0; i < pin.length; i++) {
                        // if has selected
                        if (pin[i].selected) {
                            html += "<option value='" + pin[i].id + "' selected>" + pin[i].text + "</option>";
                            continue;
                        }
                        html += "<option value='" + pin[i].id + "'>" + pin[i].text + "</option>";
                    }
                    $("#smarthomekontrol-pin").html(html);
                }
            }
        });
    }

    $("#smarthomekontrol-id_sirkuit").on("change", function() {
        updateSelectPin(this);
    });

    // on ready update select pin
    updateSelectPin($("#smarthomekontrol-id_sirkuit"));
</script>
<?php JSRegister::end(); ?>