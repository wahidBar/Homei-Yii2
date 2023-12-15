<?php

namespace app\controllers;

use app\components\Constant;
use app\models\IsianLanjutanRuangan;
use app\models\MasterKategoriKeuanganMasuk;
use app\models\Notification;
use app\models\Proyek;
use app\models\ProyekKemajuanTarget;
use app\models\ProyekKeuanganMasuk;
use Yii;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * This is the class for controller "IsianLanjutanController".
 * Modified by Defri Indra
 */
class IsianLanjutanController extends \app\controllers\base\IsianLanjutanController
{
    use \app\components\UploadFile;
    /**
     * 
     * 0=default,
     * 1=user isi,
     * 2=admin survey, 
     * 3=admin isi penawaran, 
     * 4=deal user, 
     * 5=rencana pembangunan
     * 6=deal rencana pembangunan
     * 7=upload tor
     * 8=setuju tor (jadi proyek)
     * 8=tor butuh perubahan
     * 7=tolak
     */

    public function actionRencanaSurvey($id)
    {
        $model = $this->findModel($id);
        $model->scenario = $model::SCENARIO_SURVEY;
        if ($model->status != $model::STATUS_USER_ISI) {
            toastError("Akses ditolak, belum memenuhi persyaratan");
            return $this->redirect(['isian-lanjutan/view', 'id' => $model->id]);
        }
        if ($model->kode_unik == null) {
            $model->kode_unik = Yii::$app->security->generateRandomString(30);
        }
        try {
            if ($model->load($_POST)) :
                if ($model->validate()) :
                    $model->status = $model::STATUS_ADMIN_SURVEY;

                    \app\components\Notif::log(
                        $model->id_user,
                        "Admin Telah Mengisi Rencana Survey",
                        "Hallo {$model->user->name}, Admin telah mengisi rencana survey untuk proyek Anda.",
                        [
                            "controller" => "home/form-rencana-pembangunan/view",
                            "android_route" => "app-form-rencana-pembangunan-informasi-pelanggan",
                            "params" => [
                                "id" => $model->kode_unik
                            ]
                        ]
                    );

                    $model->save();
                    $this->messageUpdateSuccess();
                    return $this->redirect(['view', 'id' => $model->id]);
                endif;
                $this->messageValidationFailed();
            endif;
            goto end;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->render('rencana-survey', $model->render());
    }

    public function actionRencanaPembangunan($id)
    {
        $model = $this->findModel($id);
        $model->scenario = $model::SCENARIO_BANGUN;
        if ($model->status != $model::STATUS_SETUJU_TOR) {
            toastError("Akses ditolak, user belum setujui TOR");
            return $this->redirect(['isian-lanjutan/view', 'id' => $model->id]);
        }
        if ($model->dp_pembayaran == null) {
            toastError("Akses ditolak, DP belum diatur");
            return $this->redirect(['isian-lanjutan/view', 'id' => $model->id]);
        }
        try {
            if ($model->load($_POST)) :
                if (strtotime($model->rencana_pembangunan) < time()) {
                    toastError(Yii::t("cruds", "Tanggal Pembangunan Harus lebih besar dari tanggal sekarang."));
                    return $this->redirect(['isian-lanjutan/view', 'id' => $model->id]);
                }

                if ($model->validate()) :
                    $model->status = $model::STATUS_RENCANA_PEMBANGUNAN;

                    \app\components\Notif::log(
                        $model->id_user,
                        "Admin Telah Mengisi Rencana Pembangunan",
                        "Hallo {$model->user->name}, Admin telah mengisi rencana pembangunan untuk proyek Anda.",
                        [
                            "controller" => "home/form-rencana-pembangunan/view",
                            "android_route" => "app-form-rencana-pembangunan-informasi-pelanggan",
                            "params" => [
                                "id" => $model->kode_unik
                            ]
                        ]
                    );

                    $model->save();
                    $this->messageUpdateSuccess();
                    return $this->redirect(['view', 'id' => $model->id]);
                endif;
                $this->messageValidationFailed();
            endif;
            goto end;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->render('rencana-bangun', $model->render());
    }

    public function actionDealPembangunan($id)
    {
        date_default_timezone_set('Asia/Jakarta');
        $model = $this->findModel($id);
        if ($model->status != $model::STATUS_RENCANA_PEMBANGUNAN) {
            toastError("Akses ditolak, belum memenuhi persyaratan");
            return $this->redirect(['isian-lanjutan/view', 'id' => $model->id]);
        }
        $model->scenario = $model::SCENARIO_DEAL_RENCANA_PEMBANGUNAN;

        $transaction = Yii::$app->db->beginTransaction();

        $proyek = new Proyek;
        $proyek->scenario = $model::SCENARIO_CREATE;
        $proyek->kode_unik = Yii::$app->security->generateRandomString(30);
        $proyek->id_user = $model->id_user;
        $proyek->nama_proyek = $model->label;
        $proyek->deskripsi_proyek = $model->alamat_proyek;
        $proyek->nilai_kontrak = $model->penawaran->total_harga_penawaran;
        $proyek->total_pembayaran = $model->dp_pembayaran;
        $proyek->tanggal_awal_kontrak = $model->rencana_pembangunan;
        $proyek->tanggal_akhir_kontrak = date("Y-m-d H:i:s", strtotime($model->rencana_pembangunan . " +{$model->penawaran->estimasi_waktu} days"));
        $proyek->latitude_proyek = $model->latitude;
        $proyek->longitude_proyek = $model->longitude;
        $proyek->created_at = date("Y-m-d H:i:s");
        $proyek->updated_at = date("Y-m-d H:i:s");
        $proyek->created_by = Constant::getUser()->id;
        $proyek->updated_by = Constant::getUser()->id;

        if ($model->validate() && $proyek->validate()) :
            $model->id_proyek = $proyek->id;
            $model->kode_proyek = $proyek->kode_unik;
            $model->approval_dokumen_tor = 1;
            $model->alasan_tolak = null;
            $model->status = $model::STATUS_DEAL_RENCANA_PEMBANGUNAN;
            $proyek->save();

            // buat data keuangan masuk dari pembayaran dp di proyek ini

            $kategori = new MasterKategoriKeuanganMasuk();
            $kategori->id_proyek = $proyek->id;
            $kategori->nama_kategori = "Pembayaran DP";
            if ($kategori->validate() == false) {
                toastError("Gagal membuat kategori keuangan");
                return $this->redirect(['isian-lanjutan/view', 'id' => $model->id]);
            }

            $kategori->save();

            $keuangan = new ProyekKeuanganMasuk();
            $keuangan->id_proyek = $proyek->id;
            $keuangan->id_kategori = $kategori->id;
            $keuangan->item = "Pembayaran DP";
            $keuangan->tanggal = $model->tanggal_pembayaran;
            $keuangan->jumlah = $model->dp_pembayaran;
            $keuangan->keterangan = "Pembayaran DP untuk proyek {$model->label}";
            $keuangan->created_at = date("Y-m-d H:i:s");
            $keuangan->created_by = Constant::getUser()->id;

            if ($keuangan->validate() == false) {
                $transaction->rollBack();
                toastError("Gagal membuat data keuangan : " . Constant::flattenError($keuangan->getErrors()));
                return $this->redirect(['isian-lanjutan/view', 'id' => $model->id]);
            }

            $keuangan->save();

            \app\components\Notif::log(
                $model->id_user,
                "Admin Telah Menyetujui Rencana Pembangunan Anda",
                "Hallo {$model->user->name}, Admin telah menyetujui proyek Anda.",
                [
                    "controller" => "home/form-rencana-pembangunan/view",
                    "android_route" => "app-form-rencana-pembangunan-informasi-pelanggan",
                    "params" => [
                        "id" => $model->kode_unik
                    ]
                ]
            );

            $model->save();

            $dari = $proyek->tanggal_awal_kontrak;
            $akhir = $proyek->tanggal_akhir_kontrak;
            $sampai = date('Y-m-d', strtotime('+7 day', strtotime($akhir)));
            $daftar_tanggal = \app\components\Tanggal::dateRange($dari, $sampai, '+7 day');
            unset($daftar_tanggal[count($daftar_tanggal) - 1]); // remove last index
            $jumlah_tanggal = count($daftar_tanggal);
            // $progress_perminggu = array();
            $jumlah_minggu = 1;
            foreach ($daftar_tanggal as $tanggal) {
                $target = new ProyekKemajuanTarget();
                $target->scenario = $target::SCENARIO_CREATE;
                $target->id_proyek = $proyek->id;
                $target->kode_proyek = $proyek->kode_unik;
                $next_week = date('Y-m-d', strtotime('+7 day', strtotime($tanggal)));
                // $awal =  $proyek->getRealisasiByRangeDate($tanggal, $next_week);
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
                    $transaction->rollBack();
                }
            }
            $transaction->commit();
            $this->messageUpdateSuccess();
            return $this->redirect(['view', 'id' => $model->id]);
        endif;
        $this->messageValidationFailed();
        $transaction->rollBack();
        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionTolakPembangunan($id)
    {
        $model = $this->findModel($id);
        $model->scenario = $model::SCENARIO_REVISI_RENCANA_PEMBANGUNAN;

        try {
            if ($model->load($_POST)) :
                $model->status = $model::STATUS_REVISI_RENCANA_PEMBANGUNAN;
                if ($model->validate()) :

                    \app\components\Notif::log(
                        $model->id_user,
                        "Admin Telah Menolak Tanggal Rencana Pembangunan",
                        "Hallo {$model->user->name}, Admin telah menolak tanggal rencana pembangunan untuk proyek Anda.",
                        [
                            "controller" => "home/form-rencana-pembangunan/view",
                            "android_route" => "app-form-rencana-pembangunan-informasi-pelanggan",
                            "params" => [
                                "id" => $model->kode_unik
                            ]
                        ]
                    );

                    $model->save();
                    $this->messageCreateSuccess();
                    return $this->redirect(['view', 'id' => $model->id]);
                endif;
                $this->messageValidationFailed();
            // return $this->redirect(['view', 'id' => $model->id]);
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }
        end:
        return $this->render('tolak-dp', $model->render());
    }


    public function actionTermOfReference($id)
    {
        $model = $this->findModel($id);
        $old_path = $model->dokumen_tor;
        $model->scenario = $model::SCENARIO_UPLOAD_TOR;

        try {
            if ($model->load($_POST)) :
                $instance = UploadedFile::getInstance($model, 'dokumen_tor');
                if ($instance) {
                    $response = $this->uploadFile($instance, "rencana-pembangunan/dokumen_tor/{$model->kode_unik}");
                    if ($response->success == false) {
                        toastError("Dokumen gagal diunggah");
                        goto end;
                    }
                    $model->dokumen_tor = $response->filename;
                    $this->deleteOne($old_path);
                } else {
                    $model->dokumen_tor = $old_path;
                }
                $model->status = $model::STATUS_UPLOAD_TOR;
                $model->alasan_tolak = null;

                if ($model->validate()) :

                    \app\components\Notif::log(
                        $model->id_user,
                        "Admin Telah Mengisi Dokumen TOR",
                        "Hallo {$model->user->name}, Admin telah mengisi dokumen TOR untuk proyek Anda.",
                        [
                            "controller" => "home/form-rencana-pembangunan/view",
                            "android_route" => "app-form-rencana-pembangunan-informasi-pelanggan",
                            "params" => [
                                "id" => $model->kode_unik
                            ]
                        ]
                    );

                    $model->save();
                    $this->messageCreateSuccess();
                    return $this->redirect(['view', 'id' => $model->id]);
                endif;
                $this->messageValidationFailed();
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->render('dokumen_tor', $model->render());
    }

    // public function actionApproveTor($id)
    // {
    //     $model = $this->findModel($id);
    //     $model->scenario = $model::SCENARIO_APPROVE_TOR;

    //     if ($model->id_user != Constant::getUser()->id) {
    //         toastError(Yii::t("cruds", "Anda tidak diperbolehkan mengakses menu ini"));
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }

    //     $transaction = Yii::$app->db->beginTransaction();

    //     try {
    //         if ($model->load($_POST)) :
    //             $proyek = new Proyek();
    //             $proyek->kode_unik = Yii::$app->security->generateRandomString(30);
    //             $proyek->id_user = $model->id_user;
    //             $proyek->nama_proyek = $model->label;
    //             $proyek->deskripsi_proyek = $model->alamat_proyek;
    //             $proyek->nilai_kontrak = $model->penawaran->harga_penawaran;
    //             $proyek->tanggal_awal_kontrak = $model->rencana_pembangunan;
    //             $proyek->tanggal_akhir_kontrak = date("Y-m-d H:i:s", strtotime($model->rencana_pembangunan . " +{$model->penawaran->estimasi_waktu} days"));
    //             $proyek->latitude_proyek = $model->latitude;
    //             $proyek->longitude_proyek = $model->longitude;
    //             $proyek->created_at = date("Y-m-d H:i:s");
    //             $proyek->updated_at = date("Y-m-d H:i:s");
    //             $proyek->created_by = Constant::getUser()->id;
    //             $proyek->updated_by = Constant::getUser()->id;
    //             if ($proyek->validate()) {
    //                 $proyek->save();
    //             } else {
    //                 toastError("Telah terjadi kesalahan");
    //                 $transaction->rollBack();
    //                 return $this->redirect(['isian-lanjutan/view', 'id' => $model->id]);
    //             }

    //             $model->id_proyek = $proyek->id;
    //             $model->kode_proyek = Yii::$app->security->generateRandomString(30);
    //             $model->status = $model::STATUS_SETUJU_TOR;
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
    //     return $this->render('dokumen_tor', $model->render());
    // }

    public function actionEditNilaiDp($id)
    {
        $model = $this->findModel($id);
        // if ($model->dp_pembayaran != null) {
        //     $this->messageValidationFailed();
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }
        $model->scenario = $model::SCENARIO_DP;

        try {
            if ($model->load($_POST)) :
                $model->dp_pembayaran = str_replace(",", "", $model->dp_pembayaran);

                $dp = $model->dp_pembayaran;
                if ($dp > $model->penawaran->total_harga_penawaran) {
                    toastError("DP tidak boleh melebihi nilai penawaran");
                    return $this->redirect(['view', 'id' => $model->id]);
                }

                if ($model->validate()) :

                    \app\components\Notif::log(
                        $model->id_user,
                        "Admin Telah Mengisi Nilai DP",
                        "Hallo {$model->user->name}, Admin telah mengisi nilai DP untuk proyek Anda.",
                        [
                            "controller" => "home/form-rencana-pembangunan/view",
                            "android_route" => "app-form-rencana-pembangunan-informasi-pelanggan",
                            "params" => [
                                "id" => $model->kode_unik
                            ]
                        ]
                    );

                    $model->save();
                    $this->messageCreateSuccess();
                    return $this->redirect(['view', 'id' => $model->id]);
                endif;
                $this->messageValidationFailed();
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->render('edit-nilai-dp', $model->render());
    }

    public function actionKonfirmasiDp($id)
    {
        $model = $this->findModel($id);
        $status = $model->status_pembayaran;
        if ($status != 1) {
            $this->messageValidationFailed();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $model->scenario = $model::SCENARIO_KONFIRMASI;
        try {

            $model->status_pembayaran = 2;
            $model->alasan_tolak = "-";

            if ($model->validate()) :

                $notif = new Notification();

                $notif->scenario = $notif::SCENARIO_CREATE;
                $notif->user_id = $model->id_user;
                $notif->title = "Admin Telah Konfirmasi DP Anda";
                $notif->description = "Hallo {$model->user->name}, Admin telah mengonfirmasi DP untuk proyek Anda.";
                $notif->controller = "home/form-rencana-pembangunan/view";
                $notif->params = json_encode([
                    "id" => $model->kode_unik
                ]);
                $notif->save();
                $model->save();
                $this->messageCreateSuccess();
                return $this->redirect(['view', 'id' => $model->id]);
            endif;
            $this->messageValidationFailed();
            return $this->redirect(['view', 'id' => $model->id]);
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }
    }

    public function actionTolakDp($id)
    {
        $model = $this->findModel($id);
        $status = $model->status_pembayaran;
        if ($status != 1) {
            $this->messageValidationFailed();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $model->scenario = $model::SCENARIO_TOLAK_DP;

        try {
            if ($model->load($_POST)) :
                $model->status_pembayaran = 3;
                // $oldtotal = $model->total_pembayaran;
                // $hasil = $oldtotal - $model->dp_pembayaran;
                // $model->total_pembayaran = $hasil;
                $model->keterangan_pembayaran = null;
                if ($model->validate()) :

                    \app\components\Notif::log(
                        $model->id_user,
                        "Admin Telah Menolak DP Anda",
                        "Hallo {$model->user->name}, Admin telah menolak DP Anda.",
                        [
                            "controller" => "home/form-rencana-pembangunan/view",
                            "android_route" => "app-form-rencana-pembangunan-informasi-pelanggan",
                            "params" => [
                                "id" => $model->kode_unik
                            ]
                        ]
                    );

                    $model->save();
                    $this->messageCreateSuccess();
                    return $this->redirect(['view', 'id' => $model->id]);
                endif;
                $this->messageValidationFailed();
            // return $this->redirect(['view', 'id' => $model->id]);
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }
        end:
        return $this->render('tolak-dp', $model->render());
    }

    public function actionSaveAndCopy($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        $model->list_ruangan = $oldCategoryIDs = $list_ruangan = $model->getListRuangan();
        $transaction = Yii::$app->db->beginTransaction();
        $model->scenario = $model::SCENARIO_UPDATE;

        if ($model->load(Yii::$app->request->post())) {
            $model->budget = str_replace(",", "", $model->budget);
            if ($model->status == 0) {
                $model->status = 1;
            }

            // list_ruangan
            $list_ruangan = Yii::$app->request->post('IsianLanjutan')['list_ruangan'];
            if (is_array($list_ruangan) == false) $list_ruangan = [];
            $deletedCategoryIDs = array_diff($oldCategoryIDs, $list_ruangan);

            $model->id_satuan = 2; // set default satuan
            if ($model->validate()) :
                $model->luas_tanah = $model->panjang * $model->lebar;

                // list_ruangan
                if (!empty($deletedCategoryIDs)) {
                    IsianLanjutanRuangan::deleteAll([
                        'and',
                        ['id_isian_lanjutan' => $model->id],
                        ['id_ruangan' => $deletedCategoryIDs]
                    ]);
                }

                foreach ($list_ruangan as $cat) {
                    $exist = IsianLanjutanRuangan::findOne(['id_isian_lanjutan' => $model->id, 'id_ruangan' => $cat]);
                    if ($exist == false) {
                        $create_category = new IsianLanjutanRuangan();
                        $create_category->id_isian_lanjutan = $model->id;
                        $create_category->id_ruangan = $cat;
                        if ($create_category->validate() == false) {
                            $transaction->rollBack();
                            toastError("Gagal menyimpan data kategori");
                            goto end;
                        }
                        $create_category->save();
                    }
                }
            endif;

            if ($model->validate() == false) {
                throw new HttpException(422, Constant::flattenError($model->getErrors()));
            }

            $model->save();
            $transaction->commit();
            return [
                'success' => true,
                'message' => 'Data berhasil disimpan.',
                'data' => Url::to(['home/form-rencana-pembangunan/valid', 'id' => $model->kode_unik], true),
            ];
        }

        end:
        throw new HttpException(400, 'Data tidak valid.');
    }
}
