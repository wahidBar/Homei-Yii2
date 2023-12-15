<?php

namespace app\models;

use Yii;
use \app\models\base\ProyekKemajuan as BaseProyekKemajuan;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "t_proyek_kemajuan".
 * Modified by Defri Indra M
 */
class ProyekKemajuan extends BaseProyekKemajuan
{
  private static function nestedSearchForApproval($p, $data = [], $level = 1)
  {
    if ($p->getChildren()->count() > 0) {
      $label = str_repeat('-', $level) . ' ' . $p->item;
      $data[$label] = [];
      foreach ($p->getChildren()->all() as $i => $c) {
        $data[$label] += static::nestedSearchForApproval($c, [], $level + 1);
      }
    } else {
      $data[$p->id] = $p->item;
    }

    return $data;
  }

  static function myFilter($var)
  {
    return ($var !== NULL && $var !== FALSE && $var !== "" && $var !== []);
  }


  public static function searchForApproval($id_proyek, $query = null)
  {
    $progressSelect2 = [];

    $parent = \app\models\ProyekKemajuan::find()->where(['and', ['is', 'id_parent', null], ['id_proyek' => $id_proyek]])->all();
    foreach ($parent as $p) {
      $progressSelect2[$p->item] = [];
      if ($p->getChildren()->count() > 0) {
        $progressSelect2[$p->item] = [];
        foreach ($p->getChildren()->all() as $c) {
          $progressSelect2[$p->item] +=  static::nestedSearchForApproval($c);
        }
      } else {
        $progressSelect2[$p->id] = $p->item;
      }
    }

    return array_filter($progressSelect2, [self::class, 'myFilter']);
  }

