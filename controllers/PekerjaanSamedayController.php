<?php

namespace app\controllers;

use app\components\Constant;
use app\models\PekerjaanSameday;
use app\models\SiteSetting;
use Yii;
use yii\web\UploadedFile;

/**
 * This is the class for controller "PekerjaanSamedayController".
 * Modified by Defri Indra
 */
class PekerjaanSamedayController extends \app\controllers\base\PekerjaanSamedayController
{

    public function actionUpdateRencanaSurvey($id)
    {
        $model = $this->findModel($id);
        $model->scenario = $model::SCENARIO_TANGGAL_SURVEY;
        if ($model->status != PekerjaanSameday::STATUS_PELENGKAPAN_DATA && \app\components\Constant::getUser()->role_id != 1) {
            toastError("Status Bukan Pelengkapan Data");
            return $this->redirect(['index']);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->status = PekerjaanSameday::STATUS_SURVEY_LOKASI;
            if ($model->validate() == false) {
                toastError("Validasi gagal");
                goto end;
            }
            $model->save();
            \app\components\Notif::log(
                $model->id_pelanggan,
                "Admin Telah Mengisi Tanggal Survey",
                "Hallo {$model->pelanggan->name}, Admin telah mengisi tanggal survey untuk proyek Anda.",
                [
                    "controller" => "home/cari-tukang/view",
                    "android_route" => "app-sameday-detail",
                    "params" => [
                        "id" => $model->kode_unik
                    ]
                ]
            );
            toastSuccess("Berhasil menambahkan rencana survey");
            return $this->redirect(['view', 'id' => $id]);
        }

        end:
        return $this->render('form_survey', $model->render());
    }

    public function actionLayanan($id)
    {
        $model = $this->findModel($id);
        $pengaturan = SiteSetting::find()->one();
        $model->scenario = $model::SCENARIO_PENGISIAN_LAYANAN;
        if ($model->status != PekerjaanSameday::STATUS_SURVEY_LOKASI && \app\components\Constant::getUser()->role_id != 1) {
            toastError("Status Bukan Survey Lokasi");
            return $this->redirect(['index']);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->biaya = str_replace(",", "", $model->biaya);
            $model->nominal_dp = str_replace(",", "", $model->nominal_dp);
            $model->deadline_pembayaran_dp = date("Y-m-d H:i:s", strtotime("+$pengaturan->batas_pembayaran minutes"));
            $model->catatan_revisi = null;

            if ($model->nominal_dp >= $model->biaya) {
                toastError("Nilai DP tidak boleh melebihi nilai biaya");
                goto end;
            }

            $model->status = PekerjaanSameday::STATUS_PEMBAYARAN_DP;
            if ($model->validate() == false) {
                toastError("Validasi gagal");
                goto end;
            }

            $model->save();
            \app\components\Notif::log(
                $model->id_pelanggan,
                "Admin Telah Mengisi Layanan Tukang",
                "Hallo {$model->pelanggan->name}, Admin telah mengisi layanan tukang untuk proyek Anda.",
                [
                    "controller" => "home/cari-tukang/view",
                    "android_route" => "app-sameday-detail",
                    "params" => [
                        "id" => $model->kode_unik
                    ]
                ]
            );

            // notifikasi ke tukang bahwa ada pekerjaan sameday baru
            \app\components\Notif::log(
                $model->id_tukang,
                "Ada Pekerjaan Sameday Baru",
                "Ada pekerjaan sameday baru yang harus Anda kerjakan.",
                [
                    "controller" => "home/cari-tukang/view",
                    "android_route" => "app-sameday-detail",
                    "params" => [
                        "id" => $model->id
                    ]
                ]
            );
            toastSuccess("Berhasil menambahkan layanan");
            return $this->redirect(['view', 'id' => $id]);
        }

        end:
        return $this->render('form_layanan', $model->render());
    }

