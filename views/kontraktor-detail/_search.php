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
* @var app\models\search\KontraktorDetailSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="kontraktor-detail-search">

    <?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    ]); ?>

    		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'id_kontraktor') ?>

		<?= $form->field($model, 'id_user') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
