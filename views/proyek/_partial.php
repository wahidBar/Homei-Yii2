<div class="project-card-item project-card-win">
    <h5 class="project-card-title"><?= $model->nama_proyek ?></h5>
    <p class="project-card-info project-card-overdue"><?= Yii::$app->formatter->asIddate($model->tanggal_awal_kontrak) ?> s/d <?= Yii::$app->formatter->asIddate($model->tanggal_akhir_kontrak) ?></p>
    <p class="project-card-menu-item">
        <?php $sisa_hari = $model->getSisaHari() ?>
        <?php if ($sisa_hari < 0) : ?>
            <span class="badge badge-pill pl-3 pr-3 badge-danger">Waktu pengerjaan proyek telah berakhir</span>
        <?php else : ?>
            <span class="badge badge-pill pl-3 pr-3 badge-success">Sisa Waktu <?= $model->getSisaHari() ?> Hari</span>
        <?php endif; ?>
    </p>
    <p><?= $model->getStatus() ?></p>
    <div class="project-card-menu-item">
        <h5 class="project-card-price"><?= Yii::$app->formatter->asRp($model->nilai_kontrak) ?></h4>
    </div>
    <div class="project-card-menu-item">
        <?= dmstr\helpers\Html::a("Detail", ["view", "id" => $model->id], ["class" => "btn btn-info btn-sm btn-block mt-2"]) ?>
    </div>

</div>