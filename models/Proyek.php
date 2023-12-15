<?php

namespace app\models;

use app\components\Angka;
use app\components\Tanggal;
use Yii;
use \app\models\base\Proyek as BaseProyek;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_proyek".
 * Modified by Defri Indra M
 */
class Proyek extends BaseProyek
{

    const STATUS_PROYEK_BERJALAN = 0;
    const STATUS_PENGAJUAN_SELESAI = 1;
    const STATUS_REVISI_PROYEK = 2;
    const STATUS_SELESAI = 3;

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
            static::STATUS_PROYEK_BERJALAN => "STATUS PROYEK SEDANG BERJALAN",
            static::STATUS_PENGAJUAN_SELESAI => "STATUS PENGAJUAN SELESAI",
            static::STATUS_REVISI_PROYEK => "STATUS REVISI PROYEK",
            static::STATUS_SELESAI => "STATUS SELESAI",
        ];
    }

    function getStatus()
    {
        return static::getStatuses()[$this->status];
    }

    public function getSisaHari($format = "text")
    {
        $now = time();
        if ($now < strtotime($this->tanggal_awal_kontrak)) {
            $sisa_waktu = "-";
        } else {
            $sisa_waktu = (int)((strtotime($this->tanggal_akhir_kontrak) - $now) / (60 * 60 * 24));
        }

        if ($format == "text") return $sisa_waktu;
        else if ($format == "html") {
            $color = $this->getColor();
            return "<span class='badge badge-$color'>$sisa_waktu hari</span>";
        }
    }

    public function getColor()
    {

        $now = time();
        if ($now < strtotime($this->tanggal_awal_kontrak)) {
            $sisa_waktu = "-";
        } else {
            $sisa_waktu = (int)((strtotime($this->tanggal_akhir_kontrak) - $now) / (60 * 60 * 24));
        }

        $color = "primary";

        if (is_int($sisa_waktu) == false) {
            // $color = "primary";
        } else if ($sisa_waktu <= 0) {
            $color = "danger";
        } else if ($sisa_waktu <= 5) {
            $color = "warning";
        }

        return $color;
    }

    public function getRealisasiDana()
    {

        $total_anggaran = $this->getProyekKeuanganMasuks()
            ->andWhere(['is', 't_proyek_keuangan_masuk.deleted_at', null])
            ->sum('jumlah');
        $total_pengeluaran = (intval($this->getProyekKeuanganKeluars()
            ->andWhere(['and', ['is', 't_proyek_keuangan_keluar.deleted_at', null], ['t_proyek_keuangan_keluar.tipe' => 0]])
            ->sum('total_jumlah'))
            + intval($this->getProyekKeuanganKeluars()
                ->andWhere(['and', ['is', 't_proyek_keuangan_keluar.deleted_at', null], ['t_proyek_keuangan_keluar.tipe' => 1]])
                ->sum('total_dibayarkan')));

        return "<table class='mx-auto'><tbody style='text-align: left;'><tr><td>Total Anggaran</td><td> :</td><td> " . Angka::toReadableHarga($total_anggaran) . "</td></tr>" .
            "<tr><td>Total Pengeluaran</td><td> :</td><td> " . Angka::toReadableHarga($total_pengeluaran) . "</td></tr></tbody></table>";
    }

    public function getTotalAnggaran()
    {
        $total_anggaran = $this->getProyekKeuanganMasuks()
            ->andWhere(['is', 't_proyek_keuangan_masuk.deleted_at', null])
            ->sum('jumlah');
        return Angka::toReadableHarga($total_anggaran);
    }

    public function getTotalPengeluaran()
    {
        $total_pengeluaran = (intval($this->getProyekKeuanganKeluars()
            ->andWhere(['and', ['is', 't_proyek_keuangan_keluar.deleted_at', null], ['t_proyek_keuangan_keluar.tipe' => 0]])
            ->sum('total_jumlah'))
            + intval($this->getProyekKeuanganKeluars()
                ->andWhere(['and', ['is', 't_proyek_keuangan_keluar.deleted_at', null], ['t_proyek_keuangan_keluar.tipe' => 1]])
                ->sum('total_dibayarkan')));

        return Angka::toReadableHarga($total_pengeluaran);
    }

    public function getRealisasiProyek()
    {
        // $total_bobot = doubleval($this->getProyekKemajuans()->where(['is', 'deleted_at', null])->sum('bobot'));
        // $bobot = doubleval($this->getProyekKemajuans()->where(['is', 'deleted_at', null])->sum('bobot_kemajuan'));
        // if ($bobot == 0) return "0.00 %";
        // $persen = ($bobot / $total_bobot) * 100;

        $hasil = Yii::$app->db->createCommand("select (sum(bobot_kemajuan) / sum(bobot)) * 100 from t_proyek_kemajuan where flag=1 and id_proyek='{$this->id}'")->queryScalar();

        return number_format($hasil, 2) . " %";
    }

    public function getRealisasiByDate($tanggal)
    {

        $hasil = Yii::$app->db->createCommand(
            "select 
            coalesce(
              (
                (realisasi.total / target.total) * 100
              ), 
              0
            ) as realisasi 
          from 
            (
              select 
                sum(t_proyek_kemajuan.bobot) as total 
              from 
                t_proyek_kemajuan 
              where 
                flag=1
                and t_proyek_kemajuan.id_proyek = '{$this->id}'
            ) as target, 
            (
              select 
                sum(t_proyek_kemajuan_harian.bobot) as total 
              from 
                t_proyek_kemajuan_harian 
                inner join t_proyek_kemajuan 
                on t_proyek_kemajuan.id = t_proyek_kemajuan_harian.id_proyek_kemajuan
              where
                t_proyek_kemajuan.flag = '1'
                and t_proyek_kemajuan_harian.id_proyek = '{$this->id}' 
                and t_proyek_kemajuan_harian.tanggal = '{$tanggal}'
            ) as realisasi;"
        )->queryScalar();

        /**
         * Catatan : jangan beri number_format, 
         * akan membuat nilai dari curva s 
         * dan realisasi proyek menjadi tidak sinkron
         */
        return $hasil;
    }

    public function getRealisasiByRangeDate($tanggal1, $tanggal2)
    {

        $hasil = Yii::$app->db->createCommand(
            "select 
            coalesce(
              (
                (realisasi.total / target.total) * 100
              ), 
              0
            ) as realisasi 
          from 
            (
              select 
                sum(t_proyek_kemajuan.bobot) as total 
              from 
                t_proyek_kemajuan 
              where 
                flag=1
                and t_proyek_kemajuan.id_proyek = '{$this->id}'
            ) as target, 
            (
              select 
                sum(t_proyek_kemajuan_harian.bobot) as total 
              from 
                t_proyek_kemajuan_harian 
                inner join t_proyek_kemajuan 
                on t_proyek_kemajuan.id = t_proyek_kemajuan_harian.id_proyek_kemajuan
              where
                t_proyek_kemajuan.flag = '1'
                and t_proyek_kemajuan_harian.id_proyek = '{$this->id}' 
                and t_proyek_kemajuan_harian.tanggal between '{$tanggal1}' and '{$tanggal2}'
            ) as realisasi;"
        )->queryScalar();

        /**
         * Catatan : jangan beri number_format, 
         * akan membuat nilai dari curva s 
         * dan realisasi proyek menjadi tidak sinkron
         */
        return $hasil;
    }

    public static function getJenisPembayaranList()
    {
        return [
            Yii::t("cruds", "Jenis Belum Dipilih"),
            Yii::t("cruds", "Pembayaran Cash"),
            Yii::t("cruds", "Pembayaran Cicilan"),
        ];
    }

    public function getJenisPembayaran()
    {
        return static::getJenisPembayaranList()[$this->jenis_pembayaran];
    }

    public static function getStatusPembayaranList()
    {
        return [
            "Belum Membayar",
            "Telah Membayar (belum dicek)",
            "Pembayaran Masuk",
            "Pembayaran Ditolak",
        ];
    }

    public function getStatusPembayaran()
    {
        return static::getStatusPembayaranList()[$this->status_pembayaran];
    }

    public function getSisaBiayaProgress()
    {
        $total_tergunakan = \app\models\ProyekKemajuan::find()
            ->where(['id_proyek' => $this->id])
            ->sum('nilai_biaya');

        $bobot = \app\models\ProyekKemajuan::find()
            ->where([
                'and',
                ['id_proyek' => $this->id],
                ['is', 'nilai_biaya', null]
            ])
            ->sum('bobot');

        $sisa_anggaran = $this->nilai_kontrak - $total_tergunakan;

        if ($bobot != 0) $sisa_anggaran = $sisa_anggaran - ($sisa_anggaran * ($bobot / 100));

        return $sisa_anggaran;
    }

    public function getTotalBobot()
    {
        return number_format($this->getProyekKemajuans()
            ->where(['flag' => 1])
            ->sum('bobot'), 5);
    }
}
