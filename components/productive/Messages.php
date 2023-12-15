<?php

namespace app\components\productive;

use Yii;
use yii\helpers\Url;

trait Messages
{
    public function messageValidationError()
    {
        $message = "Telah terjadi kesalahan";
        Yii::$app->session->setFlash("error", $message);
        return $message;
    }

    public function messageValidationFailed($error = null)
    {
        $message = "Validasi gagal $error";
        Yii::$app->session->setFlash("error", $message);
        return $message;
    }

    public function messageCreateSuccess($name = "Data")
    {
        $message = "$name Berhasil di buat";
        Yii::$app->session->setFlash("success", $message);
        return $message;
    }

    public function messageUpdateSuccess($name = "Data")
    {
        $message = "$name Berhasil di ubah";
        Yii::$app->session->setFlash("success", $message);
        return $message;
    }

    public function messageDeleteSuccess($name = "Data")
    {
        $message = "$name Berhasil di hapus";
        Yii::$app->session->setFlash("success", $message);
        return $message;
    }

    public function messageNonaktifSuccess($name = "Data")
    {
        $message = "$name Berhasil di nonaktifkan";
        Yii::$app->session->setFlash("success", $message);
        return $message;
    }

    public function messageAktifSuccess($name = "Data")
    {
        $message = "$name Berhasil di Aktifkan";
        Yii::$app->session->setFlash("success", $message);
        return $message;
    }

    public function messageAccessForbidden($name = "Data")
    {
        $message = "Fungsi ini belum bisa dijalankan, $name belum memenuhi standar";
        Yii::$app->session->setFlash("error", $message);
        return $message;
    }

    public function messageAccessRunning($name = "Data")
    {
        $message = "Fungsi ini berhasil dijalankan";
        Yii::$app->session->setFlash("success", $message);
        return $message;
    }

    public function messageDeleteForbidden($name = "Data")
    {
        $message = "$name gagal di hapus karena sudah di konfirmasi.";
        Yii::$app->session->setFlash("error", $message);
        return $message;
    }

    public function messageLetterNumberNotFound($name = "Penomoran")
    {
        $message = "$name belum di atur, silahkan atur terlebih dahulu / hubungi administrator untuk mengatur penomoran pada surat ini.";
        Yii::$app->session->setFlash("error", $message);
        return $message;
    }

    public function messageFailedSendWa($name = "Data")
    {
        $message = "Gagal mengirim notifikasi Whatapps ketika $name di konfirmasi.";
        Yii::$app->session->setFlash("error", $message);
        return $message;
    }
}
