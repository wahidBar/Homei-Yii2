<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\base;

use app\models\IsianLanjutan;
use app\models\Model;
use app\models\Notification;
use app\models\Penawaran;
use app\models\PenawaranDetail;
use app\models\search\PenawaranSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * PenawaranController implements the CRUD actions for Penawaran model.
 **/
class PenawaranController extends \app\components\productive\DefaultActiveController
{

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     **/
    public $enableCsrfValidation = false;


    /**
     * Lists all Penawaran models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new PenawaranSearch;
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
     * Creates a new Penawaran model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // check is request ajax or not
        if (Yii::$app->request->isAjax) {
            $render = "renderAjax";
        } else {
            $render = "render";
        }

        $dbtrans = Yii::$app->db->beginTransaction();
        $model = new Penawaran();
        $modelHarga = [new PenawaranDetail()];
        $model->scenario = $model::SCENARIO_CREATE;
        $model->tgl_transaksi = date('Y-m-d');

        $model->load($_GET);
        $isian = IsianLanjutan::findOne(['id' => $model->id_isian_lanjutan]);
        if ($isian == null) throw new HttpException(404, "Data tidak ditemukan");
        if (in_array($isian->status, [2, 3]) == false)  throw new HttpException(404, "Tidak dapat menambahkan penawaran, Karena user telah memilih salah satu penawaran yang diajukan");

        try {
            if ($model->load($_POST)) :
                $model->total_harga_penawaran = str_replace(",", "", $_POST['Penawaran']['total_harga_penawaran']);
                $model->kode_unik = Yii::$app->security->generateRandomString(30);
                $model->kode_isian_lanjutan = $isian->kode_unik;
                $cek_max = Penawaran::find()->where(['id_isian_lanjutan' => $isian->id])->max('kode_penawaran');
                if ($cek_max == null) {
                    $no_nota = "HOMEi-0001-" . $isian->label;
                } else {
                    $a = explode("-", $cek_max);
                    $cek = (int) substr($a[1], 0);
                    $cek++;

                    $home = "HOMEi-";
                    $no_notas = sprintf("%04s", $cek);
                    $no_nota = $home . $no_notas . "-" . $isian->label;
                }
                $model->kode_penawaran = $no_nota;
                if ($model->validate()) :
                    $model->save();

                    $detail = Model::createMultiple(PenawaranDetail::classname());
                    Model::loadMultiple($detail, Yii::$app->request->post());

                    $total = 0;
                    $mt = 0;
                    foreach ($detail as $idx => $item) {
                        $barang = \app\models\SupplierBarang::find()->where(['id' => $item->id_material])->one();

                        $detail[$idx]->id_penawaran = $model->id;
                        $detail[$idx]->kode_unik = Yii::$app->security->generateRandomString(30);
                        $detail[$idx]->kode_penawaran = $model->kode_unik;
                        $detail[$idx]->id_material = $item->id_material;
                        $detail[$idx]->kisaran_harga = $barang->harga_proyek;
                        $detail[$idx]->sub_harga = $detail[$idx]->jumlah * $detail[$idx]->kisaran_harga;
                        // validate detail
                        if ($detail[$idx]->validate() == false) {
                            toastError("Terjadi kesalahan pada detail penawaran");
                            goto end;
                        }

                        $total += $detail[$idx]->sub_harga;
                    }
                    $model->harga_penawaran = $total;
                    $model->validate();

                    $isian->scenario = $isian::SCENARIO_DEAL;
                    if ($isian->status == 2) {
                        $isian->status = 3;
                        $isian->save();
                    }

                    \app\components\Notif::log(
                        $isian->id_user,
                        "Admin Telah Mengisi Penawaran",
                        "Hallo {$isian->user->name}, Admin telah mengisi penawaran untuk proyek Anda.",
                        [
                            "controller" => "home/daftar-penawaran-project",
                            "android_route" => "app-penawaran-detail",
                            "params" => [
                                "id" => $isian->kode_unik
                            ]
                        ]
                    );

                    // validate all models
                    $valid = $model->validate();
                    $valid = Model::validateMultiple($detail) && $valid;

                    // dd(Model::validateMultiple($detail));
                    if ($valid) {
                        foreach ($detail as $idx => $item) {
                            $item->save();
                        }
                        $model->save();

                        $dbtrans->commit();
                        $this->messageCreateSuccess();
                        return $this->redirect(['/isian-lanjutan/view', 'id' => $isian->id]);
                    }
                endif;
                $dbtrans->rollBack();
                $this->messageValidationFailed();
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $dbtrans->rollBack();
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        $model->setRender(compact('model', 'modelHarga'));
        return $this->$render('create', $model->render());
    }

    /**
     * Updates an existing SuratBeritaAcaraPemasanganAlat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

        $dbtrans = Yii::$app->db->beginTransaction();
        $model = $this->findModel($id);
        $modelHarga = $model->penawaranDetails;
        $model->scenario = $model::SCENARIO_UPDATE;

        try {
            if ($model->load($_POST)) :
                if ($model->validate()) :
                    if ($model->kode_unik == null) {
                        $model->kode_unik = Yii::$app->security->generateRandomString(30);
                    }
                    if ($model->save()) {
                        $oldIDs = ArrayHelper::map($modelHarga, 'id', 'id');
                        $harga = Model::createMultiple(PenawaranDetail::classname(), $modelHarga);
                        Model::loadMultiple($harga, Yii::$app->request->post());
                        $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelHarga, 'id', 'id')));

                        $total = 0;
                        foreach ($harga as $idx => $item) {
                            $harga[$idx]->id_penawaran = $model->id;
                            $hargas = \app\models\HargaMaterial::findOne(['id_supplier' => $harga[$idx]->id_supplier]);
                            $material = \app\models\MasterMaterial::find()->where(['id' => $hargas->id_material])->one();
                            $mt = $material->id;
                            $harga[$idx]->id_material = $item->id_material;
                            $harga[$idx]->kisaran_harga = (int)$hargas->harga;
                            $harga[$idx]->sub_harga = (int)$hargas->harga * $harga[$idx]->jumlah;
                            $total += $hargas->harga * $harga[$idx]->jumlah;
                        }
                        $model->harga_penawaran = $total;
                        $model->save();
                        // var_dump($harga);die;
                        // validate all models
                        $valid = $model->validate();
                        $valid = Model::validateMultiple($harga) && $valid;
                        // var_dump($modelHarga);die;
                        if ($valid) {
                            if (!empty($deletedIDs)) {
                                PenawaranDetail::findOne(['id' => $deletedIDs])->delete();
                            }
                            foreach ($harga as $idx => $item) {

                                // $item->id_material = $model->id;
                                $item->save();
                                // $item->save();
                            }
                        }
                        $dbtrans->commit();
                        $this->messageUpdateSuccess();
                        return $this->redirect(['/isian-lanjutan/view', 'id' => $model->id_isian_lanjutan]);
                    }
                endif;
                $this->messageValidationFailed();
            endif;
            goto end;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        $model->setRender(compact('model', 'modelHarga'));
        return $this->render('update', $model->render());
    }

    /**
     * Deletes an existing SuratBeritaAcaraPemasanganAlat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
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

    /**
     * Finds the Penawaran model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Penawaran the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Penawaran::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
