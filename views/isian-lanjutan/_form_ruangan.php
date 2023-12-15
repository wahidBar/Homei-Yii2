<?php

use app\models\MasterRuangan;
use dosamigos\selectize\SelectizeDropDownList;
use yii\helpers\ArrayHelper;

?>
<?= $form->field($model, 'list_ruangan', \app\components\Constant::COLUMN(1))->widget(
    SelectizeDropDownList::class,
    [
        "items" => ArrayHelper::map(MasterRuangan::find()->all(), 'id', 'nama'),
        "options" => [
            "multiple" => true,
            'prompt' => "--Pilih Kategori--",
        ],
        "clientOptions" => [
            'persist' => false,
            'maxItems' => null,
            'plugins' => ['remove_button'],
            'valueField' => 'id',
            'labelField' => 'name',
            'searchField' => ['name'],
            'create' => false,
        ],
    ]
) ?>

<?= $form->field($model, 'id_konsep_design', \app\components\Constant::COLUMN())->widget(\kartik\select2\Select2::classname(), [
    'name' => 'class_name',
    'model' => $model,
    'attribute' => 'id_konsep_design',
    'data' => \yii\helpers\ArrayHelper::map(app\models\MasterKonsepDesain::find()->all(), 'id', 'nama_konsep'),
    'options' => [
        'placeholder' => $model->getAttributeLabel('id_konsep_design'),
        'multiple' => false,
        'disabled' => (isset($relAttributes) && isset($relAttributes['id_konsep_design'])),
    ]
]); ?>
<?= $form->field($model, 'id_lantai', \app\components\Constant::COLUMN())->widget(\kartik\select2\Select2::classname(), [
    'name' => 'class_name',
    'model' => $model,
    'attribute' => 'id_lantai',
    'data' => \yii\helpers\ArrayHelper::map(app\models\MasterLantai::find()->all(), 'id', 'nama'),
    'options' => [
        'placeholder' => $model->getAttributeLabel('id_lantai'),
        'multiple' => false,
        'disabled' => (isset($relAttributes) && isset($relAttributes['id_lantai'])),
    ]
]); ?>