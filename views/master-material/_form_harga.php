<?php

use app\components\Constant;
use kartik\depdrop\DepDrop;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\Url;

DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
    'widgetBody' => '.container-items', // required: css class selector
    'widgetItem' => '.item', // required: css class
    'limit' => 9999, // the maximum times, an element can be added (default 999)
    'min' => 1, // 0 or 1 (default 1)
    'insertButton' => '.add-item', // css class
    'deleteButton' => '.remove-item', // css class
    'model' => $modelHarga[0],
    'formId' => 'MasterMaterial',
    'formFields' => [
        'id_material',
        'id_supplier',
        'id_kota',
        'id_provinsi',
        'harga',
    ],
]);
?>
<div class="clearfix"></div>
<div class="d-flex  flex-wrap">

    <table class="table table-hover table-stripped table-bordered">
        <thead>
            <th>Provinsi</th>
            <th>Kota/Kabupaten</th>
            <th>Harga</th>
            <th>Supplier</th>
        </thead>
        <tbody class="container-items">
            <?php foreach ($modelHarga as $i => $item) : ?>
                <tr class="item">
                    <td>
                        <?=
                        $form->field($item, "[{$id}]id_provinsi", \app\components\Constant::COLUMN(1, false))->widget(\kartik\select2\Select2::classname(), [
                            'model' => $item,
                            'attribute' => 'id_provinsi',
                            'data' => \yii\helpers\ArrayHelper::map(app\models\WilayahProvinsi::find()->all(), 'id', 'nama'),
                            'options' => [
                                'id' => 'hargamaterial-' . $i . '--id_provinsi',
                                'placeholder' => $item->getAttributeLabel('id_provinsi'),
                                'multiple' => false,
                                'disabled' => (isset($relAttributes) && isset($relAttributes['id_provinsi'])),
                            ]
                        ]);
                        ?>
                    </td>
                    <td>
                        <?=
                        $form->field($item, "[{$id}]id_kota", \app\components\Constant::COLUMN(1, false))->widget(DepDrop::classname(), [
                            'attribute' => 'id_kota',
                            'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                            'options' => ['id' => 'hargamaterial-' . $i . '--id_kota', 'prompt' => 'Pilih Kota .... '],
                            'pluginOptions' => [
                                'depends' => ['hargamaterial-' . $i . '--id_provinsi'],
                                'placeholder' => $item->getAttributeLabel('id_kota'),
                                'loadingText' => 'Sedang memuat ...',
                                'url' => Url::to(['/site/get-kota'])
                            ],
                        ]);
                        ?>
                    </td>
                    <td>
                        <?= $form->field($item, "[{$i}]harga", Constant::COLUMN(1, false))->widget(\yii\widgets\MaskedInput::className(), [
                            'name' => 'input-33',
                            'clientOptions' => [
                                'alias' =>  'decimal',
                                'groupSeparator' => ',',
                                'autoGroup' => true
                            ],

                        ]) ?>
                    </td>
                    <td>
                        <?=
                        $form->field($item, "[{$id}]id_supplier", \app\components\Constant::COLUMN(1, false))->widget(\kartik\select2\Select2::classname(), [
                            'model' => $item,
                            'attribute' => 'id_supplier',
                            'data' => \yii\helpers\ArrayHelper::map(app\models\Supplier::find()->all(), 'id', 'nama_supplier'),
                            'options' => [
                                'id' => 'hargamaterial-' . $i . '--id_supplier',
                                'placeholder' => $item->getAttributeLabel('id_supplier'),
                                'multiple' => false,
                                'disabled' => (isset($relAttributes) && isset($relAttributes['id_supplier'])),
                            ]
                        ]);
                        ?>
                    </td>
                    <td>
                        <button type="button" class="add-item btn btn-success btn-xs mb-1 mr-1"><i class="fa fa-plus"></i></button>
                        <button type="button" class="remove-item btn btn-danger btn-xs mb-1 mr-1"><i class="fa fa-minus"></i></button>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<?php DynamicFormWidget::end(); ?>

<div class="clearfix"></div>
<?php
$js = <<<JS
$(".dynamicform_wrapper").on("beforeInsert", function(e, item) {
    console.log("beforeInsert");
});

$(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    console.log(item);
    console.log("afterInsert");
});

$(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
    Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Data yang sudah dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
        if(result.isConfirmed) {
            $(item).remove();
            Swal.fire(
                'Terhapus!',
                'Data berhasil dihapus.',
                'success'
            )
        }else {
            Swal.fire(
                'Dibatalkan',
                'Data batal dihapus.',
                'error'
            )
        }
    })
    
    return false;
});

$(".dynamicform_wrapper").on("afterDelete", function(e) {
    console.log("Deleted item!");
});

$(".dynamicform_wrapper").on("limitReached", function(e, item) {
    alert("Limit reached");
});
JS;
$this->registerJs($js);
