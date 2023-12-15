<?php

use app\components\annex\Modal;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use \dmstr\helpers\Html;
use yii\widgets\Pjax;

?>

<?php \yii\widgets\Pjax::begin(['id' => 'pjax-main-keuangan-mausk', 'enableReplaceState' => false, 'linkSelector' => '#pjax-main-keuangan-mausk ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("yo")}']]) ?>
<div class="table-responsive">

    <?= GridView::widget([
        'layout' => '{summary}{pager}{items}{pager}',
        'dataProvider' => new ActiveDataProvider([
            'query' => $model->getProyekKeuanganMasuks()
                ->andWhere(['is', 't_proyek_keuangan_masuk.deleted_at', null])
                ->orderBy('t_proyek_keuangan_masuk.created_at DESC'),
        ]),
        'pager'        => [
            'class'          => app\components\annex\LinkPager::className(),
            'firstPageLabel' => 'First',
            'lastPageLabel'  => 'Last'
        ],
        'tableOptions' => ['class' => 'table table-bordered table-hover'],
        'headerRowOptions' => ['class' => 'x'],
        'columns' => [

            \app\components\ActionButton::getButtons([
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        $url = [];
                        $url[] = "/proyek-keuangan-masuk/update";
                        $url["id"] = $model->id;
                        $url["id_project"] = $model->id_proyek;
                        return Html::button("<i class='fa fa-pencil'></i>", [
                            "class" => "mr-1 mb-1 btn btn-warning",
                            "title" => "Ubah Data",
                            'onclick' => new \yii\web\JsExpression("openmodal( '" . \yii\helpers\Url::to($url) . "',  'Ubah Data')")
                        ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        $url = [];
                        $url[] = "/proyek-keuangan-masuk/delete";
                        $url["id"] = $model->id;
                        $url["id_project"] = $model->id_proyek;
                        return Html::a("<i class='fa fa-trash'></i>", $url, [
                            "class" => "mr-1 mb-1 btn btn-danger",
                            "title" => "Hapus Data",
                            'method' => 'post',
                            "data-confirm" => "Apakah Anda yakin ingin menghapus data ini ?",
                        ]);
                    },
                ]
            ]),

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
<?php Pjax::end() ?>