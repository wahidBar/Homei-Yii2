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
    'model' => $modelDetail[0],
    'formId' => 'ProyekKeuanganKeluar',
    'formFields' => [
        'id'
    ],
]);
?>
<div class="clearfix"></div>
<table class="table table-stripped table-bordered">
    <thead>
        <th><?= $modelDetail[0]->getAttributeLabel('item') ?></th>
        <th><?= $modelDetail[0]->getAttributeLabel('satuan') ?></th>
        <th><?= $modelDetail[0]->getAttributeLabel('kuantitas') ?></th>
        <th><?= $modelDetail[0]->getAttributeLabel('harga_satuan') ?></th>
        <th><?= $modelDetail[0]->getAttributeLabel('deskripsi') ?></th>
    </thead>
    <tbody class="container-items">
        <?php foreach ($modelDetail as $i => $item) : ?>
            <tr class="item">
                <?= $form->field($item, "[$i]id", ['template' => "{input}"])->hiddenInput(['placeholder' => ''])->label(false) ?>
                <td>
                    <?= $form->field($item, "[$i]item", ['template' => "{input}"])->textInput(['placeholder' => '', 'style' => 'border:0;border-bottom:1px solid #aaa;border-radius:0'])->label(false) ?>
                </td>
                <td>
                    <?= $form->field($item, "[$i]satuan", ['template' => "{input}"])->textInput(['placeholder' => '', 'style' => 'border:0;border-bottom:1px solid #aaa;border-radius:0'])->label(false) ?>
                </td>
                <td>
                    <?= $form->field($item, "[$i]kuantitas", ['template' => "{input}"])->textInput(['type' => 'number', 'placeholder' => '', 'style' => 'border:0;border-bottom:1px solid #aaa;border-radius:0'])->label(false) ?>
                </td>
                <td>
                    <?= $form->field($item, "[$i]harga_satuan", ['template' => "{input}"])->widget(
                        \yii\widgets\MaskedInput::class,
                        [
                            'clientOptions' => [
                                'alias' =>  'decimal',
                                'groupSeparator' => ',',
                                'autoGroup' => true
                            ],
                            'options' => [
                                'placeholder' => '',
                                'style' => 'border:0;border-bottom:1px solid #aaa;border-radius:0;width:100%'
                            ]
                        ]
                    )->label(false) ?>
                </td>
                <td>
                    <?= $form->field($item, "[$i]deskripsi", ['template' => "{input}"])->textInput(['placeholder' => '', 'style' => 'border:0;border-bottom:1px solid #aaa;border-radius:0'])->label(false) ?>
                </td>
                <td style="border:0">
                    <button type="button" class="remove-item btn btn-danger btn-xs mb-1 mr-1"><i class="fa fa-minus"></i></button>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
<button type="button" class="add-item btn btn-success btn-xs mb-1 mr-1"><i class="fa fa-plus"></i> Tambah Item </button>
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
    if (! confirm("Are you sure you want to delete this item?")) {
        return false;
    }
    return true;
});

$(".dynamicform_wrapper").on("afterDelete", function(e) {
    console.log("Deleted item!");
});

$(".dynamicform_wrapper").on("limitReached", function(e, item) {
    alert("Limit reached");
});
JS;
$this->registerJs($js);
