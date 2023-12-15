<?php

use app\components\annex\Modal;
use app\models\ProyekKemajuan;
use richardfan\widget\JSRegister;
use \yii\helpers\Html;
use yii\widgets\Pjax;


Modal::begin([
    'id' => 'modal',
    'header' => '<h2>History Progress</h2>',
    'size' => 'modal-lg',
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => true]
]);
echo "<div id='modalContent'></div>";
Modal::end();

$modelProgress = new ProyekKemajuan();
$data = $model->getProyekKemajuans()
    ->andWhere(['is', 'id_parent', null])
    ->select([
        '*',
        'child' => ProyekKemajuan::find()
            ->select(['COUNT(*)'])
            ->where('b.id_parent = t_proyek_kemajuan.id')
            ->andWhere(['b.id_proyek' => $model->id, 'b.flag' => 1])
            ->alias('b')
    ])
    ->orderBy(['child' => SORT_DESC, 'item' => SORT_ASC])
    ->all();

if (function_exists('recursive_table_progress') == false) {
    function recursive_table_progress($project, $model, $defaultclass = "", $level = 1)
    {
        $template = "";
        $data = $model->getChildren()->andWhere(['flag' => 1])
            ->select([
                '*',
                'child' => ProyekKemajuan::find()
                    ->select(['COUNT(*)'])
                    ->where('b.id_parent = t_proyek_kemajuan.id')
                    ->andWhere(['b.id_proyek' => $project->id, 'b.flag' => 1])
                    ->alias('b')
            ])
            ->orderBy(['child' => SORT_DESC, 'item' => SORT_ASC])
            ->all();

        foreach ($data as $item) :
            $has_child = $item->getChildren()->andWhere(['flag' => 1])->count();
            $class = $item->id_parent ? "child-data-" . $item->id_parent : "";
            $button_start = "";

            if ($has_child) :
                $button_start = <<<JS
            <button class="btn btn-info btn-flat btn-xs" onclick="maxmin(event, 'data-$item->id')" id="data-$item->id" data-maximize="0">
                <i class="fa fa-plus"></i>
            </button>
JS;
                $button_update_data = "";
                // $button_delete = "";

                $button_delete = Html::a("<i class='fa fa-trash'></i>", [
                    'proyek-kemajuan/rdelete',
                    'id' =>  $item->id,
                    'id_project' =>  $project->id,
                ], [
                    "class" => "mr-1 mb-1 btn btn-danger",
                    "title" => "Hapus Data",
                    'method' => 'post',
                    "data-confirm" => "Apakah Anda yakin ingin menghapus data ini ?",
                ]);
            else :
                if ($item->volume <= ($item->volume_kemajuan ?? 0)) {
                    $button_update_progress = "";
                } else {
                    $params[0] = 'proyek-kemajuan-harian/create';
                    $params['ProyekKemajuanHarian'] = ['id_proyek_kemajuan' => $item->id];
                    $params['id_project'] = $project->id;
                    $button_update_progress = Html::a("<i class='fa fa-line-chart'></i>", $params, ["class" => "mr-1 mb-1 btn btn-secondary", "title" => "Update Progress"]);
                }


                $params[0] = '/proyek-kemajuan/show-history';
                $params['id'] = $item->id;
                $params['id_project'] = $project->id;
                $button_history = Html::button("<i class='fa fa-clock-o'></i>", ["value" => \yii\helpers\Url::to($params), "class" => "mr-1 mb-1 btn btn-warning modalButton", "title" => "Lihat Histori"]);

                $button_update_data =  Html::a("<i class='fa fa-pencil'></i>", [
                    'proyek-kemajuan/update',
                    'id' =>  $item->id,
                    'id_project' =>  $project->id,
                ], ["class" => "mr-1 mb-1 btn btn-warning", "title" => "Ubah Data"]);
                $button_delete = Html::a("<i class='fa fa-trash'></i>", [
                    'proyek-kemajuan/delete',
                    'id' =>  $item->id,
                    'id_project' =>  $project->id,
                ], [
                    "class" => "mr-1 mb-1 btn btn-danger",
                    "title" => "Hapus Data",
                    'method' => 'post',
                    "data-confirm" => "Apakah Anda yakin ingin menghapus data ini ?",
                ]);

                $button_start = "$button_update_progress $button_history";
            endif;
            $button_last = "$button_update_data $button_delete";

            $padding = ($level * 15) + 12;

            $status = ($has_child == false) ? $item->getStatus() : "-";
            $template .= "
            <tr class=\"$class\" style='display: none'>
                <td style='padding-left: {$padding}px'>
                    $button_start
                </td>
                <td>
                    $item->item
                </td>
                <td>
                    {$item->satuan->nama}
                </td>
                <td>
                    $item->volume
                </td>
                <td>
                    $item->bobot
                </td>
                <td>
                    $item->volume_kemajuan
                </td>
                <td>
                    $item->bobot_kemajuan
                </td>
                <td>
                    {$status}
                </td>
                <td>
                    $button_last
                </td>
            </tr>";
            $template .= recursive_table_progress($project, $item, $class, $level + 1);
        endforeach;

        return $template;
    }
}


