<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use app\models\IsianLanjutan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\IsianLanjutanSearch $searchModel
 */

$this->title = 'Isian Lanjutan';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php \yii\widgets\Pjax::begin(['id' => 'pjax-main', 'enableReplaceState' => false, 'linkSelector' => '#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("yo")}']]) ?>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <?= GridView::widget([
                        'layout' => '{summary}{pager}{items}{pager}',
                        'dataProvider' => $dataProvider,
                        'pager'        => [
                            'class'          => app\components\annex\LinkPager::className(),
                            'firstPageLabel' => 'First',
                            'lastPageLabel'  => 'Last'
                        ],
                        'filterModel' => $searchModel,
                        'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                        'headerRowOptions' => ['class' => 'x'],
                        'columns' => [
                            \app\components\ActionButton::getButtons(['template' => '{view}']),
                            // modified by Defri Indra
                            [
                                'class' => yii\grid\DataColumn::className(),
                                'attribute' => 'created_at',
                                'format' => 'iddate',
                                'filter' => false
                            ],
                            // modified by Defri Indra
                            [
                                'class' => yii\grid\DataColumn::className(),
                                'attribute' => 'id_konsep_design',
                                'filter' => false,
                                'value' => function ($model) {
                                    if ($rel = $model->konsepDesign) {
                                        return $rel->nama_konsep;
                                    } else {
                                        return '';
                                    }
                                },
                                'format' => 'raw',
                            ],
                            // modified by Defri Indra
                            [
                                'class' => yii\grid\DataColumn::className(),
                                'attribute' => 'id_user',
                                'filter' => false,
                                'value' => function ($model) {
                                    if ($rel = $model->user) {
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
                                'attribute' => 'id_wilayah_provinsi',
                                'filter' => false,
                                'value' => function ($model) {
                                    if ($rel = $model->wilayahProvinsi) {
                                        return $rel->nama;
                                    } else {
                                        return '';
                                    }
                                },
                                'format' => 'raw',
                            ],
                            // modified by Defri Indra
                            [
                                'class' => yii\grid\DataColumn::className(),
                                'attribute' => 'id_wilayah_kota',
                                'filter' => false,
                                'value' => function ($model) {
                                    if ($rel = $model->wilayahKota) {
                                        return $rel->nama;
                                    } else {
                                        return '';
                                    }
                                },
                                'format' => 'raw',
                            ],
                            [
                                'class' => yii\grid\DataColumn::className(),
                                'attribute' => 'status',
                                'filter' => IsianLanjutan::getStatuses(),
                                'value' => function ($model) {
                                    if($model->is_beli_material == 1)
                                    {
                                        return '<span class="badge badge-pill pl-3 pr-3 badge-info">Beli Material</span>';
                                    }
                                    else {
                                        return $model->getStatus();
                                    }
                                },
                                'format' => 'raw',
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\widgets\Pjax::end() ?>