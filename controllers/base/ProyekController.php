<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\base;

use app\models\Proyek;
use app\models\search\ProyekSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use app\components\annex\Tabs;
use app\models\ProyekKemajuanTarget;
use Yii;
use yii\data\Pagination;
use yii\web\UploadedFile;

/**
 * ProyekController implements the CRUD actions for Proyek model.
 **/
class ProyekController extends \app\components\productive\DefaultActiveController
{
    use \app\components\UploadFile;

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     **/
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return \app\models\Action::getAccess($this->id, true, "id");
    }

    /**
     * Lists all Proyek models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new ProyekSearch;
        $dataProvider = $searchModel->search($_GET);

        Tabs::clearLocalStorage();

        Url::remember();
        \Yii::$app->session['__crudReturnUrl'] = null;

        $dataProvider->pagination = new Pagination([
            "pageSize" => 9
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    public function actionView($id)
    {
        date_default_timezone_set('Asia/Jakarta');
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember();
        Tabs::rememberActiveState();
        Tabs::rememberActiveState(["second-relation-tabs"]);
        $model = $this->findModel($id);

        $model_targets = ProyekKemajuanTarget::find()->where(['kode_proyek' => $model->kode_unik])->all();

        $target_perminggu = [];
        foreach ($model_targets as $target) {
            $target_perminggu[] = number_format($target->jumlah_target, 2);
        }

        $dari = $model->tanggal_awal_kontrak;
        $akhir = $model->tanggal_akhir_kontrak;
        $sampai = date('Y-m-d', strtotime('+7 day', strtotime($akhir)));
        $daftar_tanggal = \app\components\Tanggal::dateRange($dari, $sampai, '+8 day');
        $daftar_tanggal_harian = \app\components\Tanggal::dateRange($dari, $sampai, '+1 day');

        $last_input = \app\models\ProyekKemajuanHarian::find()
            ->where(['id_proyek' => $model->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->select('created_at')
            ->one();

        $progress_perminggu = array();
        $progress_minggu_ini = array();
        $total_progress_mingguan = 0;
        $progress_perminggu[] = 0; // start from 0
        $temp = [];
        foreach ($daftar_tanggal as $key => $tanggal) {

            $next_week = date('Y-m-d', strtotime('+7 day', strtotime($tanggal)));
            $awal =  $model->getRealisasiByRangeDate($tanggal, $next_week);
            $temp[] = $awal;
            $total_progress_mingguan = $total_progress_mingguan + $awal;

            if ($last_input != null && strtotime($last_input->created_at) >= strtotime($tanggal)) {
                $progress_perminggu[] =  number_format($total_progress_mingguan, 2);
            } else {
                // $progress_perminggu[] = 0;
            }

            $check_date = \app\components\Tanggal::checkBetweenDate($tanggal, $next_week);

            if ($check_date == true) {
                $progress_minggu_ini['data'] = $model->getRealisasiByRangeDate($tanggal, $next_week);
                $target = ProyekKemajuanTarget::find()
                    ->where(['kode_proyek' => $model->kode_unik])
                    ->andWhere(['between', 'tanggal_awal', $tanggal, $next_week])->one();
                $progress_minggu_ini['deviasi'] =  end($progress_perminggu) - $target->jumlah_target;
                $progress_minggu_ini['tanggal_awal'] = $tanggal;
                $progress_minggu_ini['tanggal_akhir'] = $next_week;
            }
        }


        $daftar_tanggal = \app\components\Tanggal::dateRange($dari, $sampai, '+7 day');
        return $this->render('view', [
            'model' => $model,
            'daftar_tanggal' => json_encode($daftar_tanggal),
            'target_perminggu' => json_encode($target_perminggu),
            'progress_perminggu' => json_encode($progress_perminggu),
            'progress_minggu_ini' => $progress_minggu_ini,
        ]);
    }

    /**
     * Creates a new Proyek model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Proyek;
        $model->scenario = $model::SCENARIO_CREATE;

        try {
            if ($model->load($_POST)) :
                $model->kode_unik = \Yii::$app->security->generateRandomString(30);
                $model->nilai_kontrak = str_replace(",", "", $model->nilai_kontrak);
                if (is_numeric($model->nilai_kontrak) == false) {
                    toastError("Nilai kontrak harus berupa angka");
                    goto end;
                }

                if ($model->validate()) :
                    $model->flag = 1;
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
                $model->nilai_kontrak = str_replace(",", "", $model->nilai_kontrak);
                $model->total_pembayaran = str_replace(",", "", $model->total_pembayaran);
                if (is_numeric($model->nilai_kontrak) == false) {
                    toastError("Nilai kontrak harus berupa angka");
                    goto end;
                }
                if ($model->validate()) :

                    $model->save();

                    $dari = $model->tanggal_awal_kontrak;
                    $akhir = $model->tanggal_akhir_kontrak;
                    $sampai = date('Y-m-d', strtotime('+7 day', strtotime($akhir)));
                    $daftar_tanggal = \app\components\Tanggal::dateRange($dari, $sampai, '+7 day');
                    unset($daftar_tanggal[count($daftar_tanggal) - 1]); // remove last index

                    $jumlah_minggu = 1;
                    foreach ($daftar_tanggal as $tanggal) {
                        $check = ProyekKemajuanTarget::findOne([
                            'id_proyek' => $model->id,
                            'nama_target' => "Minggu-" . $jumlah_minggu,
                        ]);

                        if ($check) {
                            $next_week = date('Y-m-d', strtotime('+7 day', strtotime($tanggal)));
                            $check->tanggal_awal = $tanggal;
                            $check->tanggal_akhir = $next_week;
                            $check->save();
                        } else {
                            $target = new ProyekKemajuanTarget();
                            $target->scenario = $target::SCENARIO_CREATE;
                            $target->id_proyek = $model->id;
                            $target->kode_proyek = $model->kode_unik;
                            $next_week = date('Y-m-d', strtotime('+7 day', strtotime($tanggal)));
                            $target->tanggal_awal = $tanggal;
                            $target->tanggal_akhir = $next_week;
                            $target->nama_target = "Minggu-" . $jumlah_minggu;
                            $target->nilai_target = 0;
                            $target->jumlah_target = 0;

                            if ($target->validate()) {
                                $target->save();
                            } else {
                                $errors = $target->errors;
                            }
                        }

                        $jumlah_minggu++;
                    }

                    $termins = \app\models\ProyekTermin::find()->where(['id_proyek' => $model->id])->all();
                    foreach ($termins as $termin) {
                        $termin->scenario = $termin::SCENARIO_UPDATE;
                        $termin->user_id = $model->id_user;
                        $termin->save();
                    }

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

            $old = $model->dokumen_tor;
            if ($model->dokumen_tor != null) {
                unlink(Yii::getAlias("@app/web/uploads/") . $old);
            }
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

    public function actionKonfirmasiDp($id)
    {
        $model = $this->findModel($id);
        $status = $model->status_pembayaran;
        if ($status != 1) {
            $this->messageValidationFailed();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        if ($model->total_pembayaran == $model->nilai_kontrak) {
            $this->messageValidationFailed();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $model->scenario = $model::SCENARIO_KONFIRMASI;

        try {

            $model->status_pembayaran = 2;
            $model->alasan_tolak = "-";

            if ($model->validate()) :
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
        if ($model->total_pembayaran == $model->nilai_kontrak) {
            $this->messageValidationFailed();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $model->scenario = $model::SCENARIO_TOLAK;

        try {
            if ($model->load($_POST)) :
                $model->status_pembayaran = 3;
                $oldtotal = $model->total_pembayaran;
                $hasil = $oldtotal - $model->nilai_dp;
                $model->total_pembayaran = $hasil;
                $model->keterangan_pembayaran = null;
                $model->setuju_tor = 0;
                if ($model->validate()) :
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

    /**
     * Finds the Proyek model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Proyek the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Proyek::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
