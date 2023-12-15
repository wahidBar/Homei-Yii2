<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use app\components\Constant;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use \dmstr\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\ProyekKeuanganKeluarSearch $searchModel
 */
?>

<?php \yii\widgets\Pjax::begin(['id' => 'pjax-main', 'enableReplaceState' => false, 'linkSelector' => '#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("yo")}']]) ?>

<div class="table-responsive">
    <?= GridView::widget([
        'layout' => '{summary}{pager}{items}{pager}',
        'dataProvider' => new ActiveDataProvider([
            'query' => $model->getProyekKeuanganKeluars()
                ->andWhere(['is', 'deleted_at', null])
        ]),
        'pager'        => [
            'class'          => app\components\annex\LinkPager::className(),
            'firstPageLabel' => 'First',
            'lastPageLabel'  => 'Last'
        ],
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-bordered table-hover'],
        'headerRowOptions' => ['class' => 'x'],
        'columns' => [

            \app\components\ActionButton::getButtons([
                'template' => "{view}",
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $url = \yii\helpers\Url::to([
                            '/home/proyek-saya/detail-keuangan-keluar',
                            'id' => $model->id,
                            "id_project" => $model->id_proyek,
                        ]);
                        return Html::button("<i class='fa fa-eye'></i>", [
                            "class" => "mr-1 mb-1 btn btn-success",
                            "title" => "Lihat Detail",
                            'onclick' => new \yii\web\JsExpression("openmodal( '$url',  'Detail')")
                        ]);
                    }
                ]
            ]),
            [
                'attribute' => 'id_kategori',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->kategori->nama_kategori;
                }
            ],
            [
                'attribute' => 'no_invoice',
                'format' => 'text',
            ],
            [
                'attribute' => 'detail',
                'format' => 'raw',
                'value' => function ($model) {
                    $count = $model->getProyekKeuanganKeluarDetails()->count();
                    if ($count > 1) {
                        $detail = implode(",", $model->getProyekKeuanganKeluarDetails()->select('item')->column());
                        if (strlen($detail) > 50) {
                            $detail = substr($detail, 0, 50) . "...";
                        }
                    } else {
                        $detail = implode(",", $model->getProyekKeuanganKeluarDetails()->select('item')->column());
                    }
                    return $detail;
                }
            ],
            // [
            //     'attribute' => 'qty',
            //     'label' => 'Kuantitas',
            //     'format' => 'raw',
            //     'value' => function ($model) {
            //         $count = $model->getProyekKeuanganKeluarDetails()->count();
            //         if ($count > 1) {
            //             $detail = Html::button('Detail', [
            //                 'value' => Url::to(['/proyek-keuangan-keluar/detail', 'id' => $model->id]),
            //                 'style' => 'border:0;background:transparent'
            //             ]);
            //         } else {
            //             $detail = implode(",", $model->getProyekKeuanganKeluarDetails()->select('kuantitas')->column());
            //         }
            //         return $detail;
            //     }
            // ],
            [
                'attribute' => 'tanggal',
                'format' => 'iddate',
            ],
            [
                'attribute' => 'total_jumlah',
                'format' => 'rp',
            ],
            [
                'attribute' => 'vendor',
                'format' => 'text',
            ],
            [
                'attribute' => 'keterangan',
                'format' => 'ntext',
            ],
        ],
    ]); ?>
</div>
<?php \yii\widgets\Pjax::end() ?>