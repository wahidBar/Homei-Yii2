<?php

use app\components\Constant;
use kartik\depdrop\DepDrop;
use richardfan\widget\JSRegister;
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
    'formId' => 'Penawaran',
    'formFields' => [
        'submaterial_id',
        'id_material',
    ],
]);
?>
<div class="clearfix"></div>
<div class="d-flex  flex-wrap">

    <div class="table-responsive">

        <table class="table table-hover table-stripped table-bordered">
            <thead>
                <th><?= $modelHarga[0]->getAttributeLabel('submaterial_id') ?></th>
                <th><?= $modelHarga[0]->getAttributeLabel('material_id') ?></th>
                <th><?= $modelHarga[0]->getAttributeLabel('jumlah') ?></th>
                <th><?= $modelHarga[0]->getAttributeLabel('sub_harga') ?></th>
                <th>Aksi</th>
            </thead>
            <tbody class="container-items">
                <?php foreach ($modelHarga as $i => $item) : ?>
                    <tr class="item">
                        <td>
                            <?=
                            $form->field($item, "[{$i}]submaterial_id", \app\components\Constant::COLUMN(1, false))->widget(\kartik\select2\Select2::classname(), [
                                'model' => $item,
                                'options' => [
                                    'style' => 'min-width:250px'
                                ],
                                'data' => \yii\helpers\ArrayHelper::map(app\models\SupplierSubMaterial::find()->all(), 'id', 'nama'),
                                'options' => [
                                    'id' => 'penawarandetail-' . $i . '--submaterial_id',
                                    'placeholder' => $item->getAttributeLabel('submaterial_id'),
                                    'multiple' => false,
                                    'disabled' => (isset($relAttributes) && isset($relAttributes['submaterial_id'])),
                                ]
                            ]);
                            ?>
                        </td>
                        <td>
                            <?php
                            // echo yii\helpers\Html::hiddenInput("[{$i}]selected_material", ($model->isNewRecord) ? '' : $model->id_material, ['id' => "[{$i}]selected_material"]) 
                            ?>
                            <?=
                            $form->field($item, "[{$i}]id_material", \app\components\Constant::COLUMN(1, false))->widget(DepDrop::classname(), [
                                'type' => \kartik\depdrop\DepDrop::TYPE_SELECT2,
                                'attribute' => 'id_material',
                                'options' => [
                                    'style' => 'min-width:250px'
                                ],
                                'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                                'options' => ['id' => 'penawarandetail-' . $i . '--id_material', 'prompt' => 'Pilih Material ...'],
                                'pluginOptions' => [
                                    'width' => '150px',
                                    'depends' => ['penawarandetail-' . $i . '--submaterial_id'],
                                    'placeholder' => 'Pilih...',
                                    'loadingText' => 'Sedang memuat ...',
                                    'url' => Url::to(['/site/get-barang'])
                                ],
                            ]);
                            ?>
                        </td>
                        <td>
                            <?= $form->field($item, "[{$i}]jumlah", Constant::COLUMN(1, false))->textInput([
                                'style' => 'min-width: 150px',
                                'onkeyup' => 'hitungHarga(this, false)',
                                'onkeydown' => 'hitungHarga(this, true)',
                            ]) ?>
                        </td>
                        <td>
                            <?= $form->field($item, "[{$i}]sub_harga", Constant::COLUMN(1, false))->textInput([
                                'style' => 'min-width: 150px',
                                'class' => 'form-control subharga',
                                'readonly' => true
                            ]) ?>
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
</div>
<?php DynamicFormWidget::end(); ?>

<?php JSRegister::begin() ?>
<script>
    window.hitungHarga = function(el, action) {
        // if (action) {
        var jumlah = parseInt($(el).val());
        let item = $(el).parent().parent().parent().parent().parent();
        var selectHtml = $(item.children()[1]).find('select');

        let material = $(selectHtml).find('option').filter(':selected').val();

        if (isNaN(jumlah) || jumlah == 0) {
            $(item.children()[3]).find('input').val(0)
            return;
        }

        if (material) {
            $.ajax({
                url: '<?= Url::to(['/penawaran/get-harga']) ?>',
                type: 'POST',
                data: {
                    'material': material,
                },
                success: function(data) {
                    $(item.children()[3]).find('input').val(parseInt(data.data) * parseInt(jumlah));
                    let list_subharga = $('.subharga');
                    let subharga = 0;
                    for (let i = 0; i < list_subharga.length; i++) {
                        subharga += parseInt($(list_subharga[i]).val());
                    }
                    $("#penawaran-total_harga_penawaran").val(subharga);
                }
            });
        }
        // }
    }
</script>
<?php JSRegister::end() ?>

<div class="clearfix"></div>
<?php
$js = <<<JS
$(".dynamicform_wrapper").on("beforeInsert", function(e, item) {
    console.log("beforeInsert");
});

$(".dynamicform_wrapper").on("afterInsert", function(e, item) {
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
