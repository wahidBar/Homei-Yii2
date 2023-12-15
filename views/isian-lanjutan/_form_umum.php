<?php

use kartik\select2\Select2;
?>
<?= $form->field($model, 'id_user', \app\components\Constant::COLUMN(1))->widget(Select2::class,  [
    'name' => 'class_name',
    'model' => $model,
    'attribute' => 'id_user',
    'data' => \yii\helpers\ArrayHelper::map(app\models\User::find()->all(), 'id', 'name'),
    'options' => [
        'placeholder' => $model->getAttributeLabel('id_user'),
        'multiple' => false,
        'disabled' => (isset($relAttributes) && isset($relAttributes['id_user'])),
    ]
]) ?>
<?= $form->field($model, 'nama_awal', \app\components\Constant::COLUMN(2))->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'nama_akhir', \app\components\Constant::COLUMN(2))->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'no_hp', \app\components\Constant::COLUMN(1))->textInput(['maxlength' => true, 'value' => $model->no_hp ?? "62"]) ?>
