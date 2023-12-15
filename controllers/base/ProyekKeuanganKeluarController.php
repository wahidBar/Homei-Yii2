<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\base;

use app\components\Constant;
use app\models\Model;
use app\models\ProyekKeuanganKeluar;
use app\models\ProyekKeuanganKeluarDetail;
use app\models\search\ProyekKeuanganKeluarSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * ProyekKeuanganKeluarController implements the CRUD actions for ProyekKeuanganKeluar model.
 **/
class ProyekKeuanganKeluarController extends \app\components\productive\DefaultActiveController
{

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     **/
    public $enableCsrfValidation = false;

    /**
     * Displays a single SuratBeritaAcaraSosialisasi model.
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        if (Yii::$app->request->isAjax) {
            $render = "renderAjax";
            $view = "view_ajax";
        } else {
            $render = "render";
            $view = "view";
        }

        return $this->$render($view, [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ProyekKeuanganKeluar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProyekKeuanganKeluar;
        $model->scenario = $model::SCENARIO_PENGELUARAN;
        $modelDetail = [new ProyekKeuanganKeluarDetail];

        if (Yii::$app->request->isAjax) $render = "renderAjax";
        else $render = "render";

        try {
            $transaction = Yii::$app->db->beginTransaction();
            if ($model->load($_POST)) :
                // save data
                if ($model->validate() == false) {
                    $transaction->rollBack();
                    toastError("Data gagal divalidasi");
                    goto end;
                }
                $model->save();

                // load model detail
                $modelDetail = Model::createMultiple(ProyekKeuanganKeluarDetail::class);
                Model::loadMultiple($modelDetail, $_POST);
                $total_jumlah = 0;
                foreach ($modelDetail as $id => $item) {
                    $modelDetail[$id]->id_keuangan_keluar = $model->id;
                    $to_idr = str_replace(",", "", $modelDetail[$id]->harga_satuan);
                    if (is_numeric($to_idr) == false) {
                        $transaction->rollBack();
                        toastError("Terdapat data detail yang tidak valid");
                        goto end;
                    }
                    $modelDetail[$id]->harga_satuan = $to_idr;
                    $modelDetail[$id]->jumlah = intval($modelDetail[$id]->harga_satuan) * intval($modelDetail[$id]->kuantitas);
                    $total_jumlah += intval($modelDetail[$id]->jumlah);
                }

                // validate
                $model->status = 2;
                $model->flag = 1;
                $valid = $model->validate() && Model::validateMultiple($modelDetail);
                if ($valid) :
                    $model->total_jumlah = $total_jumlah;
                    $model->save();
                    foreach ($modelDetail as $item) $item->save();
                    $transaction->commit();
                    $this->messageCreateSuccess();
                    return $this->redirect(['keuangan/view', 'id' => $model->id_proyek]);
                endif;
                $transaction->rollBack();
                $this->messageValidationFailed();
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        $model->setRender(['modelDetail' => $modelDetail]);
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
        $transaction = Yii::$app->db->beginTransaction();
        $model = $this->findModel($id);
        $modelDetail = $model->proyekKeuanganKeluarDetails;
        $oldDetail = ArrayHelper::map($modelDetail, 'id', 'id');
        if ($modelDetail == []) $modelDetail = [new ProyekKeuanganKeluarDetail()];
        $model->scenario = $model::SCENARIO_PENGELUARAN;

        if (Yii::$app->request->isAjax) $render = "renderAjax";
        else $render = "render";

        try {
            if ($model->load($_POST)) :

                // load model detail
                $modelDetail = Model::createMultiple(ProyekKeuanganKeluarDetail::class, $modelDetail);
                Model::loadMultiple($modelDetail, $_POST);
                $deleteDetail = array_diff($oldDetail, array_filter(ArrayHelper::map($modelDetail, 'id', 'id')));
                $total_jumlah = 0;
                foreach ($modelDetail as $id => $item) {
                    $modelDetail[$id]->id_keuangan_keluar = $model->id;
                    $to_idr = str_replace(",", "", $modelDetail[$id]->harga_satuan);
                    if (is_numeric($to_idr) == false) {
                        $transaction->rollBack();
                        toastError("Terdapat data detail yang tidak valid");
                        goto end;
                    }
                    $modelDetail[$id]->harga_satuan = $to_idr;
                    $modelDetail[$id]->jumlah = intval($modelDetail[$id]->harga_satuan) * intval($modelDetail[$id]->kuantitas);
                    $total_jumlah += intval($modelDetail[$id]->jumlah);
                }
                $model->total_jumlah = $total_jumlah;

                // set default value
                $model->tipe = 0; // biasa , bukan po
                $model->status = 1; // lunas

                // validate
                $valid = $model->validate() && Model::validateMultiple($modelDetail);
                if ($valid) :
                    $model->save();
                    foreach ($modelDetail as $item) $item->save();
                    ProyekKeuanganKeluarDetail::deleteAll(['in', 'id', $deleteDetail]);
                    $transaction->commit();
                    $this->messageUpdateSuccess();
                    return $this->redirect(['keuangan/view', 'id' => $model->id_proyek]);
                endif;
                $this->messageValidationFailed();
                $transaction->rollBack();
            endif;
            goto end;
        } catch (\Exception $e) {
            $transaction->rollBack();
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        $model->setRender(['modelDetail' => $modelDetail]);
        return $this->$render('update', $model->render());
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
        $model = $this->findModel($id);
        $id_proyek = $model->id_proyek;

        try {
            $model->scenario = $model::SCENARIO_DELETE;
            if ($model->tipe == 1 && $model->status != 0) {
                toastError("Anda tidak dapat menghapus data ini. Hubungi admin jika dirasa harus mengakses fitur ini.");
                return $this->redirect(['keuangan/view', 'id' => $model->id_proyek]);
            }
            $model->deleted_at = date("Y-m-d H:i:s");
            $model->deleted_by = Constant::getUser()->id;
            $model->flag = 0;
            $model->save();

            $transaction->commit();
            $this->messageDeleteSuccess();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            \Yii::$app->getSession()->addFlash('error', $msg);
            return $this->redirect(['proyek/keuangan', 'id' => $id_proyek]);
        }

        return $this->redirect(['proyek/keuangan', 'id' => $id_proyek]);
    }

    /**
     * Finds the ProyekKeuanganKeluar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProyekKeuanganKeluar the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProyekKeuanganKeluar::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
