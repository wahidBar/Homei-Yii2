<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\ApprovalSebelumPekerjaanSearch $searchModel
 */

$this->title = 'Approval Sebelum Pekerjaan';
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= Html::a('<i class="fa fa-plus"></i> Tambah Baru', [
        'approval-sebelum-pekerjaan/create',
        'id_project' => $model->id,
        'ApprovalSebelumPekerjaan' => ['id_proyek' => $model->id],
    ], ['class' => 'btn btn-success']) ?>
</p>


<?php \yii\widgets\Pjax::begin(['id' => 'pjax-main', 'enableReplaceState' => false, 'linkSelector' => '#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("yo")}']]) ?>

<div class="table-responsive">
    <?= GridView::widget([
        'layout' => '{summary}{pager}{items}{pager}',
        'dataProvider' =>  new \yii\data\ActiveDataProvider([
            'query' => $model->getApprovalSebelumPekerjaans(),
            'pagination' => [
                'pageSize' => 10,
                'pageParam' => 'page-approvalSebelumPekerjaan',
            ]
        ]),
        'pager'        => [
            'class'          => app\components\annex\LinkPager::className(),
            'firstPageLabel' => 'First',
            'lastPageLabel'  => 'Last'
        ],
        'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
        'headerRowOptions' => ['class' => 'x'],
        'columns' => [


            [
                'class'      => 'yii\grid\ActionColumn',
                'template'   => '{view} {update} {delete} {dilakukan-revisi}',
                'contentOptions' => ['nowrap' => 'nowrap'],
                'urlCreator' => function ($action, $model, $key, $index) {
                    // using the column name as key, not mapping to 'id' like the standard generator
                    $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                    $params[0] = 'approval-sebelum-pekerjaan' . '/' . $action;
                    $params['ApprovalSebelumPekerjaan'] = ['id_proyek' => $model->id];

                    $params['id_project'] = $model->id;
                    return $params;
                },
                'buttons'    => [
                    'view' => function ($url, $model, $key) {
                        return Html::a("<i class='fa fa-eye'></i>", $url, ["class" => "mr-1 mb-1 btn btn-primary", "title" => "Lihat Data"]);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a("<i class='fa fa-pencil'></i>", $url, ["class" => "mr-1 mb-1 btn btn-warning", "title" => "Edit Data"]);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a("<i class='fa fa-trash'></i>", $url, ["class" => "mr-1 mb-1 btn btn-danger", "title" => "Hapus Data", "method" => "POST"]);
                    },
                    'dilakukan-revisi' => function ($url, $model, $key) {
                        return Html::a("<i class='fa fa-check'></i>", $url, ["class" => "mr-1 mb-1 btn btn-primary", "title" => "Revisi Data", "method" => "POST"]);
                    },
                ],
                // visible buttons
                'visibleButtons' => [
                    'view' => true,
                    'update' => true,
                    'delete' => true,
                    'dilakukan-revisi' => function ($model) {
                        return $model->status == \app\models\ApprovalSebelumPekerjaan::STATUS_REJECTED;
                    },
                ],
                'controller' => 'approval-sebelum-pekerjaan'
            ],
            // modified by Defri Indra
            [
                'class' => yii\grid\DataColumn::className(),
                'attribute' => 'id_progress',
                'value' => function ($model) {
                    if ($rel = $model->progress) {
                        return $rel->item;
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'nama_progress',
                'format' => 'text',
            ],
            [
                'attribute' => 'foto_material',
                'format' => 'myImage',
            ],
            [
                'attribute' => 'keterangan',
                'format' => 'ntext',
            ],
            [
                'attribute' => 'status',
                'format' => 'text',
                'filter' => \app\models\ApprovalSebelumPekerjaan::getListStatus(),
                'value' => function ($model) {
                    return $model->getStatus();
                },
            ],
            [
                'attribute' => 'revisi',
                'format' => 'ntext',
            ],
        ],
    ]); ?>
</div>
<?php \yii\widgets\Pjax::end() ?>