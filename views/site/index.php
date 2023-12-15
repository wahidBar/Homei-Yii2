<?php

/* @var $this yii\web\View */

use app\formatter\CustomFormatter;
use app\models\IsianLanjutan;
use app\models\MasterToko;
use app\models\Sales;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Dashboard';

$css = <<<CSS
.select2-selection__clear{
    margin-right:20px!important;
}
.card-body{
    border-radius:10px;
}
.card-body .main{
    font-size:28px;
}
.card-body> h5{
    font-size:14px;
}
.card-primary{
    color:#fff;
    background-color:#221d57;
}
.card-primary>.card-body>.main>i.fa {
    color:#E99409;
}
.card-secondary{
    color:#fff;
    background-color:#E99409;
}
.card-secondary>.card-body>.main>i.fa {
    color:#221d57;
}
CSS;
$this->registerCss($css);

?>


<div class="site-index">
    <div class="row">
        <!-- Column -->
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="card m-b-30">
                <div class="card-body">
                    <a href="<?= Url::to(['user/index']); ?>">
                        <div class="d-flex flex-row">
                            <table class="table table-borderless">
                                <tr>
                                    <td style="padding: 0px 0px;">
                                        <div class="round"><i class="mdi mdi-account-multiple-plus"></i></div>
                                    </td>
                                    <td style="padding: 0px 0px;">
                                        <div class="m-l-10 text-center">
                                            <h5 class="mt-0 round-inner"><?= $jumlah_user ?></h5>
                                            <p class="mb-0 text-muted">Jumlah Pengguna</p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </a>
                </div>
            </div>
        </div><!-- Column -->
        <!-- Column -->
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="card m-b-30">
                <div class="card-body">
                    <a href="<?= Url::to(['isian-lanjutan/index']); ?>">
                        <div class="d-flex flex-row">
                            <table class="table table-borderless">
                                <tr>
                                    <td style="padding: 0px 0px;">
                                        <div class="round"><i class="fa fa-file-text-o"></i></div>
                                    </td>
                                    <td style="padding: 0px 0px;">
                                        <div class="m-l-10 text-center">
                                            <h5 class="mt-0 round-inner"><?= $isian ?></h5>
                                            <p class="mb-0 text-muted">Isian Lanjutan</p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </a>
                </div>
            </div>
        </div><!-- Column -->
        <!-- Column -->
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="card m-b-30">
                <div class="card-body">
                    <a href="<?= Url::to(['proyek/index']); ?>">
                        <div class="d-flex flex-row">
                            <table class="table table-borderless">
                                <tr>
                                    <td style="padding: 0px 0px;">
                                        <div class="round"><i class="fa fa-building"></i></div>
                                    </td>
                                    <td style="padding: 0px 0px;">
                                        <div class="m-l-10 text-center">
                                            <h5 class="mt-0 round-inner"><?= $jumlah_proyek ?></h5>
                                            <p class="mb-0 text-muted">Jumlah Proyek</p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </a>
                </div>
            </div>
        </div><!-- Column -->
        <!-- Column -->
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="card m-b-30">
                <div class="card-body">
                    <a href="<?= Url::to(['isian-lanjutan/index']); ?>">
                        <div class="d-flex flex-row">
                            <table class="table table-borderless">
                                <tr>
                                    <td style="padding: 0px 0px;">
                                        <div class="round"><i class="fa fa-handshake-o"></i></div>
                                    </td>
                                    <td style="padding: 0px 0px;">
                                        <div class="m-l-10 text-center">
                                            <h5 class="mt-0 round-inner"><?= $jumlah_deal ?></h5>
                                            <p class="mb-0 text-muted">Deal Proyek</p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </a>
                </div>
            </div>
        </div><!-- Column -->
    </div>

    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-8 align-self-center">
            <?php \yii\widgets\Pjax::begin(['id' => 'pjax-main', 'enableReplaceState' => false, 'linkSelector' => '#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("yo")}']]) ?>
            <div class="card bg-white m-b-30">
                <div class="card-body new-user">
                    <h5 class="header-title mb-4 mt-0">Isian Lanjutan</h5>
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'layout' => '{summary}{items}',
                            'dataProvider' => $dataProviderIsian,
                            'pager'        => [
                                'class'          => app\components\annex\LinkPager::className(),
                                'firstPageLabel' => 'First',
                                'lastPageLabel'  => 'Last'
                            ],
                            'filterModel' => $searchModelIsian,
                            'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                            'headerRowOptions' => ['class' => 'x'],
                            'columns' => [
                                [
                                    'class' => yii\grid\DataColumn::className(),
                                    'attribute' => 'detail',
                                    'format' => 'html',
                                    'value' => function ($model) {
                                        return Html::a("<i class='fa fa-eye'></i>", ["/isian-lanjutan/view", "id" => $model->id], ["class" => "mr-1 mb-1 btn btn-success", "title" => "Lihat Data"]);
                                    }
                                ],
                                // modified by Defri Indra
                                [
                                    'class' => yii\grid\DataColumn::className(),
                                    'attribute' => 'created_at',
                                    'format' => 'iddate',
                                    'filter' => false
                                ],
                                // modified by Defri Indra
                                [
                                    'class' => yii\grid\DataColumn::className(),
                                    'attribute' => 'id_user',
                                    'filter' => false,
                                    'value' => function ($model) {
                                        if ($rel = $model->user) {
                                            return $rel->name;
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'raw',
                                ],
                                // modified by Defri Indra
                                [
                                    'class' => yii\grid\DataColumn::className(),
                                    'attribute' => 'id_wilayah_provinsi',
                                    'filter' => false,
                                    'value' => function ($model) {
                                        if ($rel = $model->wilayahProvinsi) {
                                            return $rel->nama;
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'raw',
                                ],
                                [
                                    'class' => yii\grid\DataColumn::className(),
                                    'attribute' => 'status',
                                    'filter' => IsianLanjutan::getStatuses(),
                                    'value' => function ($model) {
                                        return $model->getStatus();
                                    },
                                    'format' => 'raw',
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
            <?php \yii\widgets\Pjax::end() ?>
        </div>
        <div class="col-md-12 col-lg-12 col-xl-4">
            <div class="card bg-white m-b-30">
                <div class="card-body new-user">
                    <h5 class="header-title mt-0 mb-4">Pengguna Online Terakhir</h5>
                    <ul class="list-unstyled mb-0 pr-3" id="boxscroll2" tabindex="1" style="overflow: hidden; outline: none;">
                        <?php foreach ($users as $user) { ?>
                            <li class="p-3">
                                <div class="media">
                                    <div class="thumb float-left"><a href="#">
                                            <?php if ($user->photo_url != null) { ?>
                                                <img class="rounded-circle" src="<?= Yii::getAlias("@web/uploads/") . $user->photo_url ?>" style="width: 50px;height: 50px;" alt=""></a>
                                    <?php } else { ?>
                                        <img class="rounded-circle" src="<?= Yii::getAlias("@web/uploads/default.png") ?>" style="width: 50px;height: 50px;" alt=""></a>
                                    <?php } ?>
                                    </div>
                                    <div class="media-body">
                                        <p class="media-heading mb-0"><?= $user->name ?>
                                            <?php if ($user->is_active == 1) { ?>
                                                <i class="fa fa-circle text-success mr-1 pull-right"></i>
                                            <?php } else { ?>
                                                <i class="fa fa-circle text-danger mr-1 pull-right"></i>
                                            <?php } ?>
                                        </p>
                                        <small class="pull-right text-muted"><?php if ($user->is_active == 1) {
                                                                                    echo "Online";
                                                                                } else {
                                                                                    echo "Offline";
                                                                                } ?></small>
                                        <small class="text-muted">Login Terakhir : <p> <?= \Yii::$app->formatter->asIddate($user->last_login) ?></p></small>

                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-12 col-xl-12 align-self-center">
            <?php \yii\widgets\Pjax::begin(['id' => 'pjax-main', 'enableReplaceState' => false, 'linkSelector' => '#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("yo")}']]) ?>
            <div class="card bg-white m-b-30">
                <div class="card-body new-user">
                    <h5 class="header-title mb-4 mt-0">Daftar Proyek</h5>
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'layout' => '{summary}{items}{pager}',
                            'dataProvider' => $dataProviderProyek,
                            'pager'        => [
                                'class'          => app\components\annex\LinkPager::className(),
                                'firstPageLabel' => 'First',
                                'lastPageLabel'  => 'Last'
                            ],
                            'filterModel' => $searchModelProyek,
                            'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                            'headerRowOptions' => ['class' => 'x'],
                            'columns' => [

                                [
                                    'class' => yii\grid\DataColumn::className(),
                                    'attribute' => 'detail',
                                    'format' => 'html',
                                    'value' => function ($model) {
                                        return Html::a("<i class='fa fa-eye'></i>", ["/proyek/view", "id" => $model->id], ["class" => "mr-1 mb-1 btn btn-success", "title" => "Lihat Data"]);
                                    }
                                ],

                                // modified by Defri Indra
                                [
                                    'class' => yii\grid\DataColumn::className(),
                                    'attribute' => 'id_user',
                                    'value' => function ($model) {
                                        if ($rel = $model->user) {
                                            return $rel->name;
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'raw',
                                ],
                                [
                                    'attribute' => 'nama_proyek',
                                    'format' => 'text',
                                ],
                                [
                                    'attribute' => 'deskripsi_proyek',
                                    'format' => 'ntext',
                                ],
                                [
                                    'attribute' => 'nilai_kontrak',
                                    'format' => 'rp',
                                ],
                                [
                                    'attribute' => 'sisa_hari',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $model->getSisaHari("html");
                                    }
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
            <?php \yii\widgets\Pjax::end() ?>
        </div>
    </div>
</div>