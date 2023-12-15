<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "PenawaranController".
 * Modified by Defri Indra
 */

use app\models\IsianLanjutan;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class PenawaranController extends \app\controllers\api\BaseController
{
    public $modelClass = 'app\models\Penawaran';

    // public function actionIndex()
    // {
    //     $query = $this->modelClass::find();
    //     return $this->dataProvider($query);
    // }

    // public function actionView($id) 
    // {
    //     $data = $this->findModel($id);
    //     return [
    //         "success" => true,
    //         "data" => $data
    //     ];
    // }

    public function actionDaftarPenawaranProyek($id)
    {
        $query = new Query();
        $query->select([
            't_penawaran.id',
            't_penawaran.kode_unik as kode_penawaran', 't_penawaran.id_isian_lanjutan',
            't_penawaran.estimasi_waktu', 't_penawaran.tgl_transaksi',
            't_penawaran.harga_penawaran', 't_isian_lanjutan.kode_unik',
            't_isian_lanjutan.status', 't_isian_lanjutan.id_user'
        ])
            ->from('t_penawaran')
            ->join(
                'LEFT JOIN',
                't_isian_lanjutan',
                't_isian_lanjutan.kode_unik =t_penawaran.kode_isian_lanjutan'
            )
            ->where(['id_user' => \Yii::$app->user->identity->id])
            ->andWhere(['id_isian_lanjutan' => $id])
            ->andWhere(['<', 'status', 5]);
        $command = $query->createCommand();
        $count = $command->execute();
        $models = $command->queryAll();
        if ($models != null) {
            return [
                "success" => true,
                "data" => $models
            ];
        } else {
            return [
                "success" => false,
                "message" => "Data Tidak Ditemukan"
            ];
        }
    }

    public function actionDetailPenawaranProyek($id)
    {
        $model = $this->findModel($id);

        if ($model != null) {
            return [
                "success" => true,
                "data" => $model
            ];
        } else {
            return [
                "success" => false,
                "message" => "Data Tidak Ditemukan"
            ];
        }
    }

    public function actionPilihPenawaran()
    {
        $id = $_POST['id'];
        $penawaran = $this->findModel($id);
        if ($penawaran == null) {
            return [
                "success" => false,
                "message" => "Data Penawaran Tidak Ditemukan"
            ];
        }
        $isian = IsianLanjutan::find()->where(['id' => $penawaran->id_isian_lanjutan])->one();
        if ($isian == null) {
            return [
                "success" => false,
                "message" => "Data Rencana Pembangunan Tidak Ditemukan"
            ];
        }

        $isian->scenario = $isian::SCENARIO_DEAL;
        $isian->id_penawaran = $penawaran->id;
        $isian->status = $isian::STATUS_DEAL_USER;
        
        if ($isian->save()) {
            return [
                "success" => true,
                "data" => $isian,
                "message" => "Data Berhasil Disimpan",
            ];
        } else {
            return [
                "success" => false,
                "message" => "Data Gagal Disimpan",
            ];
        }
    }

    // public function actionCreate()
    // {
    //     $model = new $this->modelClass;
    //     $model->scenario = $model::SCENARIO_CREATE;

    //     try {
    //         if ($model->load(\Yii::$app->request->post(), '')) {
    //             if ($model->validate()) {
    //                 $model->save();

    //                 return [
    //                     "success" => true,
    //                     "message" => "Data berhasil dihapus"
    //                 ];
    //             }

    //             throw new \yii\web\HttpException(422);
    //         }
    //         throw new \yii\web\HttpException(400);
    //     } catch (\Throwable $th) {
    //         throw new \yii\web\HttpException($th->statusCode ?? 500, $th->getMessage());
    //     }
    // }

    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);
    //     $model->scenario = $model::SCENARIO_UPDATE;

    //     try {
    //         if ($model->load(\Yii::$app->request->post(), '')) {
    //             if ($model->validate()) {
    //                 $model->save();

    //                 return [
    //                     "success" => true,
    //                     "message" => "Data berhasil dihapus"
    //                 ];
    //             }

    //             throw new \yii\web\HttpException(422);
    //         }
    //         throw new \yii\web\HttpException(400);
    //     } catch (\Throwable $th) {
    //         throw new \yii\web\HttpException($th->statusCode ?? 500, $th->getMessage());
    //     }
    // }

    // public function actionDelete($id)
    // {
    //     $model = $this->findModel($id);

    //     try {
    //         $model->delete();
    //         return [
    //             "success" => true,
    //             "message" => "Data berhasil dihapus"
    //         ];
    //     } catch (\Throwable $th) {
    //         throw new \yii\web\HttpException($th->statusCode ?? 500, $th->getMessage());
    //     }
    // }
}
