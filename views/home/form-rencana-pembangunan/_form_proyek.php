<div class="col-12 text-center">
    <?php
    $setting = \app\models\SiteSetting::find()->all();
    $link =  $setting[0]['contoh_boq_proyek'];
    $absolutelink = Yii::getAlias("@file/$link");
    if (\app\components\Constant::checkFile($link)) {
        echo "<a href='$absolutelink' class='btn btn-primary text-white' target='_blank'>Download Contoh BOQ Proyek</a>";
    } else {
        echo "<span  class='badge badge-warning'>File tidak tersedia</span>";
    }
    ?>
</div>
<div class="col-sm-12 col-md-12 col-lg-12 text-left mt-3">
    <?= $form->field($model, 'nomor_spk', [
        'template' => '
            {label}
            {input}
            {error}
        ',
        'inputOptions' => [
            'class' => 'form-control'
        ],
        'labelOptions' => [
            'class' => 'control-label'
        ],
        'options' => ['tag' => false]
    ])->textInput(['maxlength' => true])->label('Nomor SPK') ?>
</div>
<div class="col-sm-12 col-md-12 col-lg-12 text-left mt-3">
    <?= $form->field($model, 'boq_proyek', [
        'template' => '
            {label}
            {input}
            {error}
        ',
        'inputOptions' => [
            'class' => 'form-control'
        ],
        'labelOptions' => [
            'class' => 'control-label'
        ],
        'options' => ['tag' => false]
    ])->widget(\kartik\file\FileInput::class, [
        // 'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'allowedFileExtensions' => ['doc', 'pdf', 'xlsx', 'xsl', 'csv'],
        ],
    ])->label('BOQ Proyek') ?>
</div>
<div class="col-sm-12 col-md-12 col-lg-12 text-left mt-3">
    <?= $form->field($model, 'informasi_proyek', [
        'template' => '
            {label}
            {input}
            {error}
        ',
        'inputOptions' => [
            'class' => 'form-control'
        ],
        'labelOptions' => [
            'class' => 'control-label'
        ],
        'options' => ['tag' => false]
    ])->textarea(['rows' => 6])->label('Informasi Proyek') ?>
</div>