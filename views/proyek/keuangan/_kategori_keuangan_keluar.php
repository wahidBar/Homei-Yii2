<?php

use app\components\annex\Modal;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use \dmstr\helpers\Html;
use yii\widgets\Pjax;

?>
<div class="table-responsive">

    <?= GridView::widget([
        'layout' => '{summary}{pager}{items}{pager}',
        'dataProvider' => new ActiveDataProvider([
            'query' => $model->getMasterKategoriKeuanganKeluars()->andWhere(['flag' => 1]),
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
                        $url[] = "/master-kategori-keuangan-keluar/update";
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
                        $url[] = "/master-kategori-keuangan-keluar/delete";
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
            [
                'attribute' => 'nama_kategori',
                'format' => 'ntext',
            ],
        ],
    ]); ?>
</div>