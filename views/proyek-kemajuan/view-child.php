<?php

use app\components\annex\Modal;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'Proyek Kemajuan : ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Proyek Kemajuan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';


Modal::begin([
    'id' => 'modal',
    'header' => '<h2>History Progress</h2>',
    'size' => 'modal-lg',
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => true]
]);
echo "<div id='modalContent'></div>";
Modal::end();

?>
<div class="giiant-crud proyek-kemajuan-view-child">

    <!-- menu buttons -->
    <?php if ($modelKemajuan->parent != null) : ?>
        <p class='pull-left'>
            <?= Html::a('<span class="glyphicon glyphicon-rollback"></span> ' . 'Kembali', ['view-child', 'id' => $modelKemajuan->id_parent], ['class' => 'btn btn-default mr-1 mt-1']) ?>
        </p>
    <?php endif ?>
    <p class='pull-left'>
        <?= Html::a('<span class="glyphicon glyphicon-rollback"></span> ' . 'Lihat Proyek', ['/proyek/view', 'id' => $model->id], ['class' => 'btn btn-info mr-1 mt-1']) ?>
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
                    <?php Pjax::begin(['id' => 'pjax-ProyekKemajuans', 'enableReplaceState' => false, 'linkSelector' => '#pjax-ProyekKemajuans ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("defrindr")}']]) ?>
                    <?= '<div class="table-responsive">'
                        .                     \yii\grid\GridView::widget([
                            'layout' => '{summary}<br/>{items}{pager}',
                            'dataProvider' => new \yii\data\ActiveDataProvider([
                                'query' => $modelKemajuan->getChildren()->andWhere(['flag' => 1])->orderBy('id_parent ASC'),
                                'pagination' => [
                                    'pageSize' => 20,
                                    'pageParam' => 'page-proyekkemajuans',
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
                                    'template'   => ' {progress} {view-history} {child}',
                                    'header' => 'Aksi',
                                    'contentOptions' => ['nowrap' => 'nowrap'],
                                    'urlCreator' => function ($action, $model, $key, $index) {
                                        // using the column name as key, not mapping to 'id' like the standard generator
                                        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                                        $params[0] = 'proyek-kemajuan' . '/' . $action;
                                        $params['ProyekKemajuan'] = ['id_proyek' => $model->primaryKey()[0]];
                                        return $params;
                                    },
                                    'buttons'    => [
                                        'progress' => function ($url, $model, $key) {
                                            if ($model->getChildren()->andWhere(['flag' => 1])->count() != 0) return "";
                                            $params[0] = 'proyek-kemajuan-harian/create';
                                            $params['ProyekKemajuanHarian'] = ['id_proyek_kemajuan' => $model->id];
                                            return Html::a("<i class='fa fa-line-chart'></i>", $params, ["class" => "mr-1 mb-1 btn btn-secondary", "title" => "Update Progress"]);
                                        },
                                        'view-history' => function ($url, $model, $key) {
                                            if ($model->getChildren()->andWhere(['flag' => 1])->count() != 0) return "";
                                            $params[0] = '/proyek-kemajuan/show-history';
                                            $params['id'] = $model->id;
                                            return Html::button("<i class='fa fa-clock-o'></i>", ["value" => \yii\helpers\Url::to($params), "class" => "mr-1 mb-1 btn btn-warning modalButton", "title" => "Lihat Histori"]);
                                        },
                                        'child' => function ($url, $model, $key) {
                                            if ($model->getChildren()->andWhere(['flag' => 1])->count() == 0) return "";
                                            return Html::a("<i class='fa fa-random'></i>", ["view-child", "id" => $model->id], [
                                                "class" => "mr-1 mb-1 btn btn-primary",
                                                "title" => "Sub Data",
                                            ]);
                                        },
                                    ],
                                    'controller' => 'proyek-kemajuan'
                                ],
                                [
                                    'attribute' => 'item',
                                    'format' => 'ntext',
                                ],
                                // modified by Defri Indra
                                [
                                    'class' => yii\grid\DataColumn::className(),
                                    'attribute' => 'id_satuan',
                                    'value' => function ($model) {
                                        if ($rel = $model->satuan) {
                                            return $rel->nama;
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'raw',
                                ],
                                [
                                    'attribute' => 'volume',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        if ($model->volume === null) return "";
                                        return $model->volume;
                                    },
                                ],
                                [
                                    'attribute' => 'bobot',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        if ($model->bobot === null) return "";
                                        return $model->bobot;
                                    },
                                ],
                                [
                                    'attribute' => 'volume_kemajuan',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        if ($model->volume_kemajuan === null) return "";
                                        return $model->volume_kemajuan;
                                    },
                                ],
                                [
                                    'attribute' => 'bobot_kemajuan',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        if ($model->bobot_kemajuan === null) return "";
                                        return $model->bobot_kemajuan;
                                    },
                                ],
                                [
                                    'class'      => 'yii\grid\ActionColumn',
                                    'template'   => '{update} {delete}',
                                    'header' => 'Aksi',
                                    'contentOptions' => ['nowrap' => 'nowrap'],
                                    'urlCreator' => function ($action, $model, $key, $index) {
                                        // using the column name as key, not mapping to 'id' like the standard generator
                                        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                                        $params[0] = 'proyek-kemajuan' . '/' . $action;
                                        $params['ProyekKemajuan'] = ['id_proyek' => $model->primaryKey()[0]];
                                        return $params;
                                    },
                                    'buttons'    => [
                                        'update' => function ($url, $model, $key) {
                                            return Html::a("<i class='fa fa-pencil'></i>", $url, ["class" => "mr-1 mb-1 btn btn-warning", "title" => "Ubah Data"]);
                                        },
                                        'delete' => function ($url, $model, $key) {
                                            return Html::a("<i class='fa fa-trash'></i>", $url, [
                                                "class" => "mr-1 mb-1 btn btn-danger",
                                                "title" => "Hapus Data",
                                                'method' => 'post',
                                                "data-confirm" => "Apakah Anda yakin ingin menghapus data ini ?",
                                            ]);
                                        },
                                    ],
                                    'controller' => 'proyek-kemajuan'
                                ],
                            ]
                        ])
                        . '</div>' ?>

                    <?php Pjax::end() ?>
                </div>
            </div>
        </div>
    </div>
</div>