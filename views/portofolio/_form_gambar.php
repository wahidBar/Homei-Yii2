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
    'model' => $modelGambar[0],
    'formId' => 'Portofolio',
    'formFields' => [
        'id',
        'jenis_gambar',
        'gambar_design',
        'portofolio_id',
    ],
]);
?>
<div class="table-responsive">

    <table class="table table-hover table-stripped table-bordered">
        <thead style="background-color: #9A83DA;color: #fff;">
            <th style="width: 25vw">Jenis</th>
            <th style="width: 75vw">Preview</th>
            <th style="width: 25vw">Aksi</th>
        </thead>
        <tbody class="container-items1">
            <?php foreach ($modelGambar as $i => $item) : ?>
                <tr class="item1">
                    <td>
                        <?= $form->field($item, "[$i]jenis_gambar", Constant::COLUMN_DYNAMIC)->dropDownList($item::LIST_JENIS_GAMBAR) ?>
                    </td>
                    <td>
                        <?= $form->field($item, "[$i]id", Constant::COLUMN_DYNAMIC)->hiddenInput()->label(false) ?>
                        <?= $form->field($item, "[$i]gambar_design", Constant::COLUMN_DYNAMIC)->widget(FileInput::class, [

                            'options' => [
                                'multiple' => false,
                                'accept' => 'image/*',
                                'class' => 'optionvalue-img',
                            ],
                            'pluginOptions' => [
                                'previewFileType' => 'image',
                                'showCaption' => false,
                                'showUpload' => false,
                                'browseClass' => 'btn btn-default btn-sm',
                                'browseLabel' => ' Pick image',
                                'browseIcon' => '<i class="glyphicon glyphicon-picture"></i>',
                                'removeClass' => 'btn btn-danger btn-sm',
                                'removeLabel' => ' Delete',
                                'removeIcon' => '<i class="fa fa-trash"></i>',
                                'previewSettings' => [
                                    'image' => ['width' => '138px', 'height' => 'auto'],
                                ],

                                'initialPreview' => [
                                    ($item->gambar_design) ? Yii::getAlias("@web/uploads/$item->gambar_design") : "https://pbi.uad.ac.id/wp-content/uploads/2018/01/default-image.jpg",
                                ],
                                'initialPreviewAsData' => true,
                                'layoutTemplates' => ['footer' => ''],
                            ],
                        ]) ?>
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
JS;
$this->registerJs($js);
