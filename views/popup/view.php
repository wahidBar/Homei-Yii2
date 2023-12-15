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
 * @var app\models\Popup $model
 */

$this->title = 'Popup : ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Popup', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';
?>
<div class="giiant-crud popup-view">

    <!-- menu buttons -->
    <p class='pull-left'>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . 'Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'Tambah Baru', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <p class="pull-right">
        <?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . 'Daftar Popup', ['index'], ['class' => 'btn btn-default']) ?>
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
                    <?php $this->beginBlock('app\models\Popup'); ?>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'attribute' => 'image',
                                'format' => 'myImage',
                            ],
                            [
                                'attribute' => 'web_show',
                                'format' => 'text',
                                'value' => function($model) {
                                    return $model->getWebShowLabel();
                                }
                            ],
                            [
                                'attribute' => 'android_show',
                                'format' => 'text',
                                'value' => function($model) {
                                    return $model->getAndroidShowLabel();
                                }
                            ],
                            [
                                'attribute' => 'android_redirect_type',
                                'format' => 'text',
                                'value' => function($model) {
                                    return $model->getAndroidRedirectTypeLabel();
                                }
                            ],
                            [
                                'attribute' => 'android_route',
                                'format' => 'text',
                                'value' => function($model) {
                                    return $model->android_route;
                                }
                            ],
                            [
                                'attribute' => 'android_params',
                                'format' => 'text',
                                'value' => function($model) {
                                    return $model->android_params;
                                }
                            ],
                            [
                                'attribute' => 'web_link',
                                'format' => 'text',
                                'value' => function($model) {
                                    return $model->web_link;
                                }
                            ],
                        ],
                    ]); ?>

                    <hr />

                    <?= Html::a(
                        '<span class="glyphicon glyphicon-trash"></span> ' . 'Delete',
                        ['delete', 'id' => $model->id],
                        [
                            'class' => 'btn btn-danger',
                            'data-confirm' => '' . 'Are you sure to delete this item?' . '',
                            'data-method' => 'post',
                        ]
                    ); ?>
                    <?php $this->endBlock(); ?>



                    <?= Tabs::widget(
                        [
                            'id' => 'relation-tabs',
                            'encodeLabels' => false,
                            'items' => [
                                [
                                    'label'   => '<b class=""># ' . $model->id . '</b>',
                                    'content' => $this->blocks['app\models\Popup'],
                                    'active'  => true,
                                ],
                            ]
                        ]
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>