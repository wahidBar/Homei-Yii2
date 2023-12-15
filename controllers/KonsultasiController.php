<?php

namespace app\controllers;

use app\components\Constant;
use app\components\SSOToken;
use app\models\Konsultasi;
use app\models\KonsultasiChat;
use Yii;
use yii\web\Response;

/**
 * This is the class for controller "KonsultasiController".
 * Modified by Defri Indra
 */
class KonsultasiController extends \app\controllers\base\KonsultasiController
{
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
            $chat_to = $chat_active->id_konsultan;
        }

        if ($chat_active->is_active == 1) {
            $model = new KonsultasiChat();
            $model->load($_POST, "");
            $model->user_id = Yii::$app->user->id;
            $model->konsultasi_id = $chat_active->id;
            if ($model->validate()) {
                \app\components\Notif::log(
                    $chat_to,
                    'Pesan Baru - Konsultasi',
                    $model->body,
                    [
                        'controller' => '/home/formulir-konsultasi',
                        'android_route' => 'app-konsultasi',
                    ]
                );
                $model->save();
                $data = KonsultasiChat::findOne(['id' => $model->id]);
                return ["message" => "Data berhasil disimpan", "data" => $data];
            }
            return ["message" => Constant::flattenError($model->getErrors()), "success" => false];
        }
        return ["message" => "Gagal menyimpan pesan", "success" => false];
    }

    public function actionEndChat($ticket)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $this->layout = false;
        $user = Yii::$app->user->identity;

        if ($user == null) return ["message" => "Authentikasi anda tidak valid", "success" => false];

        if ($user->role_id === Constant::ROLE_KONSULTAN) {
            $chat_active = Konsultasi::find()->where(['ticket' => $ticket, 'id_konsultan' => $user->id, 'is_active' => 1])->one();
        } else {
            $chat_active = Konsultasi::find()->where(['ticket' => $ticket, 'id_user' => $user->id, 'is_active' => 1])->one();
        }

        if ($chat_active->is_active == 1) {
            $chat_active->is_active = 0;
            if ($chat_active->validate()) {
                $chat_active->save();
                return ["message" => "Data berhasil disimpan"];
            }
            return ["message" => Constant::flattenError($chat_active->getErrors()), "success" => false];
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
}
