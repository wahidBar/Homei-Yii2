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
 * @var app\models\search\SupplierPengirimanSearch $searchModel
 */
?>

<?php \yii\widgets\Pjax::begin(['id' => 'pjax-main', 'enableReplaceState' => false, 'linkSelector' => '#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("yo")}']]) ?>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <?= GridView::widget([
                        'layout' => '{summary}<br/>{items}{pager}',
                        'dataProvider' => new \yii\data\ActiveDataProvider([
                            'query' => $model->getSupplierPengirimans(),
                            'pagination' => [
                                'pageSize' => 20,
                                'pageParam' => 'page-master-templates',
                            ]
                        ]),
                        'pager'        => [
                            'class'          => \app\components\annex\LinkPager::className(),
                            'firstPageLabel' => 'First',
                            'lastPageLabel'  => 'Last'
                        ],
                        'columns' => [
                            // modified by Defri Indra
                            [
                                'attribute' => 'keterangan',
                                'format' => 'ntext',
                            ],
                            [
                                'attribute' => 'tanggal',
                                'format' => 'iddate',
                            ],
                            /*// modified by Defri Indra
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
                    ],*/
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\widgets\Pjax::end() ?>