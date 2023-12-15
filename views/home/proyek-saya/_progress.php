<?php

use app\components\annex\Modal;
use app\models\ProyekKemajuan;
use richardfan\widget\JSRegister;
use yii\helpers\Html;
use yii\widgets\Pjax;


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

            else :
                $params[0] = '/proyek-kemajuan/show-history';
                $params['id'] = $item->id;
                $params['id_project'] = $project->id;
                $button_history = Html::button("<i class='fa fa-clock-o'></i>", ["value" => \yii\helpers\Url::to($params), "class" => "mr-1 mb-1 btn btn-warning modalButton", "title" => "Lihat Histori"]);


                $button_start = "$button_history";
            endif;
            $button_last = "$button_update_data";

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
        </tr>";
            $template .= recursive_table_progress($project, $item, $class, $level + 1);
        endforeach;

        return $template;
    }
}
?>

<ul class="nav nav-pills nav-justified" id="myTab" role="tablist">
    <li class="nav-item waves-effect waves-light pb-1">
        <a style=" border: groove; border-color: #239ade; border-top: 10px; border-left: 0px; border-right: 0px;" class="nav-link active" id="persetujuanPekerjaan-tab" data-toggle="tab" href="#persetujuanPekerjaan" role="tab" aria-controls="persetujuanPekerjaan" aria-selected="true">Persetujuan Pekerjaan</a>
    </li>
    <li class="nav-item waves-effect waves-light pb-1">
        <a style=" border: groove; border-color: #239ade; border-top: 10px; border-left: 0px; border-right: 0px;" class="nav-link" id="pekerjaan-tab" data-toggle="tab" href="#pekerjaan" role="tab" aria-controls="pekerjaan" aria-selected="false">Daftar Pekerjaan</a>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="persetujuanPekerjaan" role="tabpanel" aria-labelledby="persetujuanPekerjaan-tab">
        <?php Pjax::begin(['id' => 'pjax-ApprovalPekerjaans', 'enableReplaceState' => false, 'linkSelector' => '#pjax-ApprovalPekerjaans ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("defrindr")}']]) ?>
        <?= '<div class="table-responsive">'
            . \yii\grid\GridView::widget([
                'layout' => '{summary}<br/>{items}{pager}',
                'dataProvider' => new \yii\data\ActiveDataProvider([
                    'query' => $model->getApprovalPekerjaans()->orderBy([new \yii\db\Expression('field (status, 0, 2, 1)')]),
                    'pagination' => [
                        'pageSize' => 10,
                        'pageParam' => 'page-approvalPekerjaans',
                    ]
                ]),
                'pager'        => [
                    'class'          => \app\components\frontend\LinkPager::className(),
                    'firstPageLabel' => 'First',
                    'lastPageLabel'  => 'Last'
                ],
                'columns' => [
                    [
                        'class'      => 'yii\grid\ActionColumn',
                        'template'   => '{setuju-approval-proyek} {revisi-approval-proyek}',
                        'contentOptions' => ['nowrap' => 'nowrap'],
                        'urlCreator' => function ($action, $model, $key, $index) {
                            // using the column name as key, not mapping to 'id' like the standard generator
                            $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                            $params[0] = 'home/proyek-saya' . '/' . $action;
                            $params['ApprovalSebelumPekerjaan'] = ['id_proyek' => $model->proyek->kode_unik];

                            $params['id_project'] = $model->proyek->kode_unik;
                            return $params;
                        },
                        'buttons'    => [
                            'setuju-approval-proyek' => function ($url, $model, $key) {
                                return Html::a("Setuju", $url, ["class" => "mr-1 mb-1 btn btn-success text-white", "title" => "Setuju"]);
                            },
                            'revisi-approval-proyek' => function ($url, $model, $key) {
                                return Html::a("Revisi", $url, ["class" => "mr-1 mb-1 btn btn-warning", "title" => "Revisi Data"]);
                            },
                            // 'dilakukan-revisi' => function ($url, $model, $key) {
                            //     return Html::a("Revisi", $url, ["class" => "mr-1 mb-1 btn btn-warning", "title" => "Revisi Data", "method" => "POST"]);
                            // },
                        ],
                        // visible buttons
                        'visibleButtons' => [
                            'setuju-approval-proyek' => function ($model) {
                                return $model->status == \app\models\ApprovalSebelumPekerjaan::STATUS_PENDING;
                            },
                            'revisi-approval-proyek' => function ($model) {
                                return $model->status == \app\models\ApprovalSebelumPekerjaan::STATUS_PENDING;
                            },
                        ],
                        'controller' => 'approval-sebelum-pekerjaan'
                    ],
                    [
                        'class' => yii\grid\DataColumn::className(),
                        'attribute' => 'id_progress',
                        'value' => function ($model) {
                            if ($rel = $model->progress) {
                                return $rel->item;
                            } else {
                                return '';
                            }
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'nama_progress',
                        'format' => 'text',
                    ],
                    [
                        'attribute' => 'foto_material',
                        'format' => 'myImage',
                    ],
                    [
                        'attribute' => 'keterangan',
                        'format' => 'ntext',
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'text',
                        'filter' => \app\models\ApprovalSebelumPekerjaan::getListStatus(),
                        // get value from related model
                        'value' => function ($model) {
                            return $model->getStatus();
                        },
                    ],
                    [
                        'attribute' => 'revisi',
                        'format' => 'html',
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => 'iddate',
                    ],
                ]
            ])
            . '</div>' ?>
        <?php Pjax::end() ?>
    </div>
    <div class="tab-pane fade" id="pekerjaan" role="tabpanel" aria-labelledby="pekerjaan-tab">
        <?php
        Modal::begin([
            'id' => 'modal',
            'header' => '<h2>History Progress</h2>',
            'size' => 'modal-lg modal-dialog-centered',
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

        ?>
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
                        </tr>
                        <?= recursive_table_progress($model, $item, $item->id_parent ? "child-data-" . $item->id_parent : "", 2) ?>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
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