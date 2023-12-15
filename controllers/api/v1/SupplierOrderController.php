<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "SupplierOrderController".
 * Modified by Defri Indra
 */

use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class SupplierOrderController extends \app\controllers\api\BaseController
{
    use \app\components\UploadFile;
    public $modelClass = 'app\models\SupplierOrder';

    public function actionGetStatus()
    {
        $data = \app\models\SupplierOrder::getStatuses();
        $template = [];
        foreach ($data as $key => $value) {
            $template[] = [
                'id' => $key,
                'nama_status' => $value
            ];
        }
        return [
            "success" => true,
            "data" => $template
        ];
    }

    public function actionIndex($status = null)
    {
        $query = $this->modelClass::find()
            ->andWhere(['user_id' => \Yii::$app->user->id])
            ->select([
                "id",
                "kode_unik",
                "nama_penerima",
                "no_nota",
                "total_harga",
                "deadline_bayar",
                "alamat",
                "status",
                "created_at",
            ])
            ->orderBy([
                'id' => SORT_DESC
            ]);

        if ($status != null) {
            $query->andWhere(['status' => $status]);
        }

        return $this->dataProvider($query);
    }

    public function actionView($id)
    {
        $data = $this->findModel($id);
        return [
            "success" => true,
            "data" => $data
        ];
    }

    /**
     * actionDiterima
     * @param string $uniq
     * @return array
     */
    public function actionDiterima($uniq)
    {
        // check if method is allowed
        if (\app\components\Constant::isMethod(['post']) == false) {
            throw new \yii\web\HttpException(405, "Method tidak di izinkan");
        }

        // check if model exist and status is belum diterima
        $user = \app\components\Constant::getUser();
        $model = \app\models\SupplierOrder::find()->where([
            'user_id' => $user->id,
            'kode_unik' => $uniq,
            'status' => 2 // telah lunas
        ])->one();

        // throw exception if model is null
        if ($model == null) throw new \yii\web\HttpException(400, "Pesanan tidak ditemukan");

        try {
            // change status to diterima, validate, and save model
            $model->status = 4;
            $model->tanggal_diterima = date("Y-m-d H:i:s");
            $model->bukti_diterima = Yii::$app->request->post('keterangan_diterima');

            // upload image bukti_diterima
            $file = \yii\web\UploadedFile::getInstanceByName("bukti_diterima");
            $response = $this->uploadImage($file, $model->relativeUploadPath());
            if ($response->success == false) throw new \yii\web\HttpException(400, "Gagal mengunggah gambar");
            $model->bukti_diterima = $response->filename;

            if ($model->validate() == false) throw new \yii\web\HttpException(400, \app\components\Constant::flattenError($model->getErrors()));
            $model->save();

            $log = new \app\models\SupplierPengiriman();
            $log->supplier_order_id = $model->id;
            $log->kode_supplier_order = $model->kode_unik;
            $log->kode_unik =  Yii::$app->security->generateRandomString(30);
            $log->tanggal = date('Y-m-d H:i:s');
            $log->keterangan = "Pesanan telah diterima";

            $log->save();

            // send notification to admin
            \app\components\Notif::log(
                null,
                "Pesanan " . $model->no_nota . " telah diterima",
                "Pesanan " . $model->no_nota . " telah diterima oleh konsumen",
                [
                    "controller" => "supplier-order/view",
                    "android_route" => null,
                    "params" => [
                        "id" => $model->id
                    ]
                ]
            );

            // return success message
            return [
                "success" => true,
                "message" => "Pesanan telah diterima",
                "data" => $model
            ];
        } catch (\Throwable $th) {
            // throwable exception
            throw new \yii\web\HttpException($th->statusCode ?? 500, $th->message ?? $th->getMessage() ?? "Telah terjadi kesalahan");
        }
    }
}
