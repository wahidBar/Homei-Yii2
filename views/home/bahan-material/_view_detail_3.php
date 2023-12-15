<?php

use dmstr\helpers\Html;
use yii\widgets\DetailView;
?>

<div class="row">
    <div class="col-lg-6 col-md-6">
        <h3>Informasi Umum</h3>
        <div class="card m-b-30">
            <div class="card-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table table-striped'],
                    'attributes' => [

                        // modified by Defri Indra
                        // [
                        //     'format' => 'html',
                        //     'attribute' => 'id_user',
                        //     'value' => ($model->user ? $model->user->name : '<span class="label label-warning">?</span>'),
                        // ],
                        [
                            'attribute' => 'label',
                            'format' => 'text',
                        ],
                        [
                            'attribute' => 'nama_awal',
                            'format' => 'text',
                        ],
                        [
                            'attribute' => 'nama_akhir',
                            'format' => 'text',
                        ],
                        [
                            'attribute' => 'no_hp',
                            'format' => 'text',
                        ],
                        // modified by Defri Indra
                        [
                            'format' => 'html',
                            'attribute' => 'id_wilayah_provinsi',
                            'value' => ($model->wilayahProvinsi ? $model->wilayahProvinsi->nama : '<span class="label label-warning">?</span>'),
                        ],
                        // modified by Defri Indra
                        [
                            'format' => 'html',
                            'attribute' => 'id_wilayah_kota',
                            'value' => ($model->wilayahKota ? $model->wilayahKota->nama : '<span class="label label-warning">?</span>'),
                        ],
                        // modified by Defri Indra
                        [
                            'format' => 'html',
                            'attribute' => 'id_wilayah_kecamatan',
                            'value' => ($model->wilayahKecamatan ? $model->wilayahKecamatan->nama : '<span class="label label-warning">?</span>'),
                        ],
                        // modified by Defri Indra
                        [
                            'format' => 'html',
                            'attribute' => 'id_wilayah_desa',
                            'value' => ($model->wilayahDesa ? $model->wilayahDesa->nama : '<span class="label label-warning">?</span>'),
                        ],
                        [
                            'attribute' => 'alamat_pelanggan',
                            'format' => 'text',
                        ],
                        [
                            'attribute' => 'alamat_proyek',
                            'format' => 'text',
                        ],
                        [
                            'attribute' => 'keterangan',
                            'format' => 'ntext',
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <h3>Informasi Teknis</h3>
        <div class="card m-b-30">
            <div class="card-body table-responsive">
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table table-striped'],
                    'attributes' => [

                        [
                            'attribute' => 'panjang',
                            'format' => 'text',
                            // 'value' => function ($model) {
                            //     return $model->panjang . " " . $model->satuan->nama;
                            // }
                        ],
                        [
                            'attribute' => 'lebar',
                            'format' => 'text',
                            // 'value' => function ($model) {
                            //     return $model->lebar . " " . $model->satuan->nama;
                            // }
                        ],
                        [
                            'attribute' => 'budget',
                            'format' => 'rp',
                        ],
                        [
                            'attribute' => 'luas_tanah',
                            'format' => 'text',
                            'value' => function ($model) {
                                return $model->luas_tanah . " " . $model->satuan->nama;
                            }
                        ],
                        [
                            'format' => 'html',
                            'attribute' => 'id_konsep_design',
                            'value' => ($model->konsepDesign ? $model->konsepDesign->nama_konsep : '<span class="label label-warning">?</span>'),
                        ],
                        // modified by Defri Indra
                        [
                            'format' => 'html',
                            'attribute' => 'id_lantai',
                            'value' => ($model->lantai ? $model->lantai->nama : '<span class="label label-warning">?</span>'),
                        ],
                        [
                            'attribute' => 'rencana_pembangunan',
                            'format' => 'iddate',
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <h3>Informasi Proyek</h3>
        <div class="card m-b-30">
            <div class="card-body">
                <table class="table table-responsive table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nomor SPK</th>
                            <td>
                                <?php
                                if ($model->nomor_spk) {
                                    echo $model->nomor_spk;
                                } else {
                                    echo "-";
                                } ?>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Document BOQ</th>
                            <td>
                                <?php
                                if ($model->boq_proyek) {
                                    echo Yii::$app->formatter->asDownload($model->boq_proyek);
                                } else {
                                    echo "-";
                                } ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Informasi Proyek</th>
                            <td>
                                <?php
                                if ($model->informasi_proyek) {
                                    echo $model->informasi_proyek;
                                } else {
                                    echo "-";
                                } ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 col-md-6">
        <h3>Keinginan Ruangan</h3>
        <div class="card m-b-30">
            <div class="card-body">
                <?php

                use yii\widgets\Pjax;

                Pjax::begin(['id' => 'pjax-IsianLanjutanRuangans', 'enableReplaceState' => false, 'linkSelector' => '#pjax-IsianLanjutanRuangans ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("defrindr")}']]) ?>
                <?= '<div class="table-responsive">'
                    . \yii\grid\GridView::widget([
                        'layout' => '{items}{pager}',
                        'dataProvider' => new \yii\data\ActiveDataProvider([
                            'query' => $model->getIsianLanjutanRuangans(),
                            'pagination' => [
                                'pageSize' => 20,
                                'pageParam' => 'page-isianlanjutanruangans',
                            ]
                        ]),
                        'pager'        => [
                            'class'          => \app\components\annex\LinkPager::className(),
                            'firstPageLabel' => 'First',
                            'lastPageLabel'  => 'Last'
                        ],
                        'columns' => [
                            // modified by Defri Indra
                            [
                                'class' => yii\grid\DataColumn::className(),
                                'attribute' => 'id_ruangan',
                                'value' => function ($model) {
                                    if ($rel = $model->ruangan) {
                                        return $rel->nama;
                                    } else {
                                        return '';
                                    }
                                },
                                'format' => 'raw',
                            ],
                        ]
                    ])
                    . '</div>' ?>
                <?php Pjax::end() ?>
            </div>
        </div>
    </div>
</div>