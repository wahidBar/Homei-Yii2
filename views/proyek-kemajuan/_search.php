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
* @var app\models\search\ProyekKemajuanSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="proyek-kemajuan-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'id_proyek') ?>

		<?= $form->field($model, 'id_satuan') ?>

		<?= $form->field($model, 'item') ?>

		<?= $form->field($model, 'volume') ?>

		<?php // echo $form->field($model, 'bobot') ?>

		<?php // echo $form->field($model, 'volume_kemajuan') ?>

		<?php // echo $form->field($model, 'bobot_kemajuan') ?>

		<?php // echo $form->field($model, 'status_verifikasi') ?>

		<?php // echo $form->field($model, 'created_at') ?>

		<?php // echo $form->field($model, 'updated_at') ?>

		<?php // echo $form->field($model, 'deleted_at') ?>

		<?php // echo $form->field($model, 'created_by') ?>

		<?php // echo $form->field($model, 'updated_by') ?>

		<?php // echo $form->field($model, 'deleted_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
