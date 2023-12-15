<table class="table table-hover tabl-borderless">
    <thead>
        <th>Tanggal</th>
        <th>Qty</th>
        <th>Bobot</th>
    </thead>
    <tbody>
        <?php if ($model == []) : ?>
            <tr>
                <td colspan="3" style="text-align: center;">
                    Data tidak ditemukan
                </td>
            </tr>
        <?php endif ?>
        <?php foreach ($model as $item) : ?>
            <tr>
                <td><?= \Yii::$app->formatter->asIddate($item->tanggal) ?></td>
                <td><?= $item->volume ?></td>
                <td><?= $item->bobot ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>