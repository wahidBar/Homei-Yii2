<?php
/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
* @var yii\web\View $this
* @var app\models\search\ProyekKeuanganKeluarSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="proyek-keuangan-keluar-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'id_proyek') ?>

		<?= $form->field($model, 'no_po') ?>

		<?= $form->field($model, 'dokumen_po') ?>

		<?= $form->field($model, 'no_invoice') ?>

		<?php // echo $form->field($model, 'keterangan') ?>

		<?php // echo $form->field($model, 'tanggal') ?>

		<?php // echo $form->field($model, 'total_jumlah') ?>

		<?php // echo $form->field($model, 'vendor') ?>

		<?php // echo $form->field($model, 'tipe') ?>

		<?php // echo $form->field($model, 'status') ?>

		<?php // echo $form->field($model, 'created_at') ?>

		<?php // echo $form->field($model, 'created_by') ?>

		<?php // echo $form->field($model, 'updated_at') ?>

		<?php // echo $form->field($model, 'updated_by') ?>

		<?php // echo $form->field($model, 'deleted_at') ?>

		<?php // echo $form->field($model, 'deleted_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
