<?php

namespace app\controllers\api\v1\kontraktor;

use app\components\Constant;
use yii\web\HttpException;

/**
 * This is the class for REST controller "ProyekKemajuanTargetController".
 * Modified by Defri Indra
 */

class ProyekTargetController extends \app\controllers\api\v1\kontraktor\BaseController
{
    public $modelClass = 'app\models\ProyekKemajuanTarget';

    const ALLOWED_ROLES = [
        \app\components\Constant::ROLE_TUKANG_SAMEDAY,
        \app\components\Constant::ROLE_KONTRAKTOR,
    ];

    public function actionIndex($id_project)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $query = $this->modelClass::showAtIndex();
        return $query->all();
    }

    public function actionUpdateTarget($id_project)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $model = \app\models\Proyek::findOne([
            "id" => $project->id
        ]);

        $dari = $model->tanggal_awal_kontrak;
        $akhir = $model->tanggal_akhir_kontrak;
        $sampai = date('Y-m-d', strtotime('+7 day', strtotime($akhir)));
        $daftar_tanggal = \app\components\Tanggal::dateRange($dari, $sampai, '+7 day');
        $jumlah_minggu = 1;
        foreach ($daftar_tanggal as $tanggal) {
            $check = $this->modelClass::findOne([
                "nama_target" => "Minggu-" . $jumlah_minggu,
                "id_proyek" => $model->id,
            ]);
            if ($check != null) continue;
            $target = new $this->modelClass;
            $target->scenario = $target::SCENARIO_CREATE;
            $target->id_proyek = $model->id;
            $target->kode_proyek = $model->kode_unik;
            $next_week = date('Y-m-d', strtotime('+7 day', strtotime($tanggal)));
            $target->tanggal_awal = $tanggal;
            $target->tanggal_akhir = $next_week;
            $target->nama_target = "Minggu-" . $jumlah_minggu;
            $target->nilai_target = 0;
            $target->jumlah_target = 0;
            $jumlah_minggu++;

            if ($target->validate()) {
                $target->save();
            } else {
                $errors = $target->errors;
            }
        }

        $transaction = \Yii::$app->db->beginTransaction();

        try {
            if ($_POST) {
                $total_nilai = 0;
                foreach ($_POST as $key => $input) {
                    $modified = $this->modelClass::findOne(['id' => $key, 'id_proyek' => $model->id]);
                    if ($modified == null) throw new HttpException(400);
                    $total_nilai += floatval($input['nilai_target']);

                    $modified->nilai_target = $input['nilai_target'];
                    $modified->jumlah_target = $total_nilai;
                    if ($modified->validate() == false) {
                        $errors = $modified->errors;
                        throw new HttpException(400, Constant::flattenError($errors));
                    }
                    $modified->save();
                }

                $transaction->commit();
                return ['success' => true, 'message' => 'Data berhasil disimpan.'];
            }
        } catch (\Exception $e) {
            if ($transaction->isActive) $transaction->rollBack();
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? 'Internal Server Error');
        }

        if ($transaction->isActive) $transaction->rollBack();

        throw new HttpException(400);
    }
}
