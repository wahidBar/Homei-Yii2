<?php

namespace app\controllers;

/**
 * This is the class for controller "ApprovalSebelumPekerjaanController".
 * Modified by Defri Indra
 */
class ApprovalSebelumPekerjaanController extends \app\controllers\base\ApprovalSebelumPekerjaanController
{
    // fungsi untuk menanggapi revisi
    public function actionDilakukanRevisi($id)
    {
        $model = $this->findModel($id);
        $old_foto = $model->foto_material;
        if ($model->status != \app\models\ApprovalSebelumPekerjaan::STATUS_REJECTED) {
            toastError("Status revisi tidak sesuai");
            return $this->redirect(['proyek/view', 'id' => $model->id_proyek]);
        }

        if ($model->load($_POST)) {

            // upload image
            $image = \yii\web\UploadedFile::getInstance($model, 'foto_material');
            if ($image == null) {
                $model->foto_material = $old_foto;
            } else {
                $response = $this->uploadFile($image, $model->getUploadedPath());
                if ($response->success == false) {
                    toastError("Foto Material gagal diupload");
                    goto end;
                }

                $model->foto_material = $response->filename;
            }

            $model->revisi = null;
            if ($model->validate()) {
                $model->status = \app\models\ApprovalSebelumPekerjaan::STATUS_PENDING;
                $model->save();

                \app\components\Notif::log(
                    $model->proyek->id_user,
                    'Revisi Approval Proyek ' . $model->proyek->nama_proyek,
                    'Approval Proyek ' . $model->proyek->nama_proyek . ' telah diperbaharui',
                    [
                        'controller' => '/home/proyek-saya/detail-proyek',
                        'android_route' => 'app-proyek-detail',
                        'params' => [
                            'id' => $model->proyek->kode_unik
                        ],
                    ]
                );

                \app\models\LogApproval::copy($model, "Dilakukan revisi");
                return $this->redirect(['proyek/view', 'id' => $model->id_proyek]);
            }
            toastError(
                \app\components\Constant::flattenError(
                    $model->getErrors()
                )
            );
        }

        end:
        return $this->render('dilakukan-revisi', [
            'model' => $model,
        ]);
    }
}
