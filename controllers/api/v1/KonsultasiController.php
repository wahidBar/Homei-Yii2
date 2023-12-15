<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "KonsultasiController".
 * Modified by Defri Indra
 */

use app\components\Constant;
use app\models\IsianLanjutan;
use app\models\Konsultasi;
use app\models\KonsultasiChat;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\Response;

class KonsultasiController extends \app\controllers\api\BaseController
{
    public $modelClass = 'app\models\Konsultasi';

    public function actionListChat($ticket)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $this->layout = false;
        $user = Constant::getUser();

        if ($user == null) return ["message" => "Authentikasi anda tidak valid", "success" => false];

        if ($user->role_id == Constant::ROLE_KONSULTAN) {
            $konsultasi = Konsultasi::findOne(['ticket' => $ticket, 'id_konsultan' => $user->id]);
        } else {
            $konsultasi = Konsultasi::findOne(['ticket' => $ticket, 'id_user' => $user->id]);
        }

        if ($konsultasi == null)  return ["message" => "Konsultasi tidak ditemukan", "success" => false];

        $chats = $konsultasi->getKonsultasiChats()->orderBy('t_konsultasi_chat.created_at ASC')->all();
        return ["success" => true, "data" => $chats];
    }

    public function actionChat($ticket = null)
    {
        $this->layout = false;
        $user = Constant::getUser();

        // add database transaction
        $transaction = Yii::$app->db->beginTransaction();

        try {
            //code...
            $model = Konsultasi::find()
                ->andWhere(['id_user' => $user->id, 'is_active' => 1])->one();
            if ($model == null) {
                $isian = new IsianLanjutan();
                $isian->scenario = $isian::SCENARIO_INITIAL_CREATE;
                $isian->id_user = \Yii::$app->user->identity->id;
                $isian->nama_awal = \Yii::$app->user->identity->name;
                $isian->kode_unik = Yii::$app->security->generateRandomString(30);
                if ($isian->validate() == false) throw new HttpException(400, Constant::flattenError($isian->getErrors()));
                $isian->save();

                $model = new Konsultasi();
                $model->id_user = $user->id;
                $model->id_isian_lanjutan = $isian->id;
                $model->kode_isian_lanjutan = $isian->kode_unik;
                $model->ticket = "Ticket-" . Yii::$app->security->generateRandomString(12);
                $model->searchKonsultan();
                $model->is_active = 1;
                if ($model->validate() == false) throw new HttpException(400, Constant::flattenError($model->getErrors()));
                $model->save();
                $transaction->commit();
            }

            return [
                "success" => true,
                "data" => $model
            ];
        } catch (\Throwable $th) {
            // throwable with http exception
            $transaction->rollBack();
            throw new HttpException(500, $th->getMessage());
        }
    }

    public function actionSaveChat($ticket)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $this->layout = false;
        $user = Yii::$app->user->identity;

        if ($user == null) return ["message" => "Authentikasi anda tidak valid", "success" => false];
        if ($user->role_id === Constant::ROLE_KONSULTAN) {
            $chat_active = Konsultasi::find()->where(['ticket' => $ticket, 'id_konsultan' => $user->id, 'is_active' => 1])->one();
            $chat_to = $chat_active->id_user;
        } else {
            $chat_active = Konsultasi::find()->where(['ticket' => $ticket, 'id_user' => $user->id, 'is_active' => 1])->one();
            $chat_to = $chat_active->id_konsultan;;
        }

        if ($chat_active->is_active == 1) {
            $model = new KonsultasiChat();
            $model->load($_POST, "");
            $model->user_id = Yii::$app->user->id;
            $model->konsultasi_id = $chat_active->id;
            if ($model->validate()) {
                $model->save();
                $data = KonsultasiChat::findOne(['id' => $model->id]);
                \app\components\Notif::log(
                    $chat_to,
                    'Pesan Baru',
                    $model->body,
                    [
                        'controller' => '/home/formulir-konsultasi',
                        'android_route' => 'app-konsultasi',
                    ]
                );
                return ["message" => "Data berhasil disimpan", "data" => $data];
            }
            return ["message" => Constant::flattenError($model->getErrors()), "success" => false];
        }
        return ["message" => "Gagal menyimpan pesan", "success" => false];
    }

    public function actionReadChat($ticket)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $user = Constant::getUser();
        if ($user == null) return ["success" => false, "message" => "Kresidensial tidak ditemukan. Silahkan login terlebih dahulu"];

        $konsultasi = Konsultasi::findOne(['ticket' => $ticket]);

        if ($konsultasi == null) return ["success" => false, "message" => "Konsultasi tidak ditemukan di sistem kami"];

        if ($user->role_id == Constant::ROLE_KONSULTAN) {
            if ($konsultasi->id_konsultan != $user->id)  return ["success" => false, "message" => "Anda tidak mempunyai hak akses"];
        } else {

            if ($konsultasi->id_user != $user->id) return ["success" => false, "message" => "Anda tidak mempunyai hak akses"];
        }

        KonsultasiChat::updateAll(['read' => 1], [
            'and',
            ['!=', 'user_id', $user->id],
            [
                'read' => 0,
                'konsultasi_id' => $konsultasi->id
            ]
        ]);

        return ["success" => true, "status di ubah ke telah dibaca"];
    }

    public function actionAkhiriChat()
    {
        // validate method post
        if (!Yii::$app->request->isPost) {
            throw new HttpException(400, 'Invalid request. Please do not repeat this request again.');
        }

        $user = Constant::getUser();
        $model = Konsultasi::find()
            ->andWhere(['id_user' => $user->id, 'is_active' => 1])->one();
        // if ($model->id_konsultan == null) throw new HttpException(400, "Tidak dapat mengakhiri konsultasi. Karena konsultasi belum diberikan konsultan");

        $model->is_active = 0;
        if ($model->validate()) {
            $model->save();
            return ["success" => true, "message" => "Konsultasi berhasil diakhiri"];
        }
        throw new HttpException(400, Constant::flattenError($model->getErrors()) ?? "Tidak menyimpan data");
    }
}
