<?php

use app\models\MasterRuangan;
use dosamigos\selectize\SelectizeDropDownList;
use yii\helpers\ArrayHelper;

?>
<?= $form->field($model, 'list_ruangan', \app\components\Constant::COLUMN(1))->label('Permintaan Ruangan')->widget(
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