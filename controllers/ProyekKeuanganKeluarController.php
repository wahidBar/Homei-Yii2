<?php

namespace app\controllers;

use app\components\Constant;
use app\components\UploadFile;
use app\models\Model;
use app\models\ProyekKeuanganKeluar;
use app\models\ProyekKeuanganKeluarDetail;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\UploadedFile;

/**
 * This is the class for controller "ProyekKeuanganKeluarController".
 * Modified by Defri Indra
 */
class ProyekKeuanganKeluarController extends \app\controllers\base\ProyekKeuanganKeluarController
{
    use UploadFile;

    /**
     * RBAC filter
     */
    public function behaviors()
    {
        return \app\models\Action::getAccess($this->id, true, "id_project");
    }


    /**
     * Creates a new ProyekKeuanganKeluar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreatePo()
    {
        $model = new ProyekKeuanganKeluar;
        $model->scenario = $model::SCENARIO_PO;
        $modelDetail = [new ProyekKeuanganKeluarDetail];
        $model->tipe = 1;
        $old_dokumen = $model->dokumen_po;

        if (Yii::$app->request->isAjax) {
            $render = "renderAjax";
        } else {
            $render = "render";
        }

        try {
            $transaction = Yii::$app->db->beginTransaction();
            if ($model->load($_POST)) :

                $dokumen = UploadedFile::getInstance($model, "dokumen_po");
                if ($dokumen) {
                    $response = $this->uploadFile($dokumen, "project/$model->id/dokumen-po");
                    if ($response->success == false) {
                        toastError("Dokument gagal diunggah");
                        goto end;
                    }
                    $model->dokumen_po = $response->filename;
                } else {
                    $model->dokumen_po = $old_dokumen;
                }

                // save data
                if ($model->validate() == false) {
                    $transaction->rollBack();
                    toastError("Data gagal divalidasi");
                    goto end;
                }
                $model->tipe = 1;
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
    public function actionUpdatePo($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $model = $this->findModel($id);
        if ($model->status != 0 && Constant::getUser()->role_id != Constant::ROLES['sa']) throw new HttpException(403, "Anda tidak diperbolehkan mengakses menu ini. Hubungi admin jika anda merasa perlu mengubah data ini");
        $modelDetail = $model->proyekKeuanganKeluarDetails;
        $oldDetail = ArrayHelper::map($modelDetail, 'id', 'id');
        if ($modelDetail == []) $modelDetail = [new ProyekKeuanganKeluarDetail()];
        $model->scenario = $model::SCENARIO_PO;
        $model->tipe = 1;
        $model->status = 0;
        $old_dokumen = $model->dokumen_po;

        if (Yii::$app->request->isAjax) $render = "renderAjax";
        else $render = "render";

        try {
            if ($model->load($_POST)) :

                $dokumen = UploadedFile::getInstance($model, "dokumen_po");
                if ($dokumen) {
                    $response = $this->uploadFile($dokumen, "project/$model->id/dokumen-po");
                    if ($response->success == false) {
                        toastError("Dokument gagal diunggah");
                        goto end;
                    }
                    $model->dokumen_po = $response->filename;
                } else {
                    $model->dokumen_po = $old_dokumen;
                }

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
                $model->tipe = 1; // biasa , bukan po
                $model->status = 0; // lunas

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
}
