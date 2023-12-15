<?php

namespace app\controllers\api\v1\tukang;

use app\components\Constant;
use app\components\UploadFile;
use Yii;
use yii\web\HttpException;

/**
 * This is the class for REST controller "MasterMaterialController".
 * Modified by Defri Indra
 */

class PekerjaanController extends \app\controllers\api\BaseController
{
    use UploadFile;

    public $modelClass = 'app\models\PekerjaanSameday';

    public function behaviors()
    {
        $parent = parent::behaviors();
        $parent['authentication']['except'][] = 'register-tukang';
        return $parent;
    }

    /**
     * isUserTukang
     * 
     * @return bool
     */
    private function isUserTukang()
    {
        $user = Yii::$app->user->identity;
        if ($user != null && $user->role_id == Constant::ROLE_TUKANG_SAMEDAY) {
            return true;
        }

        throw new HttpException(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
    }

    /**
     * actionRegisterTukang
     * action-id: /api/v1/tukang/register-tukang
     * action-name: register-tukang
     * action-desc: Register tukang sameday
     * action input: 
     */
    public function actionRegisterTukang()
    {
        $config = \app\models\Config::find()->where(['name' => 'form-registrasi-user'])->one();
        if ($config == null) throw new HttpException(404);
        if (intval($config->value) == 0) throw new HttpException(404);
        try {
            $model = new \app\models\RegisterTukangForm();
            if ($model->load(Yii::$app->request->post(), '')) {
                if ($model->validate() == false) {
                    return [
                        "success" => false,
                        "message" => Constant::flattenError($model->getErrors()),
                    ];
                }

                if($model->register()){
                    return $model;
                } else {
                    return [
                        "success" => false,
                        "message" => Constant::flattenError($model->getErrors()),
                    ];
                }
            }
        } catch (\Throwable $th) {
            throw new HttpException(500, $th->getMessage());
        }
        throw new HttpException(400, 'Bad Request');
    }

    /**
     * actionIndex
     * action-id: /api/v1/tukang/pekerjaan/get-status
     * action-desc: Get all status pekerjaan sameday
     * action-input:
     * action-author: Defri Indra
     * action-type: GET
     * action-url-path: /api/v1/tukang/pekerjaan/get-status
     * action-success-code: 200
     * action-return:
     */
    public function actionGetStatus()
    {
        $this->isUserTukang();

        $status = \app\models\PekerjaanSameday::getStatuses();
        return $status;
    }

    /**
     * actionIndex
     * action-id: api/v1/tukang/pekerjaan/index
     * action-desc: Get all pekerjaan
     * @return \yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        $this->isUserTukang();
        $query = $this->modelClass::find()
            ->andWhere(['id_tukang' => Yii::$app->user->id])
            ->orderBy(['id' => SORT_DESC]);
        return $this->dataProvider($query);
    }

    /**
     * actionView
     * action-id: api/v1/tukang/pekerjaan/view
     * action-desc: Get pekerjaan by id
     * @param integer $id
     * @return \yii\data\ActiveDataProvider
     */
    public function actionView($id)
    {
        $this->isUserTukang();
        $data = $this->findModel([
            "kode_unik" => $id,
            'id_tukang' => Yii::$app->user->id
        ]);
        return [
            "success" => true,
            "data" => $data
        ];
    }

    /**
     * actionPengajuan
     * action-id: api/v1/tukang/pekerjaan/pengajuan
     * action-desc: Get pengajuan pekerjaan by id
     * @param integer $id
     * @return \yii\data\ActiveDataProvider
     */
    public function actionPengajuan($id)
    {
        $this->isUserTukang();
        $model = $this->findModel([
            "kode_unik" => $id,
            'id_tukang' => Yii::$app->user->id
        ]);

        $old_image = $model->foto_pengerjaan;
        $model->scenario = $this->modelClass::SCENARIO_PENGAJUAN;
        if (in_array($model->status, [$this->modelClass::STATUS_PENGERJAAN, $this->modelClass::STATUS_DIAJUKAN]) == false) {
            throw new HttpException(400, "Status tidak sesuai, anda tidak dapat menjalankan aksi ini.");
        }

        try {
            if ($model->load(Yii::$app->request->post(), '')) {
                $instance = \yii\web\UploadedFile::getInstanceByName("foto_pengerjaan");
                if ($old_image != null) {
                    if ($instance) {
                        $response = $this->uploadImage($instance, "sameday");
                        if ($response->success == false) {
                            throw new HttpException(400, "Gagal mengunggah gambar");
                        }
                        $model->foto_pengerjaan = $response->filename;
                    } else {
                        $model->foto_pengerjaan = $old_image;
                    }
                } else {
                    $response = $this->uploadImage($instance, "sameday");
                    if ($response->success == false) {
                        throw new HttpException(400, "Gagal mengunggah gambar");
                    }

                    $model->foto_pengerjaan = $response->filename;
                }

                $model->status = $this->modelClass::STATUS_DIAJUKAN;
                if ($model->validate()) {
                    $model->catatan_revisi = null;
                    $model->save();
                    \app\components\Notif::log(
                        $model->id_pelanggan,
                        "Pekerja Telah Mengajukan Report Pekerjaan",
                        "Hallo {$model->pelanggan->name}, anda mempunyai report baru di pekerjaan sameday anda.",
                        [
                            "controller" => "home/cari-tukang/view",
                            "android_route" => "app-sameday-detail",
                            "params" => [
                                "id" => $model->kode_unik
                            ]
                        ]
                    );

                    return [
                        "success" => true,
                        "message" => "Pengerjaan berhasil diajukan. Menunggu Response Owner"
                    ];
                }

                throw new HttpException(400, \app\components\Constant::flattenError($model->getErrors()));
            }
        } catch (\Throwable $th) {
            throw new HttpException($th->statusCode ?? 500, $th->getMessage() ?? "Telah terjadi kesalahan");
        }

        throw new HttpException(400);
    }
}
