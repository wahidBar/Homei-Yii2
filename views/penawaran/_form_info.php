<?php

use app\components\Constant;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
?>
<div class="clearfix"></div>
<div class="d-flex  flex-wrap">

    <?php        // modified by Defri Indra
    $id = $_GET['data_id'];
    // if ($id) {
    $isian = app\models\IsianLanjutan::find()->where(['id' => $id])->one();
    echo $form->field($model, 'id_isian_lanjutan', ['template' => '{input}'])->hiddenInput(['value' => $isian->label])->label(false);
    // } else {
    //     echo $form->field($model, 'id_isian_lanjutan', \app\components\Constant::COLUMN())->widget(\kartik\select2\Select2::classname(), [
    //         'name' => 'class_name',
    //         'model' => $model,
    //         'attribute' => 'id_isian_lanjutan',
    //         'data' => \yii\helpers\ArrayHelper::map(app\models\IsianLanjutan::find()->all(), 'id', 'label'),
    //         'options' => [
    //             'placeholder' => $model->getAttributeLabel('id_isian_lanjutan'),
    //             'multiple' => false,
    //             'disabled' => (isset($relAttributes) && isset($relAttributes['id_isian_lanjutan'])),
    //         ]
    //     ]);
    // }
    ?>
    <?= $form->field($model, 'estimasi_waktu', \app\components\Constant::COLUMN())->textInput() ?>
    <?= $form->field($model, 'total_harga_penawaran', \app\components\Constant::COLUMN())->widget(\yii\widgets\MaskedInput::class, [
        'clientOptions' => [
            'alias' =>  'decimal',
            'groupSeparator' => ',',
            'autoGroup' => true,
        ],
    ]); ?>
    <div class="clearfix"></div>
</div>