?>
<div style='position: relative'>
    <?= Html::a(
        '<span class="fa fa-plus"></span> ' . 'Tambah Progress',
        ['proyek-kemajuan/create', 'ProyekKemajuan' => ['id_proyek' => $model->id], "id_project" => $model->id],
        ['class' => 'btn btn-success btn-xs']
    ); ?>
    <?= Html::a(
        '<span class="fa fa-sign-in"></span> ' . 'Import Laporan',
        ['proyek-kemajuan/import-excel', 'id' => $model->id, "id_project" => $model->id],
        ['class' => 'btn btn-info btn-xs']
    ); ?>
    <?= Html::a(
        '<span class="fa fa-print"></span> ' . 'Export Laporan',
        ['proyek-kemajuan/export', 'id' => $model->id, "id_project" => $model->id],
        ['class' => 'btn btn-warning btn-xs']
    ); ?>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <th><?= $modelProgress->getAttributeLabel('aksi') ?></th>
            <th><?= $modelProgress->getAttributeLabel('item') ?></th>
            <th><?= $modelProgress->getAttributeLabel('id_satuan') ?></th>
            <th><?= $modelProgress->getAttributeLabel('volume') ?></th>
            <th><?= $modelProgress->getAttributeLabel('bobot') ?></th>
            <th><?= $modelProgress->getAttributeLabel('volume_kemajuan') ?></th>
            <th><?= $modelProgress->getAttributeLabel('bobot_kemajuan') ?></th>
            <th><?= $modelProgress->getAttributeLabel('status_verifikasi') ?></th>
            <th><?= $modelProgress->getAttributeLabel('aksi') ?></th>
        </thead>
        <tbody>
            <?php foreach ($data as $item) : ?>
                <?php $has_child = $item->getChildren()->andWhere(['flag' => 1])->count() ?>
                <tr class="<?= $item->id_parent ? "child-data-" . $item->id_parent : "" ?>">
                    <td>
                        <?php if ($has_child) : ?>
                            <button class="btn btn-info btn-flat btn-xs" onclick="maxmin(event, 'data-<?= $item->id ?>')" id="data-<?= $item->id ?>">
                                <i class="fa fa-plus"></i>
                            </button>
                        <?php else : ?>
                            <?php
                            if ($item->volume > $item->volume_kemajuan) {
                                $params[0] = 'proyek-kemajuan-harian/create';
                                $params['ProyekKemajuanHarian'] = ['id_proyek_kemajuan' => $item->id];
                                $params['id_project'] = $model->id;
                                echo Html::a("<i class='fa fa-line-chart'></i>", $params, ["class" => "mr-1 mb-1 btn btn-secondary", "title" => "Update Progress"]);
                            }
                            ?>
                            <?php
                            $params[0] = '/proyek-kemajuan/show-history';
                            $params['id'] = $item->id;
                            $params['id_project'] = $model->id;
                            echo Html::button("<i class='fa fa-clock-o'></i>", ["value" => \yii\helpers\Url::to($params), "class" => "mr-1 mb-1 btn btn-warning modalButton", "title" => "Lihat Histori"]);
                            ?>
                        <?php endif ?>
                    </td>
                    <td>
                        <?= $item->item ?>
                    </td>
                    <td>
                        <?= $item->satuan->nama ?>
                    </td>
                    <td>
                        <?= $item->volume ?>
                    </td>
                    <td>
                        <?php if ($has_child) : ?>
                            <?= number_format($item->getChildren()->andWhere(['flag' => 1])->sum('bobot'), 5) ?> %
                        <?php else : ?>
                            <?= number_format($item->bobot, 5) ?> %
                        <?php endif ?>
                    </td>
                    <td>
                        <?= $item->volume_kemajuan ?>
                    </td>
                    <td>
                        <?php if ($has_child) : ?>
                            <?= number_format($item->getChildren()->andWhere(['flag' => 1])->sum('bobot_kemajuan'), 5) ?> %
                        <?php else : ?>
                            <?= number_format($item->bobot_kemajuan, 5) ?> %
                        <?php endif ?>
                    </td>
                    <td>
                        <?= ($has_child == false) ? $item->getStatus() : "-" ?>
                    </td>
                    <td>
                        <?php if ($has_child == 0) : ?>
                            <?= Html::a("<i class='fa fa-pencil'></i>", [
                                'proyek-kemajuan/update',
                                'id' =>  $item->id,
                                'id_project' => $model->id,
                            ], ["class" => "mr-1 mb-1 btn btn-warning", "title" => "Ubah Data"]); ?>
                            <?= Html::a("<i class='fa fa-trash'></i>", [
                                'proyek-kemajuan/delete',
                                'id' =>  $item->id,
                                'id_project' => $model->id,
                            ], [
                                "class" => "mr-1 mb-1 btn btn-danger",
                                "title" => "Hapus Data",
                                'method' => 'post',
                                "data-confirm" => "Apakah Anda yakin ingin menghapus data ini ?",
                            ]); ?>
                        <?php else : ?>
                            <?= Html::a("<i class='fa fa-trash'></i>", [
                                'proyek-kemajuan/rdelete',
                                'id' =>  $item->id,
                                'id_project' =>  $model->id,
                            ], [
                                "class" => "mr-1 mb-1 btn btn-danger",
                                "title" => "Hapus Data",
                                'method' => 'post',
                                "data-confirm" => "Apakah Anda yakin ingin menghapus data ini ?",
                            ]); ?>
                        <?php endif ?>
                    </td>
                </tr>
                <?= recursive_table_progress($model, $item, $item->id_parent ? "child-data-" . $item->id_parent : "", 2) ?>
            <?php endforeach ?>
        </tbody>
    </table>
</div>


<?php JSRegister::begin() ?>
<script>
    window.maxmin = function(event, cl) {
        if (document.querySelector(".child-" + cl).getAttribute('style') == 'display: none') {
            let items = document.querySelectorAll(".child-" + cl);
            for (let i = 0; i < items.length; i++) {
                items[i].setAttribute('style', '');
            }
            document.querySelector('#' + cl).innerHTML = '<i class="fa fa-minus"></i>';
            document.querySelector('#' + cl).setAttribute('class', 'btn btn-danger btn-flat btn-xs')
        } else {
            let items = document.querySelectorAll(".child-" + cl);
            for (let i = 0; i < items.length; i++) {
                items[i].setAttribute('style', 'display: none');
            }
            document.querySelector('#' + cl).innerHTML = '<i class="fa fa-plus"></i>';
            document.querySelector('#' + cl).setAttribute('class', 'btn btn-info btn-flat btn-xs')
        }
    }
</script>
<?php JSRegister::end() ?>