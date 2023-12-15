<?= $form->field($model, 'id_wilayah_provinsi', \app\components\Constant::COLUMN())->widget(\kartik\select2\Select2::classname(), [
    'name' => 'class_name',
    'model' => $model,
    'attribute' => 'id_wilayah_provinsi',
    'data' => \yii\helpers\ArrayHelper::map(app\models\WilayahProvinsi::find()->all(), 'id', 'nama'),
    'options' => [
        'placeholder' => $model->getAttributeLabel('id_wilayah_provinsi'),
        'multiple' => false,
        'disabled' => (isset($relAttributes) && isset($relAttributes['id_wilayah_provinsi'])),
    ]
]); ?>


<?= yii\helpers\Html::hiddenInput('selected_kota', ($model->isNewRecord) ? '' : $model->id_wilayah_kota, ['id' => 'selected_kota']) ?>
<?= $form->field($model, 'id_wilayah_kota', \app\components\Constant::COLUMN())->widget(\kartik\depdrop\DepDrop::class, [
    'type' => \kartik\depdrop\DepDrop::TYPE_SELECT2,
    'options' => ['id' => 'isianlanjutan-id_wilayah_kota'],
    'pluginOptions' => [
        'depends' => ['isianlanjutan-id_wilayah_provinsi'],
        'placeholder' => 'Pilih...',
        'url' => yii\helpers\Url::to(['/site/get-kota']),
        'initialize' => ($model->isNewRecord) ? false : true,
        'params' => ['selected_kota']
    ]
]) ?>

<?= yii\helpers\Html::hiddenInput('selected_kecamatan', ($model->isNewRecord) ? '' : $model->id_wilayah_kecamatan, ['id' => 'selected_kecamatan']) ?>
<?= $form->field($model, 'id_wilayah_kecamatan', \app\components\Constant::COLUMN())->widget(\kartik\depdrop\DepDrop::class, [
    'type' => \kartik\depdrop\DepDrop::TYPE_SELECT2,
    'options' => ['id' => 'isianlanjutan-id_wilayah_kecamatan'],
    'pluginOptions' => [
        'depends' => ['isianlanjutan-id_wilayah_kota'],
        'placeholder' => 'Pilih...',
        'url' => yii\helpers\Url::to(['/site/get-kecamatan']),
        'initialize' => ($model->isNewRecord) ? false : true,
        'params' => ['selected_kecamatan']
    ]
]) ?>


<?= yii\helpers\Html::hiddenInput('selected_desa', ($model->isNewRecord) ? '' : $model->id_wilayah_desa, ['id' => 'selected_desa']) ?>
<?=         // modified by Defri Indra
$form->field($model, 'id_wilayah_desa', \app\components\Constant::COLUMN())->widget(\kartik\depdrop\DepDrop::class, [
    'type' => \kartik\depdrop\DepDrop::TYPE_SELECT2,
    'options' => ['id' => 'isianlanjutan-id_wilayah_desa'],
    'pluginOptions' => [
        'depends' => ['isianlanjutan-id_wilayah_kecamatan'],
        'placeholder' => 'Pilih...',
        'url' => yii\helpers\Url::to(['/site/get-desa']),
        'initialize' => ($model->isNewRecord) ? false : true,
        'params' => ['selected_desa']
    ]
]) ?>

<?= $form->field($model, 'alamat_pelanggan', \app\components\Constant::COLUMN(2))->textArea(['maxlength' => true]) ?>
<?= $form->field($model, 'alamat_proyek', \app\components\Constant::COLUMN(2))->textArea(['maxlength' => true]) ?>
