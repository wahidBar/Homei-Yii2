<?php

namespace app\models;

use Yii;
use \app\models\base\IsianLanjutan as BaseIsianLanjutan;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_isian_lanjutan".
 * Modified by Defri Indra M
 */
class IsianLanjutan extends BaseIsianLanjutan
{
    const STATUS_DEFAULT = 0;
    const STATUS_USER_ISI = 1;
    const STATUS_ADMIN_SURVEY = 2;
    const STATUS_ADMIN_ISI_PENAWARAN = 3;
    const STATUS_DEAL_USER = 4;
    const STATUS_UPLOAD_TOR = 5;
    const STATUS_SETUJU_TOR = 6;
    const STATUS_TOR_BUTUH_REVISI = 7;
    const STATUS_RENCANA_PEMBANGUNAN = 8;
    const STATUS_DEAL_RENCANA_PEMBANGUNAN = 9;
    const STATUS_REVISI_RENCANA_PEMBANGUNAN = 10;
    const STATUS_TOLAK = 11;
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
    public function getListRuangan()
    {
        $categories = MasterRuangan::find()->where(['id' => $this->getIsianLanjutanRuangans()->select('id_ruangan')])->column();
        return $categories;
    }

    public static function getStatuses()
    {
        return [
            "Belum Melengkapi Form",
            "Telah Melengkapi Form<br> (Menunggu Survey Admin)",
            "Admin Telah Mengisi<br> Survey",
            "Pengajuan Penawaran",
            "User Telah Memilih<br> Penawaran",
            "TOR Telah Diupload",
            "TOR Telah Disetujui",
            "TOR Butuh Direvisi",
            "User Telah Mengisi<br> Rencana Pembangunan",
            "Admin Menyetujui<br> Rencana Pembangunan",
            "Admin Meminta Revisi<br> Rencana Pembangunan",
            "Ditolak"
        ];
    }

    public static function getLabels()
    {
        return [
            '<span class="badge badge-pill pl-3 pr-3 badge-secondary">{status}</span>',
            '<span class="badge badge-pill pl-3 pr-3 badge-info">{status}</span>',
            '<span class="badge badge-pill pl-3 pr-3 badge-info">{status}</span>',
            '<span class="badge badge-pill pl-3 pr-3 badge-info">{status}</span>',
            '<span class="badge badge-pill pl-3 pr-3 badge-info">{status}</span>',
            '<span class="badge badge-pill pl-3 pr-3 badge-info">{status}</span>',
            '<span class="badge badge-pill pl-3 pr-3 badge-info">{status}</span>',
            '<span class="badge badge-pill pl-3 pr-3 badge-info">{status}</span>',
            '<span class="badge badge-pill pl-3 pr-3 badge-info">{status}</span>',
            '<span class="badge badge-pill pl-3 pr-3 badge-success">{status}</span>',
            '<span class="badge badge-pill pl-3 pr-3 badge-warning">{status}</span>',
            '<span class="badge badge-pill pl-3 pr-3 badge-danger">{status}</span>',
        ];
    }

    public function getStatus()
    {
        $label = static::getLabels()[$this->status];
        $status = static::getStatuses()[$this->status];
        return str_replace("{status}", $status, $label);
    }
}
