<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use richardfan\widget\JSRegister;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\PageSearch $searchModel
 */

$this->title = 'Page';
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= Html::a('<i class="fa fa-plus"></i> Tambah Baru', ['create'], ['class' => 'btn btn-success']) ?>
</p>


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

                            \app\components\ActionButton::getButtons([
                                'template' => "{copy} {view} {update} {delete}",
                                "buttons" => [
                                    "copy" => function ($url, $model, $key) {
                                        return Html::button('<i class="fa fa-copy"></i>', [
                                            'title' => 'Copy',
                                            'class' => 'btn mr-1 mb-1 btn-info',
                                            'data-pjax' => 0,
                                            'onclick' => 'copy2clip("' . \yii\helpers\Url::to(['home/pages', 'id' => $model->slug], true) . '")',
                                            'data-toggle' => 'tooltip',
                                            'data-original-title' => 'Copy'
                                        ]);
                                    },
                                ]
                            ]),

                            [
                                'attribute' => 'thumbnail',
                                'format' => 'myImage',
                                'filter' => false,
                            ],
                            [
                                'attribute' => 'title',
                                'format' => 'text',
                            ],
                            // [
                            //     'attribute' => 'pages',
                            //     'format' => 'ntext',
                            // ],
                            [
                                'attribute' => 'view_count',
                                'format' => 'text',
                                'filter' => false,
                            ],
                            [
                                'attribute' => 'created_at',
                                'format' => 'iddate',
                                'filter' => false,
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\widgets\Pjax::end() ?>

<?php JSRegister::begin() ?>
<script>
    window.copy2clip = function(link) {
        if (navigator && navigator.clipboard) { // need to check because it's only available on https and localohost
            navigator.clipboard.writeText(link);
            alert('Link Berhasil Disalin');
        } else {
            alert('Salin Link tidak Support di Browser ini. Silahkan Copy Link Manual :' + link);
        }
    }
</script>

<?php JSRegister::end() ?>