  public function getRealisasiPersentaseByRangeDate($tanggal1, $tanggal2)
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
                and t_proyek_kemajuan.id = '{$this->id}'
                and t_proyek_kemajuan.id_proyek = '{$this->id_proyek}'
            ) as target, 
            (
              select 
                sum(t_proyek_kemajuan_harian.bobot) as total 
              from 
                t_proyek_kemajuan_harian 
                inner join t_proyek_kemajuan 
                on t_proyek_kemajuan.id = t_proyek_kemajuan_harian.id_proyek_kemajuan
              where
                t_proyek_kemajuan.id = '{$this->id}'
                and t_proyek_kemajuan.flag = '1'
                and t_proyek_kemajuan_harian.id_proyek = '{$this->id_proyek}' 
                and t_proyek_kemajuan_harian.tanggal between '{$tanggal1}' and '{$tanggal2}'
            ) as realisasi;"
    )->queryScalar();

    /**
     * Catatan : jangan beri number_format, 
     * akan membuat nilai dari curva s 
     * dan realisasi proyek menjadi tidak sinkron
     */
    return number_format($hasil, Yii::$app->params['decimal']);
  }

  public function getRealisasiVolumeByRangeDate($tanggal1, $tanggal2)
  {

    $hasil = Yii::$app->db->createCommand(
      "
              select 
                sum(t_proyek_kemajuan_harian.volume) as total 
              from 
                t_proyek_kemajuan_harian 
                inner join t_proyek_kemajuan 
                on t_proyek_kemajuan.id = t_proyek_kemajuan_harian.id_proyek_kemajuan
              where
                t_proyek_kemajuan.id = '{$this->id}'
                and t_proyek_kemajuan.flag = '1'
                and t_proyek_kemajuan_harian.id_proyek = '{$this->id_proyek}' 
                and t_proyek_kemajuan_harian.tanggal between '{$tanggal1}' and '{$tanggal2}';"
    )->queryScalar();

    return number_format($hasil, Yii::$app->params['decimal']);
  }

  public function getRealisasiBobotByRangeDate($tanggal1, $tanggal2)
  {

    $hasil = Yii::$app->db->createCommand(
      "
              select 
                sum(t_proyek_kemajuan_harian.bobot) as total 
              from 
                t_proyek_kemajuan_harian 
                inner join t_proyek_kemajuan 
                on t_proyek_kemajuan.id = t_proyek_kemajuan_harian.id_proyek_kemajuan
              where
                t_proyek_kemajuan.id = '{$this->id}'
                and t_proyek_kemajuan.flag = '1'
                and t_proyek_kemajuan_harian.id_proyek = '{$this->id_proyek}' 
                and t_proyek_kemajuan_harian.tanggal between '{$tanggal1}' and '{$tanggal2}';"
    )->queryScalar();

    return number_format($hasil, Yii::$app->params['decimal']);
  }

  public function lastWeekDate($minggu_ini = null)
  {
    $last_week_date = $this->proyek->tanggal_awal_kontrak;
    if ($minggu_ini == null) {
      $minggu_ini = time();
    } else {
      $minggu_ini = strtotime($minggu_ini);
    }

    do {
      $last_week_date = date('Y-m-d', strtotime('+7 day', strtotime($last_week_date)));
    } while (strtotime($last_week_date) <= $minggu_ini);

    $last_week_date = date('Y-m-d', strtotime('-7 day', strtotime($last_week_date)));
    return $last_week_date;
  }

  public function sdMingguLalu($minggu_ini = null)
  {
    $start_date = $this->proyek->tanggal_awal_kontrak;
    $last_week_date = $this->lastWeekDate($minggu_ini);

    $persentase = $this->getRealisasiPersentaseByRangeDate($start_date, $last_week_date);
    $bobot = $this->getRealisasiBobotByRangeDate($start_date, $last_week_date);
    $volume = $this->getRealisasiVolumeByRangeDate($start_date, $last_week_date);

    return [$volume, $persentase, $bobot];
  }

  public function mingguIni($minggu_ini = null)
  {
    $start_date = $this->lastWeekDate($minggu_ini);
    $last_week_date = date('Y-m-d', strtotime('+7 day', strtotime($start_date)));

    $persentase = $this->getRealisasiPersentaseByRangeDate($start_date, $last_week_date);
    $bobot = $this->getRealisasiBobotByRangeDate($start_date, $last_week_date);
    $volume = $this->getRealisasiVolumeByRangeDate($start_date, $last_week_date);

    return [$volume, $persentase, $bobot];
  }

  public function sdMingguIni($minggu_ini = null)
  {
    $start_date = $this->proyek->tanggal_awal_kontrak;
    $last_week_date = date('Y-m-d', strtotime('+7 day', strtotime($this->lastWeekDate($minggu_ini))));


    $persentase = $this->getRealisasiPersentaseByRangeDate($start_date, $last_week_date);
    $bobot = $this->getRealisasiBobotByRangeDate($start_date, $last_week_date);
    $volume = $this->getRealisasiVolumeByRangeDate($start_date, $last_week_date);

    return [$volume, $persentase, $bobot];
  }

  // https://stackoverflow.com/questions/36523356/get-all-data-of-parent-child-relation-ship-from-same-table-in-mysql/36523552#36523552
  public function getAllChildren()
  {
    return Yii::$app->db->createCommand("select GROUP_CONCAT(id)
      from    (select id,id_parent from t_proyek_kemajuan
               order by id_parent, id) t_proyek_kemajuan,
              (select @pv := '{$this->id}') initialisation
      where   find_in_set(id_parent, @pv)
      and     @pv := concat(@pv, ',', id);")->queryScalar();
  }

  // https://stackoverflow.com/questions/36523356/get-all-data-of-parent-child-relation-ship-from-same-table-in-mysql/36523552#36523552
  // https://stackoverflow.com/questions/36523356/get-all-data-of-parent-child-relation-ship-from-same-table-in-mysql/36523552#36523552
  public function getListGrandMasterParent()
  {
    $data = Yii::$app->db->createCommand("
            SELECT id,id_parent FROM
              (SELECT id,id_parent,
                CASE WHEN id = '{$this->id}' THEN @id := id_parent
                      WHEN id = @id THEN @id := id_parent
                      END as checkId
              FROM t_proyek_kemajuan
              ORDER BY id DESC) as T
            WHERE checkId IS NOT NULL")->queryAll();

    // return last index
    return $data;
  }

  public function getGrandMasterParent()
  {
    $data = $this->getListGrandMasterParent();
    $last = end($data);
    return $last['id_parent'];
  }

  public static function getStatuses()
  {
    return [
      '0' => 'On Schedule',
      '1' => 'On Site',
      '2' => 'On Progress',
      '3' => 'Completed',
    ];
  }

  public function getStatus()
  {
    return static::getStatuses()[$this->status_verifikasi];
  }
}
