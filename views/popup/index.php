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
 * @var app\models\search\PopupSearch $searchModel
 */

$this->title = 'Popup';
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

                            \app\components\ActionButton::getButtons(),

                            [
                                'attribute' => 'image',
                                'filter' => false,
                                'format' => 'myImage',
                            ],
                            // [
                            //     'attribute' => 'android_route',
                            //     'format' => 'text',
                            // ],
                            // [
                            //     'attribute' => 'android_params',
                            //     'format' => 'text',
                            // ],
                            [
                                'attribute' => 'android_redirect_type',
                                'format' => 'text',
                                'filter' => \app\models\Popup::DROPDOWN_REDIRECT_TYPE,
                            ],
                            [
                                'attribute' => 'android_show',
                                'format' => 'text',
                                'filter' => \app\models\Popup::DROPDOWN_ANDROID_SHOW,
                            ],
                            [
                                'attribute' => 'web_show',
                                'format' => 'text',
                                'filter' => \app\models\Popup::DROPDOWN_WEB_SHOW,
                            ],
                            /*[
            'attribute' => 'web_link',
            'format' => 'text',
        ],*/
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\widgets\Pjax::end() ?>