    public function actionKonfirmasiDp($id)
    {
        $model = $this->findModel($id);
        if ($model->status != PekerjaanSameday::STATUS_PEMBAYARAN_DP) {
            toastError("Status tidak sesuai, anda tidak dapat menjalankan aksi ini.");
            return $this->redirect(['view', 'id' => $id]);
        }

        try {
            $model->status = PekerjaanSameday::STATUS_PENGERJAAN;
            if ($model->validate()) {
                $model->save();
                \app\components\Notif::log(
                    $model->id_pelanggan,
                    "Pembayaran DP Sameday telah di konfirmasi admin",
                    "Hallo {$model->pelanggan->name}, Pembayaran DP anda untuk pekerjaan sameday telah dikonfirmasi oleh admin.",
                    [
                        "controller" => "home/cari-tukang/view",
                        "android_route" => "app-sameday-detail",
                        "params" => [
                            "id" => $model->kode_unik
                        ]
                    ]
                );

                \app\components\Notif::log(
                    $model->id_tukang,
                    "Ada pekerjaan baru untuk anda",
                    "Hallo {$model->pelanggan->name}, ada perkerjaan sameday baru untuk anda.",
                    [
                        "controller" => "pekerjaan-sameday/view",
                        "android_route" => "app-sameday-detail",
                        "params" => [
                            "id" => $model->id
                        ]
                    ]
                );

                toastSuccess("Pembayaran DP berhasil dikonfirmasi");
            }
        } catch (\Throwable $th) {
            toastError($th->getMessage() ?? "Telah terjadi kesalahan");
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionTolakDp($id)
    {
        $model = $this->findModel($id);
        $model->scenario = PekerjaanSameday::SCENARIO_TOLAK_DP;
        $pengaturan = SiteSetting::find()->one();
        if ($model->status != PekerjaanSameday::STATUS_PEMBAYARAN_DP) {
            toastError("Status tidak sesuai, anda tidak dapat menjalankan aksi ini.");
            return $this->redirect(['view', 'id' => $id]);
        }

        try {
            if ($model->load(Yii::$app->request->post())) {
                $model->deadline_pembayaran_dp = date("Y-m-d H:i:s", strtotime("+$pengaturan->batas_pembayaran minutes"));
                $model->status = PekerjaanSameday::STATUS_PEMBAYARAN_DP;
                if ($model->validate()) {
                    $model->save();
                    \app\components\Notif::log(
                        $model->id_pelanggan,
                        "Admin Meminta Revisi Pembayaran DP Sameday",
                        "Hallo {$model->pelanggan->name}, Admin meminta revisi pembayaran dp untuk proyek sameday Anda.",
                        [
                            "controller" => "home/cari-tukang/view",
                            "android_route" => "app-sameday-detail",
                            "params" => [
                                "id" => $model->kode_unik
                            ]
                        ]
                    );

                    toastSuccess("Pembayaran DP berhasil ditolak, menunggu user mengupload bukti baru.");
                    return $this->redirect(['view', 'id' => $id]);
                }
                toastError(Constant::flattenError($model->getErrors()));
            }
        } catch (\Throwable $th) {
            toastError($th->getMessage() ?? "Telah terjadi kesalahan");
            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->render('form_tolak', $model->render());
    }

    public function actionPengajuan($id)
    {
        $model = $this->findModel($id);
        $old_image = $model->foto_pengerjaan;
        $model->scenario = PekerjaanSameday::SCENARIO_PENGAJUAN;
        if (in_array($model->status, [PekerjaanSameday::STATUS_PENGERJAAN, PekerjaanSameday::STATUS_DIAJUKAN]) == false) {
            toastError("Status tidak sesuai, anda tidak dapat menjalankan aksi ini.");
            return $this->redirect(['view', 'id' => $id]);
        }

        try {
            if ($model->load(Yii::$app->request->post())) {
                $instance = UploadedFile::getInstance($model, "foto_pengerjaan");
                if ($old_image != null) {
                    if ($instance) {
                        $response = $this->uploadImage($instance, "sameday");
                        if ($response->success == false) {
                            toastError("Gagal mengunggah gambar");
                            goto end;
                        }
                        $model->foto_pengerjaan = $response->filename;
                    } else {
                        $model->foto_pengerjaan = $old_image;
                    }
                } else {
                    $response = $this->uploadImage($instance, "sameday");
                    if ($response->success == false) {
                        toastError("Gagal mengunggah gambar");
                        goto end;
                    }

                    $model->foto_pengerjaan = $response->filename;
                }

                $model->status = PekerjaanSameday::STATUS_DIAJUKAN;
                if ($model->validate()) {
                    $model->catatan_revisi = null;
                    $model->save();
                    \app\components\Notif::log(
                        $model->id_pelanggan,
                        "Pekerja Telah Mengajukan Report Pekerjaan",
                        "Hallo {$model->pelanggan->name}, anda mempunyai report baru di pekerjaan sameday anda.",
                        [
                            "controller" => "home/cari-tukang/view",
                            "android_route" => "app-sameday-detail",
                            "params" => [
                                "id" => $model->kode_unik
                            ]
                        ]
                    );

                    toastSuccess("Pengerjaan berhasil diajukan. Menunggu Response Owner");
                    return $this->redirect(['view', 'id' => $id]);
                }
                toastError(Constant::flattenError($model->getErrors()));
            }
        } catch (\Throwable $th) {
            toastError($th->getMessage() ?? "Telah terjadi kesalahan");
            return $this->redirect(['view', 'id' => $id]);
        }

        end:
        return $this->render('form_pengajuan', $model->render());
    }

    /**
     * actionKonfirmasiPembayaranTotal
     * @param $id
     * @return string|\yii\web\Response
     * @throws \Throwable
     */
    public function actionKonfirmasiPembayaranTotal($id)
    {
        $model = $this->findModel($id);
        if ($model->status != PekerjaanSameday::STATUS_PEMBAYARAN && $model->revisi_pembayaran) {
            toastError("Status tidak sesuai, anda tidak dapat menjalankan aksi ini.");
            return $this->redirect(['view', 'id' => $id]);
        }

        try {
            $model->status = PekerjaanSameday::STATUS_SELESAI;
            $model->save();

            // notifikasi ke pelanggan
            \app\components\Notif::log(
                $model->id_pelanggan,
                "Pembayaran anda telah di konfirmasi",
                "Hallo {$model->pelanggan->name}, pembayaran pekerjaan sameday anda telah selesai.",
                [
                    "controller" => "home/cari-tukang/view",
                    "android_route" => "app-sameday-detail",
                    "params" => [
                        "id" => $model->kode_unik
                    ]
                ]
            );

            // notifikasi ke tukang
            \app\components\Notif::log(
                $model->id_tukang,
                "Pekerjaan telah selesai",
                "Hallo {$model->tukang->name}, pembayaran pekerjaan sameday untuk anda telah selesai.",
                [
                    "controller" => "home/cari-tukang/view",
                    "android_route" => "app-sameday-detail",
                    "params" => [
                        "id" => $model->kode_unik
                    ]
                ]
            );

            toastSuccess("Pekerjaan berhasil dikonfirmasi");
            return $this->redirect(['view', 'id' => $id]);
        } catch (\Throwable $th) {
            toastError($th->getMessage() ?? "Telah terjadi kesalahan");
            return $this->redirect(['view', 'id' => $id]);
        }
    }

    public function actionRevisiPembayaranTotal($id)
    {
        $model = $this->findModel($id);
        $model->scenario = PekerjaanSameday::SCENARIO_TOLAK_PEMBAYARAN;
        if ($model->status != PekerjaanSameday::STATUS_PEMBAYARAN) {
            toastError("Status tidak sesuai, anda tidak dapat menjalankan aksi ini.");
            return $this->redirect(['view', 'id' => $id]);
        }

        try {
            if ($model->load(Yii::$app->request->post())) {
                $model->status = PekerjaanSameday::STATUS_PEMBAYARAN;
                if ($model->validate()) {
                    $model->save();
                    \app\components\Notif::log(
                        $model->id_pelanggan,
                        "Admin Meminta Revisi Pembayaran Sameday",
                        "Hallo {$model->pelanggan->name}, Admin meminta revisi pembayaran untuk proyek sameday Anda.",
                        [
                            "controller" => "home/cari-tukang/view",
                            "android_route" => "app-sameday-detail",
                            "params" => [
                                "id" => $model->kode_unik
                            ]
                        ]
                    );

                    toastSuccess("Pembayaran berhasil ditolak, menunggu user mengupload bukti baru.");
                    return $this->redirect(['view', 'id' => $id]);
                }
                toastError(Constant::flattenError($model->getErrors()));
            }
        } catch (\Throwable $th) {
            toastError($th->getMessage() ?? "Telah terjadi kesalahan");
            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->render('form_tolak_pembayaran', $model->render());
    }
}
