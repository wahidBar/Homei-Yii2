<?= $form->field($model, 'budget', \app\components\Constant::COLUMN(3))->widget(\yii\widgets\MaskedInput::class, [

    'clientOptions' => [
        'prefix' => 'Rp ',
        'alias' =>  'decimal',
        'groupSeparator' => ',',
        'autoGroup' => true
    ],
]) ?>
<?= $form->field($model, 'id_konsep_design', \app\components\Constant::COLUMN(3))->widget(\kartik\select2\Select2::classname(), [
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
<?= $form->field($model, 'id_lantai', \app\components\Constant::COLUMN(3))->widget(\kartik\select2\Select2::classname(), [
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
<?= $form->field($model, 'panjang', \app\components\Constant::COLUMN(2))->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'lebar', \app\components\Constant::COLUMN(2))->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'luas_tanah', \app\components\Constant::COLUMN(2))->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'id_satuan', \app\components\Constant::COLUMN(2))->widget(\kartik\select2\Select2::classname(), [
    'name' => 'class_name',
    'model' => $model,
    'attribute' => 'id_satuan',
    'data' => \yii\helpers\ArrayHelper::map(app\models\MasterSatuan::find()->where(['jenis_satuan_id' => 3])->all(), 'id', 'nama'),
    'options' => [
        'placeholder' => $model->getAttributeLabel('id_satuan'),
        'multiple' => false,
        'disabled' => (isset($relAttributes) && isset($relAttributes['id_satuan'])),
    ]
]); ?>
<?= $form->field($model, 'keterangan', \app\components\Constant::COLUMN(1))->textarea(['rows' => 6]) ?>