<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use yii\helpers\Html;
use app\components\annex\ActiveForm;
use \app\components\annex\Tabs;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var app\models\ProyekAnggota $model
 * @var app\components\annex\ActiveForm $form
 */

?>

<?php $form = ActiveForm::begin([
    'action' => \yii\helpers\Url::current(["id_project" => $model->id_proyek]),
    'id' => 'ProyekAnggota',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'errorSummaryCssClass' => 'error-summary alert alert-error'
]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="clearfix"></div>
<div class="d-flex  flex-wrap">
    <?= $form->field($model, 'id_proyek', ['template' => '{input}'])->hiddenInput()->label(false); ?>

    <?= $form->field($model, 'id_user', \app\components\Constant::COLUMN(1))->widget(\kartik\select2\Select2::classname(), [
        'model' => $model,
        'data' => \yii\helpers\ArrayHelper::map(app\models\User::find()
            ->where([
                'id' => $model->id_user,
                'flag' => 1
            ])->all(), 'id', 'name'),
        'options' => [
            'placeholder' => $model->getAttributeLabel('id_user'),
            'multiple' => false,
        ],
        "pluginOptions" => [
            'minimumInputLength' => 3,
            "allowClear" => true,
            'language' => [
                'errorLoading' => new \yii\web\JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => Url::to(['/user/get-user']),
                'dataType' => 'json',
                'data' => new \yii\web\JsExpression('function(params) { 
                    let id = $("#proyekanggota-id_user").val();
                    return {q:params.term, id: id}; 
                }')
            ],
            'escapeMarkup' => new \yii\web\JsExpression('function (markup) { return markup; }'),
            'templateResult' => new \yii\web\JsExpression('function(city) { return city.text; }'),
            'templateSelection' => new \yii\web\JsExpression('function (city) { return city.text; }'),
        ]
    ]); ?>
    <?= $form->field($model, 'id_role', \app\components\Constant::COLUMN(1))->widget(\kartik\select2\Select2::classname(), [
        'name' => 'class_name',
        'model' => $model,
        'attribute' => 'id_role',
        'data' => \yii\helpers\ArrayHelper::map(app\models\Role::find()->where(['role_project' => 1])->all(), 'id', 'name'),
        'options' => [
            'placeholder' => $model->getAttributeLabel('id_role'),
            'multiple' => false,
            'disabled' => (isset($relAttributes) && isset($relAttributes['id_role'])),
        ]
    ]); ?>
    <?= $form->field($model, 'keterangan', \app\components\Constant::COLUMN(1))->textarea(['rows' => 6]) ?>
    <div class="clearfix"></div>
</div>
<hr />
<div class="row">
    <div class="col-md-offset-3 col-md-10">
        <?= Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']); ?>
        <?= Html::a('<i class="fa fa-chevron-left"></i> Kembali', ['proyek/view', 'id' => $model->id_proyek], ['class' => 'btn btn-default']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>