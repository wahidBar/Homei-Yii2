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
 * @var app\models\search\MasterKonsepDesainSearch $searchModel
 */

$this->title = 'Master Konsep Desain';
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

                            \app\components\ActionButton::getButtons(['template' => '{update} {delete}']),

                            [
                                'attribute' => 'nama_konsep',
                                'format' => 'text',
                            ],
                            [
                                'attribute' => 'gambar',
                                'filter' => false,
                                'format' => 'myImage',
                            ],
                            /*[
            'attribute' => 'flag',
            'format' => 'boolean',
        ],*/
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\widgets\Pjax::end() ?>