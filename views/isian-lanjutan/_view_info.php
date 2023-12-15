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
                        [
                            'attribute' => 'dp_pembayaran',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Yii::$app->formatter->asRp($model->dp_pembayaran);
                            }
                        ],
                        [
                            'attribute' => 'bukti_pembayaran',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Yii::$app->formatter->asDownload($model->bukti_pembayaran);
                            }
                        ],
                        [
                            'attribute' => 'tanggal_pembayaran',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return \app\components\Tanggal::toReadableDate($model->tanggal_pembayaran);
                            }
                        ],
                        [
                            'attribute' => 'status_pembayaran',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if ($model->status_pembayaran == 0) {
                                    return "DP Belum Dibayar atau Diset Admin";
                                }
                                if ($model->status_pembayaran == 1) {
                                    return "Dalam Pengecekan";
                                }
                                if ($model->status_pembayaran == 2) {
                                    return "DP Telah Dibayar";
                                }
                                if ($model->status_pembayaran == 3) {
                                    return "DP Ditolak";
                                }
                            }
                        ],
                        [
                            'attribute' => 'keterangan_pembayaran',
                            'format' => 'text',
                        ],
                        [
                            'attribute' => 'alasan_tolak',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if ($model->alasan_tolak != null) {
                                    return $model->alasan_tolak;
                                } else {
                                    return "-";
                                }
                            }
                        ],
                    ],
                ]); ?>
                <!-- <?php if ($model->status_pembayaran == 1) : ?>
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th><?= Yii::t("cruds", "Konfirmasi Pembayaran DP") ?></th>
                                <td>
                                    <?= Html::a('Konfirmasi', ['konfirmasi-dp', 'id' => $model->id], ['class' => 'btn btn-success', 'data-confirm' => '' . 'Apakah Anda Yakin?' . '',]) ?>
                                    <?= Html::a('Tolak', ['tolak-dp', 'id' => $model->id], ['class' => 'btn btn-danger', 'data-confirm' => '' . 'Apakah Anda Yakin?' . '',]) ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php endif ?> -->
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
                        [
                            'attribute' => 'rencana_survey',
                            'format' => 'iddate',
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->getStatus();
                            }
                        ],
                        [
                            'attribute' => 'dokumen_tor',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if ($model->dokumen_tor != null) {
                                    return Yii::$app->formatter->asDownload($model->dokumen_tor);
                                } else {
                                    return "-";
                                }
                            }
                        ],
                        [
                            'attribute' => 'alasan_tolak',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if ($model->alasan_tolak != null) {
                                    return $model->alasan_tolak;
                                } else {
                                    return "-";
                                }
                            }
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <h3>Penawaran yang Dipilih</h3>
        <div class="card m-b-30">
            <div class="card-body">
                <?= '<div class="table-responsive">'
                    .                     \yii\grid\GridView::widget([
                        'layout' => '{summary}<br/>{items}{pager}',
                        'dataProvider' => new \yii\data\ActiveDataProvider([
                            'query' => $model->getPenawarans()->where(['id' => $model->id_penawaran]),
                            'pagination' => [
                                'pageSize' => 20,
                                'pageParam' => 'page-penawarans',
                            ]
                        ]),
                        'pager'        => [
                            'class'          => \app\components\annex\LinkPager::className(),
                            'firstPageLabel' => 'First',
                            'lastPageLabel'  => 'Last'
                        ],
                        'columns' => [
                            [
                                'class'      => 'yii\grid\ActionColumn',
                                'template'   => '{view}',
                                'contentOptions' => ['nowrap' => 'nowrap'],
                                'urlCreator' => function ($action, $model, $key, $index) {
                                    // using the column name as key, not mapping to 'id' like the standard generator
                                    $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                                    $params[0] = 'penawaran' . '/' . $action;
                                    $params['Penawaran'] = ['id_isian_lanjutan' => $model->primaryKey()[0]];
                                    return $params;
                                },
                                'buttons'    => [
                                    'view' => function ($url, $model, $key) {
                                        $params[0] = '/penawaran/detail';
                                        $params['id'] = $model->id;
                                        return Html::button("<i class='fa fa-clock-o'></i>", [
                                            "value" => \yii\helpers\Url::to($params),
                                            "class" => "mr-1 mb-1 btn btn-warning modalButton",
                                            "title" => "Lihat Detail"
                                        ]);
                                    },
                                ],
                                'controller' => 'penawaran'
                            ],
                            [
                                'attribute' => 'kode_penawaran',
                                'format' => 'text',
                            ],
                            [
                                'attribute' => 'tgl_transaksi',
                                'format' => 'iddate',
                            ],
                            [
                                'attribute' => 'estimasi_waktu',
                                'format' => 'text',
                            ],
                            [
                                'attribute' => 'harga_penawaran',
                                'format' => 'rp',
                            ],
                            // [
                            //     'attribute' => 'flag',
                            //     'format' => 'boolean',
                            // ],
                        ]
                    ])
                    . '</div>' ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <h3>Keinginan Ruangan</h3>
        <div class="card m-b-30">
            <div class="card-body">
                <?= $this->render('_view_ruangan', compact('model')) ?>
            </div>
        </div>
    </div>
</div>