<?php

namespace app\controllers;

use app\components\annex\Tabs;
use app\models\IsianLanjutan;
use app\models\Notification;
use app\models\search\SupplierOrderBarangKeluarSearch;
use app\models\search\SupplierOrderSearch;
use app\models\SupplierBoqProyek;
use app\models\SupplierOrder;
use app\models\SupplierPengiriman;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\Url;

/**
 * This is the class for controller "SupplierOrderController".
 * Modified by Defri Indra
 */
class SupplierOrderController extends \app\controllers\base\SupplierOrderController
{
    public function actionDetail($id)
    {
        $model = SupplierOrder::findOne(['id' => $id]);
        if ($model == null) return "Tidak ditemukan";
        return $this->renderPartial('_view_detail_2', compact('model'));
    }
    public function actionDetailBoq($id)
    {
        $model = IsianLanjutan::findOne(['id' => $id]);
        if ($model == null) return "Tidak ditemukan";
        return $this->renderPartial('_view_detail_3', compact('model'));
    }

    public function actionKonfirmasiBayar($id)
    {
        $model = $this->findModel($id);
        $status = $model->status;
        if ($status != 1) {
            $this->messageValidationFailed();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $model->scenario = $model::SCENARIO_KONFIRMASI;

        try {
            $model->status = 2;
            $model->alasan_tolak = "-";
            
            // dd($model->validate());
            if ($model->validate()) :
                \app\components\Notif::log(
                    $model->user_id,
                    "Admin mengonfirmasi pembayaran Anda.",
                    "Hallo {$model->user->name}, Admin mengonfirmasi pembayaran Anda. Mohon cek pesanan Anda.",
                    [
                        "controller" => "home/bahan-material/pembayaran",
                        "android_route" => "app-riwayat-detail",
                        "params" => [
                            "id" => $model->kode_unik
                        ]
                    ]
                );
                // dd($model);
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

    public function actionTolakBayar($id)
    {
        $model = $this->findModel($id);
        $status = $model->status;
        if ($status != 1) {
            $this->messageValidationFailed();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $model->scenario = $model::SCENARIO_TOLAK_BAYAR;

        try {
            if ($model->load($_POST)) :
                $model->status = 3;
                $model->keterangan_bayar = null;
                if ($model->validate()) :

                    \app\components\Notif::log(
                        $model->user_id,
                        "Admin menolak pembayaran Anda.",
                        "Hallo {$model->user->name}, Admin menolak pembayaran Anda. Mohon cek pesanan Anda.",
                        [
                            "controller" => "home/bahan-material/pembayaran",
                            "android_route" => "app-riwayat-detail",
                            "params" => [
                                "id" => $model->kode_unik
                            ]
                        ]
                    );
                    $model->save();
                    $this->messageCreateSuccess();
                    return $this->redirect(['view', 'id' => $model->id]);
                endif;
                // $this->messageValidationFailed();
            // return $this->redirect(['view', 'id' => $model->id]);
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }
        end:
        return $this->render('tolak-bayar', $model->render());
    }
}
