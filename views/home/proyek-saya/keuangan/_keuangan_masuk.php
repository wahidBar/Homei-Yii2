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
            'query' => $model->getProyekKeuanganMasuks()
                ->andWhere(['is', 't_proyek_keuangan_masuk.deleted_at', null]),
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

            // modified by Defri Indra
            [
                'class' => yii\grid\DataColumn::className(),
                'attribute' => 'id_kategori',
                'value' => function ($model) {
                    if ($rel = $model->kategori) {
                        return $rel->nama_kategori;
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'item',
                'format' => 'text',
            ],
            [
                'attribute' => 'tanggal',
                'format' => 'iddate',
            ],
            [
                'attribute' => 'jumlah',
                'format' => 'rp',
            ],
            [
                'attribute' => 'keterangan',
                'format' => 'ntext',
            ],
        ],
    ]); ?>
</div>