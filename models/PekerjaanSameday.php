<?php

namespace app\models;

use Yii;
use \app\models\base\PekerjaanSameday as BasePekerjaanSameday;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_pekerjaan_sameday".
 * Modified by Defri Indra M
 */
class PekerjaanSameday extends BasePekerjaanSameday
{
    const STATUS_PELENGKAPAN_DATA = 0;
    const STATUS_SURVEY_LOKASI = 1;
    const STATUS_PEMBAYARAN_DP = 2;
    const STATUS_PENGERJAAN = 3;
    const STATUS_DIAJUKAN = 4;
    const STATUS_PEMBAYARAN = 5; // pembayaran gagal, status kembali ke sini
    const STATUS_SELESAI = 6;
    const STATUS_PEMBAYARAN_DP_EXPIRED = 7;
    const STATUS_PEMBAYARAN_AKHIR_EXPIRED = 8;

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

    static function getStatuses()
    {
        // 0:pelengkapan data;1:survey;2:pembayaran;3:pengerjaan;4:diajukan;5:revisi;6:selesai
        return [
            static::STATUS_PELENGKAPAN_DATA => "STATUS PELENGKAPAN DATA",
            static::STATUS_SURVEY_LOKASI => "STATUS SURVEY LOKASI",
            static::STATUS_PEMBAYARAN_DP => "STATUS PEMBAYARAN DP",
            static::STATUS_PENGERJAAN => "STATUS PENGERJAAN",
            static::STATUS_DIAJUKAN => "STATUS DIAJUKAN",
            static::STATUS_PEMBAYARAN => "STATUS PEMBAYARAN",
            static::STATUS_SELESAI => "STATUS SELESAI",
            static::STATUS_PEMBAYARAN_DP_EXPIRED => "STATUS PEMBAYARAN DP KADALUARSA",
            static::STATUS_PEMBAYARAN_AKHIR_EXPIRED => "STATUS PEMBAYARAN AKHIR KADALUARSA",
        ];
    }

    function getStatus()
    {
        return static::getStatuses()[$this->status];
    }

    public function getViewKategori()
    {
        $kategori = MasterKategoriLayananSameday::find()->where(['id' => explode(",", $this->id_kategori)])->select('nama_kategori_layanan')->column();
        if ($kategori == []) return "";
        return implode("\n", $kategori);
    }
}
