<table class="table table-responsive table-striped table-bordered">
    <thead>
        <tr>
            <th>Nomor SPK</th>
            <td>
                <?php
                if ($model->nomor_spk) {
                    echo $model->nomor_spk;
                } else {
                    echo "-";
                } ?>
            </td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>Document BOQ</th>
            <td>
                <?php
                if ($model->boq_proyek) {
                    echo Yii::$app->formatter->asDownload($model->boq_proyek);
                } else {
                    echo "-";
                } ?>
            </td>
        </tr>
        <tr>
            <th>Informasi Proyek</th>
            <td>
                <?php
                if ($model->informasi_proyek) {
                    echo $model->informasi_proyek;
                } else {
                    echo "-";
                } ?>
            </td>
        </tr>
    </tbody>
</table>