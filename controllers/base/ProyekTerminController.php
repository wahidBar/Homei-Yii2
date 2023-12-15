<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\base;

use app\components\Constant;
use app\models\MasterKategoriKeuanganMasuk;
use app\models\Notification;
use app\models\Proyek;
use app\models\ProyekKeuanganMasuk;
use app\models\ProyekTermin;
use app\models\search\ProyekTerminSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;
use Yii;

/**
 * ProyekTerminController implements the CRUD actions for ProyekTermin model.
 **/
class ProyekTerminController extends \app\components\productive\DefaultActiveController
{

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     **/
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return \app\models\Action::getAccess($this->id, true, "id_project");
    }

    /**
     * Lists all ProyekTermin models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new ProyekTerminSearch;
        $dataProvider = $searchModel->search($_GET);

        Tabs::clearLocalStorage();

        Url::remember();
        \Yii::$app->session['__crudReturnUrl'] = null;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Creates a new ProyekTermin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new ProyekTermin;
        $model->scenario = $model::SCENARIO_CREATE;

        $proyek = Proyek::findOne($id);
        // dd($proyek->id_user);
        try {
            if ($model->load($_POST)) :

                $model->nilai_pembayaran = str_replace(",", "", $model->nilai_pembayaran);
                // $total_nilai = $proyek->total_pembayaran + $model->nilai_pembayaran;
                $total_nilai = $model->nilai_pembayaran;

                // dd($total_nilai . ' ' . $proyek->nilai_kontrak);

                if ($total_nilai > $proyek->nilai_kontrak) {
                    toastError("Nilai Termin Melebihi Nilai Kontrak Proyek");
                    goto end;
                }
                $model->kode_unik = Yii::$app->security->generateRandomString(30);
                $model->proyek_id = $id;
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
                    $this->messageCreateSuccess();
                    return $this->redirect(['proyek/view', 'id' => $id]);
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
        return $this->render('create', $model->render());
    }

    /**
     * Updates an existing SuratBeritaAcaraPemasanganAlat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = $model::SCENARIO_UPDATE;

        try {
            if ($model->load($_POST)) :
                $model->nilai_pembayaran = str_replace(",", "", $model->nilai_pembayaran);
                if ($model->validate()) :
                    $model->save();
                    $this->messageUpdateSuccess();
                    return $this->redirect(['proyek/view', 'id' => $model->proyek_id]);
                endif;
                $this->messageValidationFailed();
            endif;
            goto end;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->render('update', $model->render());
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->scenario = $model::SCENARIO_DELETE;

        try {
            if ($model->validate()) :
                $model->flag = 0;
                $model->deleted_at = date("Y-m-d H:i:s");
                $model->deleted_by = Yii::$app->user->id;
                $model->save();
                $this->messageDeleteSuccess();
                return $this->redirect(['index']);
            endif;
            $this->messageValidationFailed();
            goto end;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->render('update', $model->render());
    }

    /**
     * Deletes an existing SuratBeritaAcaraPemasanganAlat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeletePermanent($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = $this->findModel($id);

            $model->delete();

            $transaction->commit();
            $this->messageDeleteSuccess();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            \Yii::$app->getSession()->addFlash('error', $msg);
            return $this->redirect(Url::previous());
        }

        // TODO: improve detection
        $isPivot = strstr('$id', ',');
        if ($isPivot == true) :
            return $this->redirect(Url::previous());
        elseif (isset(\Yii::$app->session['__crudReturnUrl']) && \Yii::$app->session['__crudReturnUrl'] != '/') :
            Url::remember(null);
            $url = \Yii::$app->session['__crudReturnUrl'];
            \Yii::$app->session['__crudReturnUrl'] = null;

            return $this->redirect($url);
        else :
            return $this->redirect(['index']);
        endif;
    }

    public function actionKonfirmasiTermin($id)
    {
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();

        $proyek = $model->proyek;
        $proyek->scenario = $proyek::SCENARIO_BAYAR_TERMIN;
        $status = $model->status;
        if ($status != 1) {
            $transaction->rollBack();
            $this->messageValidationFailed();
            return $this->redirect(['/proyek/view', 'id' => $model->proyek_id]);
        }
        if ($model->proyek->total_pembayaran == $model->proyek->nilai_kontrak) {
            $transaction->rollBack();
            $this->messageValidationFailed();
            return $this->redirect(['/proyek/view', 'id' => $model->proyek_id]);
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
                    toastError("Gagal membuat kategori keuangan");
                    return $this->redirect(['/proyek/view', 'id' => $model->id]);
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
                    toastError("Gagal membuat data keuangan : " . Constant::flattenError($keuangan->getErrors()));
                    return $this->redirect(['/proyek/view', 'id' => $model->id]);
                }

                $keuangan->save();


                $transaction->commit();
                $this->messageCreateSuccess();
                return $this->redirect(['/proyek/view', 'id' => $model->proyek_id]);
            endif;
            $this->messageValidationFailed();
            return $this->redirect(['/proyek/view', 'id' => $model->proyek_id]);
        } catch (\Exception $e) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }
    }

    public function actionTolakTermin($id)
    {
        $model = $this->findModel($id);

        $model->scenario = $model::SCENARIO_TOLAK_BAYAR_TERMIN;
        $proyek = Proyek::find()->where(['id' => $model->proyek_id])->one();
        $proyek->scenario = $proyek::SCENARIO_BAYAR_TERMIN;

        $status = $model->status;
        if ($status != 1) {
            $this->messageValidationFailed();
            return $this->redirect(['/proyek/view', 'id' => $model->proyek_id]);
        }
        if ($model->proyek->total_pembayaran == $model->proyek->nilai_kontrak) {
            $this->messageValidationFailed();
            return $this->redirect(['/proyek/view', 'id' => $model->proyek_id]);
        }
        try {
            if ($model->load($_POST)) :
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
                    $this->messageCreateSuccess();
                    return $this->redirect(['/proyek/view', 'id' => $model->proyek_id]);
                endif;
                $this->messageValidationFailed();
            // return $this->redirect(['/proyek/view', 'id' => $model->id_proyek]);
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }
        end:
        return $this->render('tolak-termin', $model->render());
    }

    /**
     * Finds the ProyekTermin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProyekTermin the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProyekTermin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
