<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use app\models\PekerjaanSameday;
use dmstr\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\PekerjaanSamedaySearch $searchModel
 */

$this->title = 'Pekerjaan Sameday';
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
                                "template" => "{view} {update}",
                                "buttons" => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::a("<i class='fa fa-eye'></i>", ["view", "id" => $model->id], ["class" => "mr-1 mb-1 btn btn-success", "title" => "Lihat Data"]);
                                    },
                                    'update' => function ($url, $model, $key) {
                                        if ($model->status < \app\models\PekerjaanSameday::STATUS_PENGERJAAN) {
                                            return Html::a("<i class='fa fa-pencil'></i>", ["update", "id" => $model->id], ["class" => "mr-1 mb-1 btn btn-warning", "title" => "Edit Data"]);
                                        }
                                    },
                                ]
                            ]),

                            [
                                'attribute' => 'id_kategori',
                                'filter' => false,
                                'format' => 'ntext',
                                'value' => function ($model) {
                                    return $model->getViewKategori();
                                }
                            ],
                            [
                                'attribute' => 'nama_pelanggan',
                                'format' => 'text',
                            ],
                            [
                                'attribute' => 'alamat_pelanggan',
                                'format' => 'ntext',
                            ],
                            // [
                            //     'attribute' => 'foto_lokasi',
                            //     'filter' => false,
                            //     'format' => 'myImage',
                            // ],
                            // [
                            //     'attribute' => 'uraian_pekerjaan',
                            //     'filter' => false,
                            //     'format' => 'html',
                            // ],
                            [
                                'attribute' => 'status',
                                'filter' => PekerjaanSameday::getStatuses(),
                                'format' => 'text',
                                'value' => function ($model) {
                                    return $model->getStatus();
                                }
                            ],
                            [
                                'attribute' => 'id_tukang',
                                'filter' => false,
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return ($rel = $model->tukang) ? $rel->name : "<label class='p-1 bg-danger text-white' style='border-radius: 5px'>Belum diatur</label>";
                                }
                            ],
                            [
                                'attribute' => 'created_at',
                                'filter' => false,
                                'format' => 'iddate',
                            ],
                            \app\components\ActionButton::getButtons([
                                "template" => "{delete}",
                                "buttons" => [
                                    'delete' => function ($url, $model, $key) {
                                        if ($model->status < \app\models\PekerjaanSameday::STATUS_PENGERJAAN) {
                                            return Html::a("<i class='fa fa-trash'></i>", ["delete", "id" => $model->id], ["class" => "mr-1 mb-1 btn btn-danger", "title" => "Hapus Data", "data-confirm" => "Apakah Anda yakin ingin menghapus data ini?", "data-method" => "post", "data-pjax" => 0]);
                                        }
                                    },
                                ]
                            ]),
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\widgets\Pjax::end() ?>