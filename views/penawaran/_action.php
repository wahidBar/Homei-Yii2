<?php
use yii\helpers\Html;

?>
<div class="row">
    <div class="col-md-offset-4 col-md-4 m-auto">
        <?=Html::submitButton('<i class="fa fa-save"></i> Simpan', ['class' => 'btn btn-success']);?>
        <?php Html::a('<i class="fa fa-chevron-left"></i> Kembali', ['index'], ['class' => 'btn btn-default'])?>
    </div>
</div>