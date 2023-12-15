<?php

namespace app\controllers\api\v1;

use app\models\LogApproval;
use Yii;
use yii\web\HttpException;

/**
 * This is the class for REST controller "ApprovalSebelumPekerjaanController".
 * Modified by Defri Indra
 */

class ApprovalSebelumPekerjaanController extends \app\controllers\api\BaseController
{
    public $modelClass = 'app\models\ApprovalSebelumPekerjaan';

    function verbs()
    {
        return [
            'index' => ['GET'],
            'view' => ['GET'],
            'konfirmasi' => ['POST'],
        ];
    }

    public function actionIndex($id_proyek)
    {
        $proyek = \app\models\Proyek::find()->where([
            'id' => $id_proyek,
            'flag' => 1,
            'id_user' => \Yii::$app->user->identity->id
        ])->one();
        if ($proyek == null) {
            throw new HttpException(404);
        }
        $query = $this->modelClass::find()->andWhere(['id_proyek' => $proyek->id])->select([
            'id',
            'nama_progress',
            'foto_material',
            'created_at',
            'status',
        ])->orderBy([
            'status' => [
                \app\models\ApprovalSebelumPekerjaan::STATUS_PENDING,
                \app\models\ApprovalSebelumPekerjaan::STATUS_REJECTED,
                \app\models\ApprovalSebelumPekerjaan::STATUS_APPROVED,
            ],
            'updated_at' => SORT_DESC,
        ]);
        return $this->dataProvider($query);
    }

    public function actionView($id)
    {
        $data = $this->findModel($id);
        if ($data->proyek->id_user != Yii::$app->user->id) {
            throw new HttpException(403);
        } else if ($data->proyek->flag != 1) {
            throw new HttpException(404);
        }
        return [
            "success" => true,
            "data" => $data
        ];
    }

    public function actionKonfirmasi($id)
    {
        $data = $this->findModel($id);

        if ($data->proyek->id_user != Yii::$app->user->id) {
            throw new HttpException(403);
        } else if ($data->proyek->flag != 1) {
            throw new HttpException(404);
        } else if ($data->status != \app\models\ApprovalSebelumPekerjaan::STATUS_PENDING) {
            throw new HttpException(403, "Status tidak sesuai");
        }

        $data->status = \app\models\ApprovalSebelumPekerjaan::STATUS_APPROVED;
        $data->save();

        // send notification to admin
        \app\components\Notif::log(
            null,
            "Approval sebelum pekerjaan telah dikonfirmasi",
            "Approval sebelum pekerjaan telah dikonfirmasi",
            [
                "controller" => "proyek/view",
                "android_route" => null,
                "params" => [
                    "id" => $data->id_proyek
                ]
            ]
        );

        LogApproval::copy($data, "Konfirmasi");

        return [
            "success" => true,
            "message" => "Data berhasil dikonfirmasi",
            "data" => $data
        ];
    }

    public function actionRevisi($id)
    {
        $data = $this->findModel($id);

        if ($data->proyek->id_user != Yii::$app->user->id) {
            throw new HttpException(403);
        } else if ($data->proyek->flag != 1) {
            throw new HttpException(404);
        } else if ($data->status == \app\models\ApprovalSebelumPekerjaan::STATUS_APPROVED) {
            throw new HttpException(403, "Data sudah di konfirmasi");
        }

        $params = Yii::$app->request->post();

        if ($params['revisi'] == "") {
            throw new HttpException(400, "Revisi tidak boleh kosong");
        }

        $data->revisi = $params['revisi'];
        $data->status = \app\models\ApprovalSebelumPekerjaan::STATUS_REJECTED;
        $data->save();


        // send notification to admin
        \app\components\Notif::log(
            null,
            "User mengajukan revisi Approval sebelum pekerjaan",
            "User mengajukan revisi Approval sebelum pekerjaan",
            [
                "controller" => "proyek/view",
                "android_route" => null,
                "params" => [
                    "id" => $data->id_proyek
                ]
            ]
        );

        LogApproval::copy($data, "Revisi");

        return [
            "success" => true,
            "message" => "Data berhasil direvisi",
            "data" => $data
        ];
    }
}
