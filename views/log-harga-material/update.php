<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */
 
use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var app\models\LogHargaMaterial $model
*/

$this->title = 'Log Harga Material ' . $model->id . ', ' . 'Edit';
$this->params['breadcrumbs'][] = ['label' => 'Log Harga Material', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>

<div class="row">
    <div class="col-md-12">
        <!-- <p>
            <?= Html::a('Kembali', \yii\helpers\Url::previous(), ['class' => 'btn btn-default']) ?>
        </p> -->
        <div class="card m-b-30">
            <div class="card-body">
                <?php echo $this->render('_form', $model->render()); ?>
            </div>
        </div>
    </div>
</div>
