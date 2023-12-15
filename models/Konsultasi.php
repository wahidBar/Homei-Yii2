<?php

namespace app\models;

use app\components\Constant;
use Yii;
use \app\models\base\Konsultasi as BaseKonsultasi;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_konsultasi".
 * Modified by Defri Indra M
 */
class Konsultasi extends BaseKonsultasi
{
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
            ]
        );
    }

    public function getStatus()
    {
        return $this->status ? "Aktif" : "Tidak Aktif";
    }

    public function searchKonsultan()
    {
        if ($this->id_konsultan == null) {
            $cek_id_teratas = Konsultasi::find()->where(['is not', 'id_konsultan', null])->orderBy('created_at DESC')->one(); // id cari konsultan terakhir yang menangani klien
            if ($cek_id_teratas == null) {
                $user = User::find()->where(
                    [
                        'role_id' => Constant::ROLE_KONSULTAN,
                        'is_active' => 1,
                    ]
                )->one();
            } else {
                $user = User::find()
                    ->where([
                        'and',
                        [
                            'role_id' => Constant::ROLE_KONSULTAN,
                            'is_active' => 1,
                        ],
                        [
                            '>',
                            'id',
                            $cek_id_teratas->id_konsultan
                        ]
                    ])
                    ->orderBy('id ASC')
                    ->one();
                if ($user == null) {
                    $user = User::find()->where([
                        'role_id' => Constant::ROLE_KONSULTAN,
                        'is_active' => 1,
                    ])->one();
                }
            }

            if ($user != null) {
                $this->id_konsultan = $user->id;
            }
        }
    }

    public function getTotalChatBelumDibaca()
    {
        $user = Constant::getUser();
        $total_chat = $this->getKonsultasiChats()->andWhere([
            'and',
            ['!=', 't_konsultasi_chat.user_id', Yii::$app->user->id],
            [
                'read' => 0,
            ],
        ])->count();
        return $total_chat;
    }
}
