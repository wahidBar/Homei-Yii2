<?php

namespace app\controllers;

use yii\web\UploadedFile;

/**
 * This is the class for controller "ProyekController".
 * Modified by Defri Indra
 */
class ProyekController extends \app\controllers\base\ProyekController
{

    public function actionPengajuanSelesai($id)
    {
        $model = $this->findModel($id);
        $model->scenario = $model::SCENARIO_PENGAJUAN_SELESAI;

        $model->status = $model::STATUS_PENGAJUAN_SELESAI;
        if ($model->validate() == false) {
            toastError("Validasi gagal");
            return $this->redirect(['view', 'id' => $id]);
        }
        $model->save();
        \app\components\Notif::log(
            $model->id_user,
            "Admin Telah Meminta Pengajuan Selesai untuk Proyek Anda",
            "Hallo {$model->user->name}, Admin telah meminta pengajuan selesai untuk proyek Anda.",
            [
                "controller" => "home/proyek-saya/detail-proyek",
                "android_route" => null,
                "params" => [
                    "id" => $model->kode_unik
                ]
            ]
        );
        toastSuccess("Pengajuan Selesai Berhasil");
        return $this->redirect(['view', 'id' => $id]);
        // end:
        // return $this->render('form_survey', $model->render());
    }
    // public function actionEditNilaiDp($id)
    // {
    //     $model = $this->findModel($id);
    //     if ($model->nilai_dp != null) {
    //         $this->messageValidationFailed();
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }
    //     $model->scenario = $model::SCENARIO_DP;

    //     try {
    //         if ($model->load($_POST)) :
    //             $model->jenis_pembayaran = 2;
    //             $dp = $model->nilai_kontrak - ($model->nilai_kontrak * $model->dp_pembayaran / 100);
    //             $model->nilai_dp = $model->nilai_kontrak - $dp;
    //             if ($model->validate()) :
    //                 $model->save();
    //                 $this->messageCreateSuccess();
    //                 return $this->redirect(['view', 'id' => $model->id]);
    //             endif;
    //             $this->messageValidationFailed();
    //         elseif (!\Yii::$app->request->isPost) :
    //             $model->load($_GET);
    //         endif;
    //     } catch (\Exception $e) {
    //         $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
    //         $model->addError('_exception', $msg);
    //     }

    //     end:
    //     return $this->render('edit-nilai-dp', $model->render());
    // }
}
