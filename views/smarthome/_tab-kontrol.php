<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


if (!isset($withValue)) {
    $withValue = false;
}

$query = $model->getSmarthomeKontrols()->active();
if (isset($_GET['q']) && $_GET['q']) {
    $query->andWhere(['like', 'nama', $_GET['q']]);
}

if (isset($_GET['sirkuit']) && $_GET['sirkuit']) {
    $query->andWhere(['id_sirkuit' => $_GET['sirkuit']]);
}

$data_provider = new ArrayDataProvider([
    'allModels' => $query->all(),
    'sort' => [
        'attributes' => ['id', 'nama', 'id_sirkuit', 'pin'],
    ],
    'pagination' => [
        'pageSize' => 20,
    ],
]);

$list_sirkuit = ArrayHelper::map($model->getSmarthomeSirkuits()->active()->all(), 'id', 'nama');
?>

<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 34px;
        height: 18px;
    }

    .switch input.checkbox {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 0px;
        bottom: 0px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input.checkbox:checked+.slider {
        background-color: #2196F3;
    }

    input.checkbox:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input.checkbox:checked+.slider:before {
        -webkit-transform: translateX(16px);
        -ms-transform: translateX(16px);
        transform: translateX(16px);
    }

    /* Rounded sliders */
    .slider.myrounded {
        border-radius: 15px;
    }

    .slider.myrounded:before {
        border-radius: 50%;
    }
</style>
<div>
    <form action="" method="get">
        <div class="row">
            <div class="col-sm-4 mb-2">
                <div class="input-group">
                    <input type="text" class="form-control" name="q" placeholder="Cari..." value="<?= Yii::$app->request->get('q') ?>">
                </div>
            </div>
            <div class="col-sm-4 mb-2">
                <!-- select2 filter -->
                <?= Html::dropDownList('sirkuit', Yii::$app->request->get('filter'), $list_sirkuit, [
                    'class' => 'form-control',
                    'prompt' => 'Sirkuit',
                ]) ?>
            </div>
            <div class="col-sm-4 mb-2">
                <button class="btn btn-primary btn-block" type="submit">Cari</button>
            </div>
        </div>
    </form>
</div>

<?= GridView::widget([
    'dataProvider' => $data_provider,
    // table-responsive
    'options' => ['class' => 'table-responsive'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'id_sirkuit',
            'format' => 'raw',
            'visible' => !$withValue,
            'value' => function ($model) {
                if ($rel = $model->sirkuit) {
                    return "ID:" . $model->id_sirkuit . " | " . $model->nama;
                }
                return null;
            }
        ],
        [
            'attribute' => 'ikon',
            'visible' => !$withValue,
            'format' => 'raw',
            'value' => function ($model) {
                return "<i class='fa fa-" . $model->ikon . "'></i>";
            }
        ],
        'nama',
        [
            'attribute' => 'pin',
            'visible' => !$withValue,
            'value' => function ($model) {
                return $model->pinLabel;
            }
        ],
        // checkbox ajax
        [
            'attribute' => 'value',
            'format' => 'raw',
            'visible' => $withValue,
            'value' => function ($model) {
                $checked = $model->value == 1 ? ['checked' => true] : [];
                return '
                                <label class="switch">
                                ' .
                    Html::checkbox('value', $model->value, array_merge([
                        'class' => 'checkbox-ajax checkbox',
                        'data-id' => $model->id,
                        'data-url' => \yii\helpers\Url::to(['smarthome/ubahdetail']),
                    ], $checked))
                    . '
                      <span class="slider myrounded"></span>
                    </label>
                    ';
            }
        ],
        [
            'header' => 'Aksi',
            'class' => 'yii\grid\ActionColumn',
            'template' => '{ubah} {delete}',
            'visible' => !$withValue,
            'buttons' => [
                'ubah' => function ($url, $model) {
                    return Html::a('<i class="fa fa-pencil"></i>', ['update', "id" => $model->id_smarthome, "_detail" => $model->id], [
                        'title' => 'Ubah',
                        'class' => 'btn btn-primary btn-xs mb-1 mr-1',
                    ]);
                },
                'delete' => function ($url, $model) {
                    return Html::beginForm(['smarthome/hapuskontrol', 'id' => $model->id], 'post', ['class' => 'd-inline-block'])
                        . Html::submitButton('<i class="fa fa-trash"></i>', [
                            'class' => 'btn btn-danger btn-xs mb-1 mr-1',
                            'title' => 'Hapus',
                            'data' => [
                                'confirm' => 'Apakah Anda yakin ingin menghapus item ini?',
                                'method' => 'post',
                            ],
                        ])
                        . Html::endForm();
                }
            ]
        ],
    ],
]); ?>
<?php
$this->registerJs("
    $('.checkbox-ajax').on('change', function() {
        var id = $(this).data('id');
        var url = $(this).data('url');
        var value = $(this).is(':checked');
        $.ajax({
            url: url,
            type: 'POST',
            data: {id: id, value: value},
            success: function(data) {
                if(data.success) {
                    alert(data.message, 'success', 'Notifikasi');
                } else {
                    alert(data.message, 'error', 'Notifikasi');
                }
            }
        });
    });
");
