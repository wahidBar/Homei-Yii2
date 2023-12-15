<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
?>
<?= GridView::widget([
    'layout' => '{summary}{pager}{items}{pager}',
    'dataProvider' => new ActiveDataProvider([
        'query' => $keuanganKeluar->getProyekKeuanganKeluarBayars()
    ]),
    'pager'        => [
        'class'          => app\components\annex\LinkPager::className(),
        'firstPageLabel' => 'First',
        'lastPageLabel'  => 'Last'
    ],
    'tableOptions' => ['class' => 'table table-bordered table-hover'],
    'headerRowOptions' => ['class' => 'x'],
    'columns' => [
        [
            'attribute' => 'tanggal',
            'format' => 'iddate',
        ],
        [
            'attribute' => 'dibayar',
            'format' => 'rp',
        ],
        \app\components\ActionButton::getButtons(["template" => "{delete}"]),
    ],
]); ?>