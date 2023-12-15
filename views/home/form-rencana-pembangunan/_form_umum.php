<?php

use kartik\select2\Select2;
?>
<?= $form->field($model, 'label', \app\components\Constant::COLUMN(1))->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'nama_awal', \app\components\Constant::COLUMN(2))->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'nama_akhir', \app\components\Constant::COLUMN(2))->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'no_hp', \app\components\Constant::COLUMN(1))->textInput(['maxlength' => true, 'value' => \Yii::$app->user->identity->no_hp]) ?>
