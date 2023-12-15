<?php 
$rooms = \app\models\IsianLanjutanRuangan::find()->where(['id_isian_lanjutan' => $model->id])->all();
?>
<table class="table table-responsive table-striped table-bordered">
    <thead>
        <tr>
            <th style="width: 100vw;">Ruangan</th>
        </tr>
        <?php foreach($rooms as $room){ ?>
        <tr>
            <td><?= $room->ruangan->nama ?></td>
        </tr>
        <?php } ?>
    </thead>
</table>