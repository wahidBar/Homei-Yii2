<div class="clearfix"></div>
<div class="d-flex  flex-wrap">

	<?= $form->field($model, 'nama_kontraktor', \app\components\Constant::COLUMN())->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'telepon', \app\components\Constant::COLUMN())->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'alamat', \app\components\Constant::COLUMN(1))->textarea(['rows' => 6]) ?>
	<div class="clearfix"></div>
</div>