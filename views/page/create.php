<?php
/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var app\models\Page $model
*/

$this->title = 'Tambah Baru';
$this->params['breadcrumbs'][] = ['label' => 'Page', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <p>
            <?= Html::a('Kembali', \yii\helpers\Url::previous(), ['class' => 'btn btn-default']) ?>
        </p>
        <div class="card m-b-30">
            <div class="card-body">
                <?= $this->render('_form', $model->render()); ?>
            </div>
        </div>
    </div>
</div>
