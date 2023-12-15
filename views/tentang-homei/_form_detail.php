<?php

use app\components\Constant;
use kartik\file\FileInput;
use wbraganca\dynamicform\DynamicFormWidget;

DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_wrapper_1', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
    'widgetBody' => '.container-items1', // required: css class selector
    'widgetItem' => '.item1', // required: css class
    'limit' => 9999, // the maximum times, an element can be added (default 999)
    'min' => 1, // 0 or 1 (default 1)
    'insertButton' => '.add-item1', // css class
    'deleteButton' => '.remove-item1', // css class
    'model' => $modelDetail[0],
    'formId' => 'TentangHomei',
    'formFields' => [
        'id',
        'icon',
        'judul',
        'isi',
    ],
]);
?>
<div class="table-responsive">

    <table class="table table-hover table-stripped table-bordered">
        <thead style="background-color: #9A83DA;color: #fff;">
            <th style="width: 25vw">Icon</th>
            <th style="width: 25vw">Judul</th>
            <th style="width: 50vw">Isi</th>
            <th style="width: 25vw">Aksi</th>
        </thead>
        <tbody class="container-items1">
            <?php foreach ($modelDetail as $i => $item) : ?>
                <tr class="item1">
                    <td>
                        <div class="row">
                            <?= $form->field($item, "[$i]id", \app\components\Constant::COLUMN(1))->hiddenInput()->label(false) ?>
                            <?= $form->field($item, "[$i]icon", \app\components\Constant::COLUMN(1))->textInput(['class' => "form-control icp-auto", 'autocomplete' => 'off']) ?>
                        </div>
                    </td>
                    <td>
                        <?= $form->field($item, "[$i]judul", \app\components\Constant::COLUMN(1))->textInput() ?>
                    </td>
                    <td>
                        <?= $form->field($item, "[$i]isi", \app\components\Constant::COLUMN(1))->textArea() ?>
                    </td>
                    <td>
                        <button type="button" class="add-item1 btn btn-success btn-xs mb-1 mr-1"><i class="fa fa-plus"></i></button>
                        <button type="button" class="remove-item1 btn btn-danger btn-xs mb-1 mr-1"><i class="fa fa-minus"></i></button>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<?php DynamicFormWidget::end(); ?>

<?php
$js = <<<JS
$(".dynamicform_wrapper_1").on("beforeInsert", function(e, item) {
    console.log("beforeInsert");
});

$(".dynamicform_wrapper_1").on("afterInsert", function(e, item) {
    console.log("afterInsert");
});

$(".dynamicform_wrapper_1").on("beforeDelete", function(e, item) {
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

$(".dynamicform_wrapper_1").on("afterDelete", function(e) {
    console.log("Deleted item!");
});

$(".dynamicform_wrapper_1").on("limitReached", function(e, item) {
    alert("Limit reached");
});

$(".optionvalue-img").on("filecleared", function(event) {
    // var regexID = /^(.+?)([-\d-]{1,})(.+)$/i;
    // var id = event.target.id;
    // var matches = id.match(regexID);
    // if (matches && matches.length === 4) {
    //     var identifiers = matches[2].split("-");
    //     $("#optionvalue-" + identifiers[1] + "-deleteimg").val("1");
    // }
});
$(".icp-auto").iconpicker();
JS;
$this->registerJs($js);
