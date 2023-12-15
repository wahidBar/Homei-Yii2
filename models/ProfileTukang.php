<?php

namespace app\models;

use app\components\Email;
use Yii;
use \app\models\base\ProfileTukang as BaseProfileTukang;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_profile_tukang".
 * Modified by Defri Indra M
 */
class ProfileTukang extends BaseProfileTukang
{
    public function getLayananLabel()
    {
        $layanans = explode(",", $this->id_layanan);
        $labels = [];
        foreach ($layanans as $layanan) {
            $m = \app\models\MasterKategoriLayananSameday::findOne($layanan);
            if ($m) {
                $labels[] = $m->nama_kategori_layanan;
            }
        }
        return implode(", ", $labels);
    }

    public function nonactivate()
    {
        $transaction = Yii::$app->db->beginTransaction();
        if ($this->status != 1) return 0;
        $this->status = 2;

        $user = $this->user;
        $user->status = 0; // disable login

        if ($this->save() && $user->save(false)) {
            $transaction->commit();
            return true;
        }

        $transaction->rollBack();
        return false;
    }

    public function activate()
    {
        $transaction = Yii::$app->db->beginTransaction();
        if ($this->status == 2) return 0;

        $this->status = 1;

        $user = $this->user;
        $user->status = 1;
        $user->flag = 1;

        if ($this->save() && $user->save(false)) {
            $transaction->commit();
            return true;
        }

        $transaction->rollBack();
        return false;
    }

    public function approve()
    {
        $transaction = Yii::$app->db->beginTransaction();
        if ($this->status != 0) {
            $transaction->rollBack();
            return 0;
        }

        if ($this->id_layanan == null) {
            $transaction->rollBack();
            $this->addError('id_layanan', 'Layanan harus diisi');
            return false;
        }

        if (is_array($this->id_layanan)) {
            foreach ($this->id_layanan as $id_layanan) {
                $m = \app\models\MasterKategoriLayananSameday::findOne($id_layanan);
                if (!$m) {
                    $transaction->rollBack();
                    $this->addError('id_layanan', 'Layanan tidak ditemukan');
                    return false;
                }
            }
            $this->id_layanan = implode(",", $this->id_layanan);
        }

        $this->status = 1;

        $user = $this->user;
        $user->flag = 1;
        $user->status = 1;
        if ($this->save() && $user->save(false)) {
            $transaction->commit();
            return true;
        }

        // send email
        Email::send($this->email, "Selamat Pendaftaran Tukang Sameday Anda telah disetujui", "Selamat Pendaftaran Tukang Sameday Anda telah disetujui.\n<br> Silahkan login untuk mengakses aplikasi.\n\n");

        $transaction->rollBack();
        return false;
    }
}
