<?php

use dmstr\helpers\Html;
use yii\widgets\DetailView;
?>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'attribute' => 'nama_kontraktor',
            'format' => 'text',
        ],
        [
            'attribute' => 'alamat',
            'format' => 'html',
        ],
        [
            'attribute' => 'telepon',
            'format' => 'text',
        ],
        // [
        //     'attribute' => 'created_at',
        //     'format' => 'iddate',
        // ],
        // [
        //     'attribute' => 'updated_at',
        //     'format' => 'iddate',
        // ],
        // [
        //     'attribute' => 'deleted_at',
        //     'format' => 'iddate',
        // ],
        // // modified by Defri Indra
        // [
        //     'format' => 'html',
        //     'attribute' => 'created_by',
        //     'value' => ($model->createdBy ? $model->createdBy->name : '<span class="label label-warning">?</span>'),
        // ],
        // // modified by Defri Indra
        // [
        //     'format' => 'html',
        //     'attribute' => 'updated_by',
        //     'value' => ($model->updatedBy ? $model->updatedBy->name : '<span class="label label-warning">?</span>'),
        // ],
        // // modified by Defri Indra
        // [
        //     'format' => 'html',
        //     'attribute' => 'deleted_by',
        //     'value' => ($model->deletedBy ? $model->deletedBy->name : '<span class="label label-warning">?</span>'),
        // ],
        // [
        //     'attribute' => 'flag',
        //     'format' => 'boolean',
        // ],
    ],
]); ?>

<hr />

<?= Html::a(
    '<span class="glyphicon glyphicon-trash"></span> ' . 'Delete',
    ['delete', 'id' => $model->id],
    [
        'class' => 'btn btn-danger',
        'data-confirm' => '' . 'Are you sure to delete this item?' . '',
        'data-method' => 'post',
    ]
); ?>