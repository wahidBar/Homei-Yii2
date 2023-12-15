<?php

namespace app\controllers\api\v1\keuangan;

use app\components\Constant;
use app\models\MasterKategoriKeuanganMasuk;
use app\models\ProyekKeuanganMasuk;
use yii\web\HttpException;

class ProyekTerminController extends \app\controllers\api\v1\kontraktor\BaseController
{
    public $modelClass = 'app\models\ProyekTermin';

    const ALLOWED_ROLES = [
        \app\components\Constant::ROLE_KEUANGAN,
    ];

    public function verbs()
    {
        $parent = parent::verbs();
        $parent['konfirmasi-termin'] = ['POST'];
        return $parent;
    }

    /**
     * actionIndex
     */
    public function actionIndex($id_project)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $query = $this->modelClass::showAtIndex($project->id);

        return $this->dataProvider($query);
    }
    /**
     * actionIndex
     */
    public function actionView($id_project, $uniq)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $model = $this->modelClass::findOne([
            "kode_unik" => $uniq,
            "proyek_id" => $project->id,
            "flag" => 1,
        ]);

        return [
            "success" => true,
            "data" => $model
        ];
    }

    /**
     * Creates a new ProyekTermin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_project)
    {
        $proyek = $this->hasAccessAtThisProject($id_project);
        $model = new $this->modelClass;
        $model->scenario = $model::SCENARIO_CREATE;

        try {
            if ($model->load($_POST, '')) :

                $model->nilai_pembayaran = str_replace(",", "", $model->nilai_pembayaran);
                // $total_nilai = $proyek->total_pembayaran + $model->nilai_pembayaran;
                $total_nilai = $model->nilai_pembayaran;

                if ($total_nilai > $proyek->nilai_kontrak) {
                    throw new HttpException(400, 'Nilai pembayaran melebihi nilai kontrak.');
                }

                $model->kode_unik = \Yii::$app->security->generateRandomString(30);
                $model->proyek_id = $proyek->id;
                $model->id_proyek = $proyek->id;
                $model->kode_proyek = $proyek->kode_unik;
                $model->user_id = $proyek->id_user;
                $model->status = 0;
                $model->flag = 1;
                if ($model->validate()) :

                    \app\components\Notif::log(
                        $model->user_id,
                        "Admin telah mengatur Termin Pembayaran Proyek",
                        "Hallo {$model->user->name}, telah mengatur Termin Pembayaran Proyek. Silahkan cek data proyek",
                        [
                            "controller" => "home/proyek-saya/pembayaran",
                            "android_route" => "app-proyek-detail",
                            "params" => [
                                "id" => $model->kode_proyek
                            ]
                        ]
                    );

                    $model->save();
                    return ['success' => true, 'message' => 'Data berhasil disimpan.'];
                endif;
                // throw new HttpException(422, \app\components\Constant::flattenError($model->errors));
                throw new HttpException(422, 'Terjadi kesalahan saat menyimpan data.');
            endif;
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? 'Terjadi kesalahan saat menyimpan data.');
        }

        throw new HttpException(400);
    }

    /**
     * Updates an existing SuratBeritaAcaraPemasanganAlat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id_project, $uniq)
    {
        $proyek = $this->hasAccessAtThisProject($id_project);
        $model = $this->findModel([
            'kode_unik' => $uniq,
            'proyek_id' => $proyek->id,
            'flag' => 1,
        ]);
        $old_kode_unik = $model->kode_unik;
        $old_id_user = $model->user_id;
        $old_kode_proyek = $model->kode_proyek;
        $old_status = $model->status;

        if ($model->status != \app\models\ProyekTermin::STATUS_BELUM_BAYAR) {
            throw new HttpException(400, "Tidak dapat mengubah data yang sudah dibayar.");
        }

        $model->scenario = $model::SCENARIO_UPDATE;

        try {
            if ($model->load($_POST, '')) :
                $model->kode_unik = $old_kode_unik;
                $model->proyek_id = $proyek->id;
                $model->id_proyek = $proyek->id;

                $model->kode_proyek = $old_kode_proyek;
                $model->user_id = $old_id_user;
                $model->status = $old_status;
                $model->nilai_pembayaran = str_replace(",", "", $model->nilai_pembayaran);
                if ($model->validate()) :
                    $model->save();
                    return ['success' => true, 'message' => 'Data berhasil disimpan.'];
                endif;
                throw new HttpException(422, 'Terjadi kesalahan saat menyimpan data.');
            endif;
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? 'Terjadi kesalahan saat menyimpan data.');
        }

        throw new HttpException(400, "Data tidak lengkap.");
    }

    public function actionDelete($id_project, $uniq)
    {
        $proyek = $this->hasAccessAtThisProject($id_project);
        $model = $this->findModel(
            [
                'kode_unik' => $uniq,
                'proyek_id' => $proyek->id,
                'flag' => 1
            ]
        );
        $model->scenario = $model::SCENARIO_DELETE;

        try {
            if ($model->validate()) :
                $model->flag = 0;
                // $model->deleted_at = date("Y-m-d H:i:s");
                // $model->deleted_by = \Yii::$app->user->id;
                $model->save();

                return [
                    'success' => true,
                    'message' => 'Data berhasil dihapus.'
                ];
            endif;
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? 'Terjadi kesalahan saat menyimpan data.');
        }

        throw new HttpException(400, "Terjadi kesalahan saat menghapus data.");
    }

    public function actionKonfirmasiTermin($id_project, $uniq)
    {
        $proyek = $this->hasAccessAtThisProject($id_project);
        $model = $this->findModel(
            [
                'kode_unik' => $uniq,
                'proyek_id' => $proyek->id,
                "flag" => 1,
            ]
        );

        $transaction = \Yii::$app->db->beginTransaction();
        $proyek->scenario = $proyek::SCENARIO_BAYAR_TERMIN;
        $status = $model->status;

        if ($status != 1) {
            $transaction->rollBack();
            throw new HttpException(400, "Status Termin Pembayaran tidak sesuai.");
        }

        if ($model->proyek->total_pembayaran == $model->proyek->nilai_kontrak) {
            $transaction->rollBack();
            throw new HttpException(400, "Nilai Pembayaran sudah sesuai dengan nilai kontrak.");
        }

        $model->scenario = $model::SCENARIO_KONFIRMASI_BAYAR_TERMIN;

        try {
            $proyek->total_pembayaran += $model->nilai_pembayaran;
            $model->status = 2;
            $model->alasan_tolak_pembayaran = "-";

            if ($model->validate()) :

                \app\components\Notif::log(
                    $model->user_id,
                    "Admin telah menyetujui pembayaran termin Anda",
                    "Hallo {$model->user->name}, telah menyetujui pembayaran termin Anda. Silahkan cek data proyek",
                    [
                        "controller" => "home/proyek-saya/pembayaran",
                        "android_route" => "app-proyek-detail",
                        "params" => [
                            "id" => $model->kode_proyek
                        ]
                    ]
                );

                $model->save();
                $proyek->save();

                // buat data keuangan masuk dari pembayaran dp di proyek ini

                $kategori = new MasterKategoriKeuanganMasuk();
                $kategori->id_proyek = $proyek->id;
                $kategori->nama_kategori = $model->termin;
                if ($kategori->validate() == false) {
                    throw new HttpException(422, 'Terjadi kesalahan ketika membuat data kategori keuangan masuk.');
                }

                $kategori->save();

                $keuangan = new ProyekKeuanganMasuk();
                $keuangan->id_proyek = $proyek->id;
                $keuangan->id_kategori = $kategori->id;
                $keuangan->item = $model->termin;
                $keuangan->tanggal = $model->tanggal_pembayaran;
                $keuangan->jumlah = $model->nilai_pembayaran;
                $keuangan->keterangan = "Pembayaran dari termin : {$model->termin}";
                $keuangan->created_at = date("Y-m-d H:i:s");
                $keuangan->created_by = Constant::getUser()->id;

                if ($keuangan->validate() == false) {
                    $transaction->rollBack();
                    throw new HttpException(422, 'Terjadi kesalahan ketika membuat data keuangan masuk.');
                }

                $keuangan->save();


                $transaction->commit();
                return [
                    'success' => true,
                    'message' => 'Data berhasil disimpan.'
                ];
            endif;
        } catch (\Exception $e) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? 'Terjadi kesalahan saat menyimpan data.');
        }
        throw new HttpException(400, "Terjadi kesalahan saat menyimpan data.");
    }

    public function actionTolakTermin($id_project, $uniq)
    {
        $proyek = $this->hasAccessAtThisProject($id_project);
        $model = $this->findModel(
            [
                'kode_unik' => $uniq,
                'proyek_id' => $proyek->id,
                "flag" => 1,
            ]
        );

        $model->scenario = $model::SCENARIO_TOLAK_BAYAR_TERMIN;
        $proyek->scenario = $proyek::SCENARIO_BAYAR_TERMIN;

        $status = $model->status;
        if ($status != 1) {
            throw new HttpException(400, "Status Termin Pembayaran tidak sesuai.");
        }

        if ($model->proyek->total_pembayaran == $model->proyek->nilai_kontrak) {
            throw new HttpException(400, "Nilai Pembayaran sudah sesuai dengan nilai kontrak.");
        }

        try {
            if ($model->load($_POST, '')) :
                $model->status = 3;
                $model->keterangan_pembayaran = null;

                $oldtotal = $proyek->total_pembayaran;
                $hasil = $oldtotal - $model->nilai_pembayaran;
                $proyek->total_pembayaran = $hasil;

                if ($model->validate()) :
                    $proyek->save();

                    \app\components\Notif::log(
                        $model->user_id,
                        "Admin telah menolak pembayaran termin Anda",
                        "Hallo {$model->user->name}, telah menolak pembayaran termin Anda. Silahkan cek data proyek",
                        [
                            "controller" => "home/proyek-saya/pembayaran",
                            "android_route" => "app-proyek-detail",
                            "params" => [
                                "id" => $model->kode_proyek
                            ]
                        ]
                    );

                    $model->save();
                    return [
                        'success' => true,
                        'message' => 'Data berhasil disimpan.'
                    ];
                endif;
                throw new HttpException(400, "Terjadi kesalahan saat menyimpan data.");
            endif;
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? 'Terjadi kesalahan saat menyimpan data.');
        }

        throw new HttpException(400, "Terjadi kesalahan saat menyimpan data.");
    }
}
