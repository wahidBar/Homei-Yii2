<?php

namespace app\models;

use Yii;
use \app\models\base\LogApproval as BaseLogApproval;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tlog_approval_sebelum_pekerjaan".
 * Modified by Defri Indra M
 */
class LogApproval extends BaseLogApproval
{
    // function copy data from actual data to log data
    public static function copy($actual, $deskripsi_log = "")
    {
        $model = new LogApproval();
        $model->id_approval = $actual->id;
        $model->id_proyek = $actual->id_proyek;
        $model->id_progress = $actual->id_progress;
        $model->nama_proyek = $actual->proyek->nama_proyek;
        $model->nama_progress = $actual->nama_progress;
        $model->foto_material = $actual->foto_material;
        $model->keterangan = $actual->keterangan;
        $model->status = $actual->status;
        $model->revisi = $actual->revisi;
        $model->deskripsi_log = $deskripsi_log;
        $model->created_by = Yii::$app->user->id;
        $model->created_at = date('Y-m-d H:i:s');
        $model->save();
    }
}
