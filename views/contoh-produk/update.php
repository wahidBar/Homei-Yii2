<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */
 
use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var app\models\ContohProduk $model
*/

$this->title = 'Contoh Produk ' . $model->id . ', ' . 'Edit';
$this->params['breadcrumbs'][] = ['label' => 'Contoh Produk', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<?= $this->render('../layouts/navigation-setting') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card m-b-30">
            <div class="card-body">
                <!-- <p>
                    <?= Html::a('Kembali', \yii\helpers\Url::previous(), ['class' => 'btn btn-default']) ?>
                </p> -->
                <?php echo $this->render('_form', $model->render()); ?>
            </div>
        </div>
    </div>
</div>
