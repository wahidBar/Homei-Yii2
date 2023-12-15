<?php

use app\components\annex\Modal;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

?>
<div class="table-responsive">

    <?= GridView::widget([
        'layout' => '{summary}{items}{pager}',
        'dataProvider' => new ActiveDataProvider([
            'query' => $model->getMasterKategoriKeuanganMasuks(),
        ]),
        'pager'        => [
            'class'          => app\components\frontend\LinkPager::className(),
            'firstPageLabel' => 'First',
            'lastPageLabel'  => 'Last'
        ],
        'tableOptions' => ['class' => 'table table-bordered table-hover'],
        'options' => [
            'class' => 'table-responsive',
        ],
        'headerRowOptions' => ['class' => 'x'],
        'columns' => [
            [
                'attribute' => 'nama_kategori',
                'format' => 'ntext',
            ],
        ],
    ]); ?>
</div>