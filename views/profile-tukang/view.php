<?php
/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use dmstr\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use app\components\annex\Tabs;

/**
* @var yii\web\View $this
* @var app\models\ProfileTukang $model
*/

$this->title = 'Profile Tukang : ' . $model->nama;
$this->params['breadcrumbs'][] = ['label' => 'Profile Tukang', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->nama, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';
?>
<div class="giiant-crud profile-tukang-view">

    <!-- menu buttons -->
    <p class='pull-left'>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . 'Edit', ['update', 'id' => $model->id],['class' => 'btn btn-info']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'Tambah Baru', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <p class="pull-right">
        <?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . 'Daftar Profile Tukang', ['index'], ['class'=>'btn btn-default']) ?>
    </p>

    <div class="clearfix"></div>

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?= \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <?php $this->beginBlock('app\models\ProfileTukang'); ?>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
					                    // modified by Defri Indra
                    [
                        'format' => 'html',
                        'attribute' => 'id_user',
                        'value' => ($model->user ? $model->user->name : '<span class="label label-warning">?</span>'),
                    ],
					                    // modified by Defri Indra
                    [
                        'format' => 'html',
                        'attribute' => 'id_layanan',
                        'value' => ($model->layanan ? $model->layanan->id : '<span class="label label-warning">?</span>'),
                    ],
					        [
            'attribute' => 'nama',
            'format' => 'text',
        ],
					        [
            'attribute' => 'foto_ktp',
            'format' => 'myImage',
        ],
					        [
            'attribute' => 'keahlian',
            'format' => 'text',
        ],
					        [
            'attribute' => 'alamat',
            'format' => 'ntext',
        ],
					        [
            'attribute' => 'flag',
            'format' => 'boolean',
        ],
                        ],
                    ]); ?>

                    <hr/>

                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ' . 'Delete', ['delete', 'id' => $model->id],
                    [
                    'class' => 'btn btn-danger',
                    'data-confirm' => '' . 'Are you sure to delete this item?' . '',
                    'data-method' => 'post',
                    ]); ?>
                    <?php $this->endBlock(); ?>


                    
                    <?= Tabs::widget(
                    [
                        'id' => 'relation-tabs',
                        'encodeLabels' => false,
                        'items' => [ 
                                                [
                        'label'   => '<b class=""># '.$model->id.'</b>',
                        'content' => $this->blocks['app\models\ProfileTukang'],
                        'active'  => true,
                    ],
                        ]
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
