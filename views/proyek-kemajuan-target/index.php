<?php

/**
 * Autogenerated From GII
 * modified by Defri Indra M
 * 2021
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\ProyekKemajuanTarget $searchModel
 */

$this->title = 'Proyek Kemajuan Target';
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= Html::a('<i class="fa fa-plus"></i> Tambah Baru', ['create'], ['class' => 'btn btn-success']) ?>
</p>


<?php \yii\widgets\Pjax::begin(['id' => 'pjax-main', 'enableReplaceState' => false, 'linkSelector' => '#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success' => 'function(){alert("yo")}']]) ?>

<?= GridView::widget([
    'layout' => '{summary}{pager}{items}{pager}',
    'dataProvider' => $dataProvider,
    'pager'        => [
        'class'          => app\components\annex\LinkPager::className(),
        'firstPageLabel' => 'First',
        'lastPageLabel'  => 'Last'
    ],
    'filterModel' => $searchModel,
    'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
    'headerRowOptions' => ['class' => 'x'],
    'columns' => [

        \app\components\ActionButton::getButtons(),

        // modified by Defri Indra
        [
            'class' => yii\grid\DataColumn::className(),
            'attribute' => 'id_proyek',
            'value' => function ($model) {
                if ($rel = $model->proyek) {
                    return $rel->id;
                } else {
                    return '';
                }
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'kode_proyek',
            'format' => 'text',
        ],
        [
            'attribute' => 'nama_target',
            'format' => 'text',
        ],
        [
            'attribute' => 'nilai_target',
            'format' => 'text',
        ],
        [
            'attribute' => 'jumlah_target',
            'format' => 'text',
        ],
    ],
]); ?>
</div>
<?php \yii\widgets\Pjax::end() ?>