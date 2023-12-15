<?php

namespace app\controllers;

use app\components\annex\Tabs;
use app\components\CustomAuth;
use app\models\ProyekKemajuanTarget;
use app\models\SupplierOrder;
use app\models\UserOtp;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * This is the class for controller "ToolController".
 * Modified by Defri Indra
 */
class ToolController extends Controller
{
    public function actionCleanOtp()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        UserOtp::deleteAll([
            'or',
            ['is_used' => 1],
            ['<', 'expired_at', date('Y-m-d H:i:s')]
        ]);

        return [
            "success" => true,
            "message" => "Cronjob berhasil dijalankan"
        ];
    }

    public function actionCheckExpiredPayment()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = SupplierOrder::find()
            ->where([
                'status' => SupplierOrder::STATUS_BELUM_BAYAR
            ])
            ->andWhere([
                '<', 'deadline_bayar', date("Y-m-d H:i:s"),
            ])
            ->limit(500)
            ->all();

        foreach ($model as $item) {
            $item->status = $item::STATUS_PEMBAYARAN_EXPIRED;
            $item->save();
        }

        return [
            "success" => true,
            "message" => "Cronjob berhasil dijalankan"
        ];
    }

    public function actionGrafik($id)
    {
        // $auth = new CustomAuth();
        // $response = $auth->authenticate(null, null, null);
        // if ($response == null) throw new HttpException(403, "Anda tidak di ijinkan mengakses halaman ini");

        $model = \app\models\Proyek::findOne([
            "id" => $id,
            // "id_user" => Yii::$app->user->id
        ]);
        if ($model == null) throw new HttpException(404, "Data tidak ditemukan");

        date_default_timezone_set('Asia/Jakarta');
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember();
        Tabs::rememberActiveState();
        Tabs::rememberActiveState(["second-relation-tabs"]);

        $model_targets = ProyekKemajuanTarget::find()->where(['kode_proyek' => $model->kode_unik])->all();

        $target_perminggu = [];
        foreach ($model_targets as $target) {
            $target_perminggu[] = number_format($target->jumlah_target, 2);
        }

        $dari = $model->tanggal_awal_kontrak;
        $akhir = $model->tanggal_akhir_kontrak;
        $sampai = date('Y-m-d', strtotime('+7 day', strtotime($akhir)));
        $daftar_tanggal = \app\components\Tanggal::dateRange($dari, $sampai, '+8 day');

        $last_input = \app\models\ProyekKemajuanHarian::find()
            ->where(['id_proyek' => $model->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->select('created_at')
            ->one();

        $progress_perminggu = array();
        $progress_minggu_ini = array();
        $total_progress_mingguan = 0;

        $progress_perminggu[] = 0; // start from 0
        foreach ($daftar_tanggal as $key => $tanggal) {
            $next_week = date('Y-m-d', strtotime('+7 day', strtotime($tanggal)));
            $awal =  $model->getRealisasiByRangeDate($tanggal, $next_week);
            $total_progress_mingguan = $total_progress_mingguan + $awal;
            if ($last_input != null && strtotime($last_input->created_at) >= strtotime($tanggal)) {
                $progress_perminggu[] =  number_format($total_progress_mingguan, 2);
            } else {
                // $progress_perminggu[] = 0;
            }
            $check_date = \app\components\Tanggal::checkBetweenDate($tanggal, $next_week);
            if ($check_date == true) {
                $progress_minggu_ini['data'] = number_format($model->getRealisasiByRangeDate($tanggal, $next_week), 2);
                $target = ProyekKemajuanTarget::find()
                    ->where(['kode_proyek' => $model->kode_unik])
                    ->andWhere(['between', 'tanggal_awal', $tanggal, $next_week])->one();
                $progress_minggu_ini['deviasi'] =  end($progress_perminggu) - $target->jumlah_target;
                $progress_minggu_ini['tanggal_awal'] = $tanggal;
                $progress_minggu_ini['tanggal_akhir'] = $next_week;
            }
        }

        $daftar_tanggal = \app\components\Tanggal::dateRange($dari, $sampai, '+7 day');

        return $this->renderAjax('/proyek/_view/info', [
            'model' => $model,
            'daftar_tanggal' => json_encode($daftar_tanggal),
            'target_perminggu' => json_encode($target_perminggu),
            'progress_perminggu' => json_encode($progress_perminggu),
            'progress_minggu_ini' => $progress_minggu_ini,
        ]);

        // $model_targets = \app\models\ProyekKemajuanTarget::find()->where(['kode_proyek' => $model->kode_unik])->all();

        // $target_perminggu = [];
        // foreach ($model_targets as $target) {
        //     $target_perminggu[] = number_format($target->jumlah_target, 2);
        // }

        // $dari = $model->tanggal_awal_kontrak;
        // $akhir = $model->tanggal_akhir_kontrak;
        // $sampai = date('Y-m-d', strtotime('+7 day', strtotime($akhir)));
        // $daftar_tanggal = \app\components\Tanggal::dateRange($dari, $sampai, '+7 day');
        // $daftar_tanggal_harian = \app\components\Tanggal::dateRange($dari, $sampai, '+1 day');

        // $progress_perminggu = array();
        // $total_progress_mingguan = 0;
        // foreach ($daftar_tanggal as $tanggal) {
        //     $next_week = date('Y-m-d', strtotime('+7 day', strtotime($tanggal)));
        //     $awal =  $model->getRealisasiByRangeDate($tanggal, $next_week);
        //     $total_progress_mingguan = $total_progress_mingguan + $awal;
        //     $progress_perminggu[] =  number_format($total_progress_mingguan, 2);
        // }

        // return Yii::$app->controller->renderAjax('/proyek/_view/info', [
        //     'model' => $model,
        //     'daftar_tanggal' => json_encode($daftar_tanggal),
        //     'target_perminggu' => json_encode($target_perminggu),
        //     'progress_perminggu' => json_encode($progress_perminggu),
        // ]);
    }
}
