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
 * @var app\models\Page $model
 * @var app\components\annex\ActiveForm $form
 */

$this->registerJsFile(Yii::getAlias("@link/tinymce/tinymce.min.js"));
// register class tinymce
$js = <<<JS
    tinymce.init({
        selector: 'textarea',
        height: 500,
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools'
        ],
        toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        toolbar2: 'print preview media | forecolor backcolor emoticons',
        image_advtab: true
    });
JS;
$this->registerJs($js);

?>

<?php $form = ActiveForm::begin([
    'id' => 'Page',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary alert alert-error'
]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="clearfix"></div>
<div class="d-flex  flex-wrap">
    <?= $form->field($model, 'thumbnail', \app\components\Constant::COLUMN(1))->widget(FileInput::class, [
        "options" => [
            "accept" => "image/*",
            "multiple" => false,
        ],
    ]) ?>
    <?= $form->field($model, 'title', \app\components\Constant::COLUMN(1))->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'pages', \app\components\Constant::COLUMN(1))->textarea(['rows' => 6]) ?>
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