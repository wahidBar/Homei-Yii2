<?php
/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use dmstr\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use app\components\annex\Tabs;

/**
* @var yii\web\View $this
* @var app\models\Supplier $model
*/

$this->title = 'Supplier : ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Supplier', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';
\app\assets\MapAsset::register($this);

?>
<style>
    #map_canvas {
        width: 100%;
        height: 70vh;
        margin-bottom: 1rem;
        border-radius: 20px;
        box-shadow: 0 8px 4px 5px #eee;
    }
</style>
<div class="giiant-crud supplier-view">

    <!-- menu buttons -->
    <p class='pull-left'>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> ' . 'Edit', ['update', 'id' => $model->id],['class' => 'btn btn-info']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'Tambah Baru', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <p class="pull-right">
        <?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . 'Daftar Supplier', ['index'], ['class'=>'btn btn-default']) ?>
    </p>

    <div class="clearfix"></div>

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?= \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <?php $this->beginBlock('app\models\Supplier'); ?>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
					                    // modified by Defri Indra
                    [
                        'format' => 'html',
                        'attribute' => 'id_provinsi',
                        'value' => ($model->provinsi ? $model->provinsi->nama : '<span class="label label-warning">?</span>'),
                    ],
					                    // modified by Defri Indra
                    [
                        'format' => 'html',
                        'attribute' => 'id_kota',
                        'value' => ($model->kota ? $model->kota->nama : '<span class="label label-warning">?</span>'),
                    ],
					        [
            'attribute' => 'nama_supplier',
            'format' => 'text',
        ],
					        [
            'attribute' => 'alamat',
            'format' => 'ntext',
        ],
					        [
            'attribute' => 'telepon',
            'format' => 'text',
        ],
					        [
            'attribute' => 'rekomendasi_homei',
            'format' => 'boolean',
        ],
					        [
            'attribute' => 'created_at',
            'format' => 'iddate',
        ],
					        [
            'attribute' => 'updated_at',
            'format' => 'iddate',
        ],
					        [
            'attribute' => 'deleted_at',
            'format' => 'iddate',
        ],
					                    // modified by Defri Indra
                    [
                        'format' => 'html',
                        'attribute' => 'created_by',
                        'value' => ($model->createdBy ? $model->createdBy->name : '<span class="label label-warning">?</span>'),
                    ],
					                    // modified by Defri Indra
                    [
                        'format' => 'html',
                        'attribute' => 'updated_by',
                        'value' => ($model->updatedBy ? $model->updatedBy->name : '<span class="label label-warning">?</span>'),
                    ],
					                    // modified by Defri Indra
                    [
                        'format' => 'html',
                        'attribute' => 'deleted_by',
                        'value' => ($model->deletedBy ? $model->deletedBy->name : '<span class="label label-warning">?</span>'),
                    ],
					        [
            'attribute' => 'flag',
            'format' => 'boolean',
        ],
                        ],
                    ]); ?>

                    <hr/>

                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> ' . 'Delete', ['delete', 'id' => $model->id],
                    [
                    'class' => 'btn btn-danger',
                    'data-confirm' => '' . 'Are you sure to delete this item?' . '',
                    'data-method' => 'post',
                    ]); ?>
                    <?php $this->endBlock(); ?>


                    <div id="map_canvas"></div>
                    
<?php $this->beginBlock('HargaMaterials'); ?>
<div style='position: relative'><div style='position:absolute; right: 0px; top: 0px;'>
  <?= Html::a(
                                    '<span class="glyphicon glyphicon-list"></span> ' . 'Semua Data' . ' Harga Materials',
                                    ['harga-material/index'],
                                    ['class'=>'btn text-muted btn-xs']
                                ) ?>
  <?= Html::a(
                                    '<span class="glyphicon glyphicon-plus"></span> ' . 'Tambah Baru' . ' Harga Material',
                                    ['harga-material/create', 'HargaMaterial' => ['Array' => $model->id]],
                                    ['class'=>'btn btn-success btn-xs']
                                ); ?>
