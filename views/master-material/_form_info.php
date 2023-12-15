<div class="clearfix"></div>
<div class="d-flex  flex-wrap">

    <?= $form->field($model, 'nama', \app\components\Constant::COLUMN())->textInput(['maxlength' => true]) ?>
    <?=         // modified by Defri Indra
    $form->field($model, 'id_satuan', \app\components\Constant::COLUMN())->widget(\kartik\select2\Select2::classname(), [
        'name' => 'class_name',
        'model' => $model,
        'attribute' => 'id_satuan',
        'data' => \yii\helpers\ArrayHelper::map(app\models\MasterSatuan::find()->where(['flag' => 1])->all(), 'id', 'nama'),
        'options' => [
            'placeholder' => $model->getAttributeLabel('id_satuan'),
            'multiple' => false,
            'disabled' => (isset($relAttributes) && isset($relAttributes['id_satuan'])),
        ]
    ]); ?>
    <?= $form->field($model, 'deskripsi', \app\components\Constant::COLUMN(1))->textarea(['rows' => 6]) ?>
    <div class="clearfix"></div>
</div>