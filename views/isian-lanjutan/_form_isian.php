<?= $form->field($model, 'label', \app\components\Constant::COLUMN(1))->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'budget', \app\components\Constant::COLUMN(3))->widget(\yii\widgets\MaskedInput::class, [
    'clientOptions' => [
        'alias' =>  'decimal',
        'groupSeparator' => ',',
        'autoGroup' => true
    ],
]) ?>
<?= $form->field($model, 'panjang', \app\components\Constant::COLUMN(3))->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'lebar', \app\components\Constant::COLUMN(3))->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'keterangan', \app\components\Constant::COLUMN(1))->textarea(['rows' => 6]) ?>