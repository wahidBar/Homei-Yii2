<?php

namespace app\controllers;

use app\models\ProyekKemajuanTarget;
use Yii;
use yii\web\HttpException;

/**
 * This is the class for controller "ProyekKemajuanTargetController".
 * Modified by Defri Indra
 */
class ProyekKemajuanTargetController extends \app\controllers\base\ProyekKemajuanTargetController
{
    public function actionUpdateTarget($id)
    {
        $model = $this->findModelProyek($id);

        $dari = $model->tanggal_awal_kontrak;
        $akhir = $model->tanggal_akhir_kontrak;
        $sampai = date('Y-m-d', strtotime('+7 day', strtotime($akhir)));
        $daftar_tanggal = \app\components\Tanggal::dateRange($dari, $sampai, '+7 day');
        $jumlah_tanggal = count($daftar_tanggal);
        $jumlah_minggu = 1;
        foreach ($daftar_tanggal as $tanggal) {
            $check = ProyekKemajuanTarget::findOne([
                "nama_target" => "Minggu-" . $jumlah_minggu,
                "id_proyek" => $model->id,
            ]);
            if ($check != null) continue;
            $target = new ProyekKemajuanTarget();
            $target->scenario = $target::SCENARIO_CREATE;
            $target->id_proyek = $model->id;
            $target->kode_proyek = $model->kode_unik;
            $next_week = date('Y-m-d', strtotime('+7 day', strtotime($tanggal)));
            // $awal =  $model->getRealisasiByRangeDate($tanggal, $next_week);
            // $progress_perminggu =  number_format($awal, 2);
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

        $data = $model->getTargetProgress()->all();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($_POST) {
                $total_nilai = 0;
                foreach ($_POST['ProyekKemajuanTarget'] as $key => $input) {
                    $modified = ProyekKemajuanTarget::findOne(['id' => $key, 'id_proyek' => $model->id]);
                    if ($modified == null) throw new HttpException(400);
                    $total_nilai += floatval($input['nilai_target']);

                    $modified->nilai_target = $input['nilai_target'];
                    $modified->jumlah_target = $total_nilai;
                    if ($modified->validate() == false) goto end;
                    $modified->save();
                }

                $transaction->commit();
                toastSuccess("Berhasil diupdate");
                return $this->redirect(['/proyek/view', 'id' => $model->id]);
            }
        } catch (\Exception $e) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }
            toastError($e->getMessage());
        }

        end:
        if ($transaction->isActive) {
            $transaction->rollBack();
        }
        return $this->render('_form_update', [
            'model' => $data
        ]);
    }



    /**
     * Finds the Proyek model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Proyek the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModelProyek($id)
    {
        if (($model = \app\models\Proyek::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\HttpException(404, 'The requested page does not exist.');
        }
    }
}
