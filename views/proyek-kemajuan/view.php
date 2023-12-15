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
 * @var app\models\ProyekKemajuan $model
 */

$this->title = 'Proyek Kemajuan : ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Proyek Kemajuan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';
?>
<div class="giiant-crud proyek-kemajuan-view">

    <!-- menu buttons -->
    <p class='pull-left'>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . 'Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'Tambah Baru', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <p class="pull-right">
        <?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . 'Daftar Proyek Kemajuan', ['index'], ['class' => 'btn btn-default']) ?>
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
                    <?php $this->beginBlock('app\models\ProyekKemajuan'); ?>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            // modified by Defri Indra
                            [
                                'format' => 'html',
                                'attribute' => 'id_proyek',
                                'value' => ($model->proyek ? $model->proyek->id : '<span class="label label-warning">?</span>'),
                            ],
                            // modified by Defri Indra
                            [
                                'format' => 'html',
                                'attribute' => 'id_satuan',
                                'value' => ($model->satuan ? $model->satuan->nama : '<span class="label label-warning">?</span>'),
                            ],
                            [
                                'attribute' => 'item',
                                'format' => 'ntext',
                            ],
                            [
                                'attribute' => 'volume',
                                'format' => 'text',
                            ],
                            [
                                'attribute' => 'bobot',
                                'format' => 'text',
                            ],
                            [
                                'attribute' => 'volume_kemajuan',
                                'format' => 'text',
                            ],
                            [
                                'attribute' => 'bobot_kemajuan',
                                'format' => 'text',
                            ],
                            [
                                'attribute' => 'status_verifikasi',
                                'format' => 'text',
                            ],
                            [
                                'attribute' => 'created_at',
                                'format' => 'iddate',
                            ],
                            [
                                'attribute' => 'updated_at',
                                'format' => 'iddate',
                            ],
                            [
                                'attribute' => 'deleted_at',
                                'format' => 'iddate',
                            ],
                            // modified by Defri Indra
                            [
                                'format' => 'html',
                                'attribute' => 'created_by',
                                'value' => ($model->createdBy ? $model->createdBy->name : '<span class="label label-warning">?</span>'),
                            ],
                            // modified by Defri Indra
                            [
                                'format' => 'html',
                                'attribute' => 'updated_by',
                                'value' => ($model->updatedBy ? $model->updatedBy->name : '<span class="label label-warning">?</span>'),
                            ],
                            // modified by Defri Indra
                            [
                                'format' => 'html',
                                'attribute' => 'deleted_by',
                                'value' => ($model->deletedBy ? $model->deletedBy->name : '<span class="label label-warning">?</span>'),
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



                    <?php $this->beginBlock('ProyekGaleris'); ?>
                    <div style='position: relative'>
                        <div style='position:absolute; right: 0px; top: 0px;'>
                            <?= Html::a(
                                '<span class="glyphicon glyphicon-list"></span> ' . 'Semua Data' . ' Proyek Galeris',
                                ['proyek-galeri/index'],
                                ['class' => 'btn text-muted btn-xs']
                            ) ?>
                            <?= Html::a(
                                '<span class="glyphicon glyphicon-plus"></span> ' . 'Tambah Baru' . ' Proyek Galeri',
                                ['proyek-galeri/create', 'ProyekGaleri' => ['Array' => $model->id]],
                                ['class' => 'btn btn-success btn-xs']
                            ); ?>
                        </div>
                    </div><?php Pjax::begin(['id' => 'pjax-ProyekGaleris', 'enableReplaceState' => false, 'linkSelector' => '#pjax-ProyekGaleris ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("defrindr")}']]) ?>
                    <?= '<div class="table-responsive">'
                        .                     \yii\grid\GridView::widget([
                            'layout' => '{summary}{pager}<br/>{items}{pager}',
                            'dataProvider' => new \yii\data\ActiveDataProvider([
                                'query' => $model->getProyekGaleris(),
                                'pagination' => [
                                    'pageSize' => 20,
                                    'pageParam' => 'page-proyekgaleris',
                                ]
                            ]),
                            'pager'        => [
                                'class'          => \app\components\annex\LinkPager::className(),
                                'firstPageLabel' => 'First',
                                'lastPageLabel'  => 'Last'
                            ],
                            'columns' => [
                                [
                                    'class'      => 'yii\grid\ActionColumn',
                                    'template'   => '{view} {update}',
                                    'contentOptions' => ['nowrap' => 'nowrap'],
                                    'urlCreator' => function ($action, $model, $key, $index) {
                                        // using the column name as key, not mapping to 'id' like the standard generator
                                        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                                        $params[0] = 'proyek-galeri' . '/' . $action;
                                        $params['ProyekGaleri'] = ['id_proyek_kemajuan' => $model->primaryKey()[0]];
                                        return $params;
                                    },
                                    'buttons'    => [],
                                    'controller' => 'proyek-galeri'
                                ],
                                // modified by Defri Indra
                                [
                                    'class' => yii\grid\DataColumn::className(),
                                    'attribute' => 'id_proyek',
                                    'value' => function ($model) {
                                        if ($rel = $model->proyek) {
                                            return $rel->id;
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'raw',
                                ],
                                [
                                    'attribute' => 'nama_file',
                                    'format' => 'text',
                                ],
                                [
                                    'attribute' => 'keterangan',
                                    'format' => 'ntext',
                                ],
                                [
                                    'attribute' => 'created_at',
                                    'format' => 'iddate',
                                ],
                                // modified by Defri Indra
                                [
                                    'class' => yii\grid\DataColumn::className(),
                                    'attribute' => 'created_by',
                                    'value' => function ($model) {
                                        if ($rel = $model->createdBy) {
                                            return $rel->name;
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'raw',
                                ],
                                [
                                    'attribute' => 'deleted_at',
                                    'format' => 'iddate',
                                ],
                                // modified by Defri Indra
                                [
                                    'class' => yii\grid\DataColumn::className(),
                                    'attribute' => 'deleted_by',
                                    'value' => function ($model) {
                                        if ($rel = $model->deletedBy) {
                                            return $rel->name;
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'raw',
                                ],
                            ]
                        ])
                        . '</div>' ?>
                    <?php Pjax::end() ?>
                    <?php $this->endBlock() ?>


                    <?php $this->beginBlock('ProyekKemajuanHarians'); ?>
                    <div style='position: relative'>
                        <div style='position:absolute; right: 0px; top: 0px;'>
                            <?= Html::a(
                                '<span class="glyphicon glyphicon-list"></span> ' . 'Semua Data' . ' Proyek Kemajuan Harians',
                                ['proyek-kemajuan-harian/index'],
                                ['class' => 'btn text-muted btn-xs']
                            ) ?>
                            <?= Html::a(
                                '<span class="glyphicon glyphicon-plus"></span> ' . 'Tambah Baru' . ' Proyek Kemajuan Harian',
                                ['proyek-kemajuan-harian/create', 'ProyekKemajuanHarian' => ['Array' => $model->id]],
                                ['class' => 'btn btn-success btn-xs']
                            ); ?>
                        </div>
                    </div><?php Pjax::begin(['id' => 'pjax-ProyekKemajuanHarians', 'enableReplaceState' => false, 'linkSelector' => '#pjax-ProyekKemajuanHarians ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("defrindr")}']]) ?>
                    <?= '<div class="table-responsive">'
                        .                     \yii\grid\GridView::widget([
                            'layout' => '{summary}{pager}<br/>{items}{pager}',
                            'dataProvider' => new \yii\data\ActiveDataProvider([
                                'query' => $model->getProyekKemajuanHarians(),
                                'pagination' => [
                                    'pageSize' => 20,
                                    'pageParam' => 'page-proyekkemajuanharians',
                                ]
                            ]),
                            'pager'        => [
                                'class'          => \app\components\annex\LinkPager::className(),
                                'firstPageLabel' => 'First',
                                'lastPageLabel'  => 'Last'
                            ],
                            'columns' => [
                                [
                                    'class'      => 'yii\grid\ActionColumn',
                                    'template'   => '{view} {update}',
                                    'contentOptions' => ['nowrap' => 'nowrap'],
                                    'urlCreator' => function ($action, $model, $key, $index) {
                                        // using the column name as key, not mapping to 'id' like the standard generator
                                        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                                        $params[0] = 'proyek-kemajuan-harian' . '/' . $action;
                                        $params['ProyekKemajuanHarian'] = ['id_proyek_kemajuan' => $model->primaryKey()[0]];
                                        return $params;
                                    },
                                    'buttons'    => [],
                                    'controller' => 'proyek-kemajuan-harian'
                                ],
                                [
                                    'attribute' => 'tanggal',
                                    'format' => 'iddate',
                                ],
                                [
                                    'attribute' => 'volume',
                                    'format' => 'text',
                                ],
                                [
                                    'attribute' => 'bobot',
                                    'format' => 'text',
                                ],
                                [
                                    'attribute' => 'created_at',
                                    'format' => 'iddate',
                                ],
                                [
                                    'attribute' => 'updated_at',
                                    'format' => 'iddate',
                                ],
                                [
                                    'attribute' => 'deleted_at',
                                    'format' => 'iddate',
                                ],
                                // modified by Defri Indra
                                [
                                    'class' => yii\grid\DataColumn::className(),
                                    'attribute' => 'created_by',
                                    'value' => function ($model) {
                                        if ($rel = $model->createdBy) {
                                            return $rel->name;
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'raw',
                                ],
                                // modified by Defri Indra
                                [
                                    'class' => yii\grid\DataColumn::className(),
                                    'attribute' => 'updated_by',
                                    'value' => function ($model) {
                                        if ($rel = $model->updatedBy) {
                                            return $rel->name;
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'raw',
                                ],
                                // modified by Defri Indra
                                [
                                    'class' => yii\grid\DataColumn::className(),
                                    'attribute' => 'deleted_by',
                                    'value' => function ($model) {
                                        if ($rel = $model->deletedBy) {
                                            return $rel->name;
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'raw',
                                ],
                            ]
                        ])
                        . '</div>' ?>
                    <?php Pjax::end() ?>
                    <?php $this->endBlock() ?>


                    <?= Tabs::widget(
                        [
                            'id' => 'relation-tabs',
                            'encodeLabels' => false,
                            'items' => [
                                [
                                    'label'   => '<b class=""># ' . $model->id . '</b>',
                                    'content' => $this->blocks['app\models\ProyekKemajuan'],
                                    'active'  => true,
                                ],                        [
                                    'content' => $this->blocks['ProyekGaleris'],
                                    'label'   => '<small>Proyek Galeris <span class="badge badge-default">' . count($model->getProyekGaleris()->asArray()->all()) . '</span></small>',
                                    'active'  => false,
                                ],                        [
                                    'content' => $this->blocks['ProyekKemajuanHarians'],
                                    'label'   => '<small>Proyek Kemajuan Harians <span class="badge badge-default">' . count($model->getProyekKemajuanHarians()->asArray()->all()) . '</span></small>',
                                    'active'  => false,
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