</div></div><?php Pjax::begin(['id'=>'pjax-HargaMaterials', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-HargaMaterials ul.pagination a, th a', 'clientOptions' => ['pjax:success'=>'function(){alert("defrindr")}']]) ?>
<?= '<div class="table-responsive">'
 .                     \yii\grid\GridView::widget([
                        'layout' => '{summary}{pager}<br/>{items}{pager}',
                        'dataProvider' => new \yii\data\ActiveDataProvider([
                            'query' => $model->getHargaMaterials(),
                            'pagination' => [
                                'pageSize' => 20,
                                'pageParam'=>'page-hargamaterials',
                            ]
                        ]),
                        'pager'        => [
                            'class'          => \app\components\annex\LinkPager::className(),
                            'firstPageLabel' => 'First',
                            'lastPageLabel'  => 'Last'
                        ],
                        'columns' => [
                     [
                        'class'      => 'yii\grid\ActionColumn',
                        'template'   => '{view} {update}',
                        'contentOptions' => ['nowrap'=>'nowrap'],
                        'urlCreator' => function ($action, $model, $key, $index) {
                            // using the column name as key, not mapping to 'id' like the standard generator
                            $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                            $params[0] = 'harga-material' . '/' . $action;
                            $params['HargaMaterial'] = ['id_supplier' => $model->primaryKey()[0]];
                            return $params;
                        },
                        'buttons'    => [
                            
                        ],
                        'controller' => 'harga-material'
                    ],
                    // modified by Defri Indra
                    [
                        'class' => yii\grid\DataColumn::className(),
                        'attribute' => 'id_provinsi',
                        'value' => function ($model) {
                            if ($rel = $model->provinsi) {
                                return $rel->nama;
                            } else {
                                return '';
                            }
                        },
                        'format' => 'raw',
                    ],
                    // modified by Defri Indra
                    [
                        'class' => yii\grid\DataColumn::className(),
                        'attribute' => 'id_kota',
                        'value' => function ($model) {
                            if ($rel = $model->kota) {
                                return $rel->nama;
                            } else {
                                return '';
                            }
                        },
                        'format' => 'raw',
                    ],
                    // modified by Defri Indra
                    [
                        'class' => yii\grid\DataColumn::className(),
                        'attribute' => 'id_material',
                        'value' => function ($model) {
                            if ($rel = $model->material) {
                                return $rel->nama;
                            } else {
                                return '';
                            }
                        },
                        'format' => 'raw',
                    ],
        [
            'attribute' => 'harga',
            'format' => 'rp',
        ],
]
                    ])
 . '</div>' ?>
<?php Pjax::end() ?>
<?php $this->endBlock() ?>


                    <?= Tabs::widget(
                    [
                        'id' => 'relation-tabs',
                        'encodeLabels' => false,
                        'items' => [ 
                                                [
                        'label'   => '<b class=""># '.$model->id.'</b>',
                        'content' => $this->blocks['app\models\Supplier'],
                        'active'  => true,
                    ],                        [
                            'content' => $this->blocks['HargaMaterials'],
                            'label'   => '<small>Harga Materials <span class="badge badge-default">'.count($model->getHargaMaterials()->asArray()->all()).'</span></small>',
                            'active'  => false,
                        ],
                        ]
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

// if($model->coordinate!=null){
//     $coordinate = json_decode($model->coordinate);
//     $model->latitude = $coordinate->latitude;
//     $model->longitude = $coordinate->longitude;
// }
$lat = ($model->latitude) ? $model->latitude : 0;
$long = ($model->longitude) ? $model->longitude : 0;

$js = <<<JS
$(function() {
    let lat = $lat,
    lng = $long,
    latlng = new google.maps.LatLng(lat, lng);
    let mapOptions = {
        center: new google.maps.LatLng(lat, lng),
        zoom: 10,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        panControl: true,
        panControlOptions: {
            position: google.maps.ControlPosition.TOP_RIGHT
        },
        zoomControl: true,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.LARGE,
            position: google.maps.ControlPosition.TOP_left
        }
    },
    map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions),
    marker = new google.maps.Marker({
        position: latlng,
        map: map,
    });
});
JS;

$this->registerJs($js);
