<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build
// Modified by Defri Indra
// 2021

namespace app\models\base;

use app\components\Constant;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "t_proyek".
 *
 * @property integer $id
 * @property string $nama_proyek
 * @property string $deskripsi_proyek
 * @property integer $nilai_kontrak
 * @property string $tanggal_awal_kontrak
 * @property string $tanggal_akhir_kontrak
 * @property string $latitude_proyek
 * @property string $longitude_proyek
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \app\models\User $createdBy
 * @property \app\models\User $updatedBy
 * @property \app\models\User $deletedBy
 * @property \app\models\ProyekDokumen[] $proyekDokumens
 * @property \app\models\ProyekGaleri[] $proyekGaleris
 * @property \app\models\ProyekKemajuan[] $proyekKemajuans
 * @property string $aliasModel
 */
abstract class Proyek extends \yii\db\ActiveRecord
{



    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_DELETE = 'delete';
    const SCENARIO_DP = 'dp';
    const SCENARIO_BAYARDP = 'bayardp';
    const SCENARIO_BAYAR_TERMIN = 'bayarcicilan';
    const SCENARIO_KONFIRMASI = 'konfirmasi';
    const SCENARIO_TOLAK = 'tolak';
    const SCENARIO_BAYARDPULANG = 'bayardpulang';
    const SCENARIO_PENGAJUAN_SELESAI = 'pengajuan_selesai';
    const SCENARIO_PENGAJUAN_REVISI = 'pengajuan_revisi';
    const SCENARIO_PROYEK_SELESAI = 'proyek_selesai';
    public $_render = [];

    /**
     * @inheiritance
     */
    public function fields()
    {
        $parent = parent::fields();

        if (isset($parent['id'])) :
            unset($parent['id']);
            $parent['id'] = function ($model) {
                return $model->id;
            };
        endif;
        if (isset($parent['id_user'])) :
            unset($parent['id_user']);
            $parent['id_user'] = function ($model) {
                return $model->id_user;
            };
            $parent['_user'] = function ($model) {
                return $model->getUser()->select(['username', 'name', 'photo_url'])->one();
            };
        endif;
        if (isset($parent['nama_proyek'])) :
            unset($parent['nama_proyek']);
            $parent['nama_proyek'] = function ($model) {
                return $model->nama_proyek;
            };
        endif;
        if (isset($parent['jenis_pembayaran'])) :
            unset($parent['jenis_pembayaran']);
            $parent['jenis_pembayaran'] = function ($model) {
                return $model->jenis_pembayaran;
            };
        endif;
        if (isset($parent['dp_pembayaran'])) :
            unset($parent['dp_pembayaran']);
            $parent['dp_pembayaran'] = function ($model) {
                return $model->dp_pembayaran;
            };
        endif;
        if (isset($parent['nilai_dp'])) :
            unset($parent['nilai_dp']);
            $parent['nilai_dp'] = function ($model) {
                return $model->nilai_dp;
            };
        endif;
        if (isset($parent['bukti_pembayaran'])) :
            unset($parent['bukti_pembayaran']);
            $parent['bukti_pembayaran'] = function ($model) {
                return Yii::$app->formatter->asFileLink($model->bukti_pembayaran, false);
            };
        endif;
        if (isset($parent['keterangan_pembayaran'])) :
            unset($parent['keterangan_pembayaran']);
            $parent['keterangan_pembayaran'] = function ($model) {
                return $model->keterangan_pembayaran;
            };
        endif;
        if (isset($parent['bulan'])) :
            unset($parent['bulan']);
            $parent['bulan'] = function ($model) {
                return $model->bulan;
            };
        endif;
        if (isset($parent['total_pembayaran'])) :
            unset($parent['total_pembayaran']);
            $parent['total_pembayaran'] = function ($model) {
                return $model->total_pembayaran;
            };
        endif;
        if (isset($parent['status_pembayaran'])) :
            unset($parent['status_pembayaran']);
            $parent['status_pembayaran'] = function ($model) {
                return $model->status_pembayaran;
            };
        endif;
        if (isset($parent['deskripsi_proyek'])) :
            unset($parent['deskripsi_proyek']);
            $parent['deskripsi_proyek'] = function ($model) {
                return $model->deskripsi_proyek;
            };
        endif;
        if (isset($parent['nilai_kontrak'])) :
            unset($parent['nilai_kontrak']);
            $parent['nilai_kontrak'] = function ($model) {
                return $model->nilai_kontrak;
            };
        endif;
        if (isset($parent['tanggal_awal_kontrak'])) :
            unset($parent['tanggal_awal_kontrak']);
            $parent['tanggal_awal_kontrak'] = function ($model) {
                return \app\components\Tanggal::toReadableDate($model->tanggal_awal_kontrak, false);
            };
        endif;
        if (isset($parent['tanggal_akhir_kontrak'])) :
            unset($parent['tanggal_akhir_kontrak']);
            $parent['tanggal_akhir_kontrak'] = function ($model) {
                return \app\components\Tanggal::toReadableDate($model->tanggal_akhir_kontrak, false);
            };
        endif;
        if (isset($parent['latitude_proyek'])) :
            unset($parent['latitude_proyek']);
            $parent['latitude_proyek'] = function ($model) {
                return $model->latitude_proyek;
            };
        endif;
        if (isset($parent['longitude_proyek'])) :
            unset($parent['longitude_proyek']);
            $parent['longitude_proyek'] = function ($model) {
                return $model->longitude_proyek;
            };
        endif;
        if (isset($parent['alasan_tolak'])) :
            unset($parent['alasan_tolak']);
            $parent['alasan_tolak'] = function ($model) {
                return $model->alasan_tolak;
            };
        endif;
        if (isset($parent['created_at'])) :
            unset($parent['created_at']);
            $parent['created_at'] = function ($model) {
                return \app\components\Tanggal::toReadableDate($model->created_at, false);
            };
        endif;
        if (isset($parent['updated_at'])) :
            unset($parent['updated_at']);
            $parent['updated_at'] = function ($model) {
                return \app\components\Tanggal::toReadableDate($model->updated_at, false);
            };
        endif;
        if (isset($parent['deleted_at'])) :
            unset($parent['deleted_at']);
            $parent['deleted_at'] = function ($model) {
                return $model->deleted_at;
            };
        endif;
        if (isset($parent['created_by'])) :
            unset($parent['created_by']);
            $parent['created_by'] = function ($model) {
                return $model->created_by;
            };
            $parent['_created_by'] = function ($model) {
                return $model->getCreatedBy()->select(['username', 'name', 'photo_url'])->one();
            };
        endif;
        if (isset($parent['updated_by'])) :
            unset($parent['updated_by']);
            $parent['updated_by'] = function ($model) {
                return $model->updated_by;
            };
            $parent['_updated_by'] = function ($model) {
                return $model->getUpdatedBy()->select(['username', 'name', 'photo_url'])->one();
            };
        endif;
        if (isset($parent['deleted_by'])) :
            unset($parent['deleted_by']);
            $parent['deleted_by'] = function ($model) {
                return $model->deleted_by;
            };
            $parent['_deleted_by'] = function ($model) {
                return $model->getDeletedBy()->select(['username', 'name', 'photo_url'])->one();
            };
        endif;
        if (isset($parent['catatan_revisi'])) :
            unset($parent['catatan_revisi']);
            $parent['catatan_revisi'] = function ($model) {
                return $model->catatan_revisi;
            };
        endif;
        if (isset($parent['status'])) :
            unset($parent['status']);
            $parent['status'] = function ($model) {
                return $model->status;
            };
        endif;
        if (isset($parent['flag'])) :
            unset($parent['flag']);
            $parent['flag'] = function ($model) {
                return $model->flag;
            };
        endif;

        if (Constant::isUriContain(['/proyek/view'])) {
            $parent['proyek_dokumen'] = function ($model) {
                $rel = $model
                    ->getProyekDokumens()
                    ->select('id,pathfile,type,nama_file,created_at,deleted_at,created_by,deleted_by')
                    ->andWhere(['flag' => 1])
                    ->all();
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
            $parent['proyek_galeri'] = function ($model) {
                $rel = $model
                    ->getProyekGaleris()
                    ->select('nama_file,keterangan,created_at,created_by,deleted_at,deleted_by')
                    ->andWhere(['flag' => 1])
                    ->all();
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
            $parent['proyek_cctv'] = function ($model) {
                $rel = $model
                    ->getProyekCctvs()
                    ->select(['id', 'lokasi', 'link', 'tipe'])
                    ->andWhere(['flag' => 1])
                    ->all();
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
            $parent['proyek_kemajuan'] = function ($model) {
                $rel = $model
                    ->getProyekKemajuans()
                    ->select('id,id_parent,id_satuan,item,volume,bobot,volume_kemajuan,bobot_kemajuan,status_verifikasi,created_at,created_by')
                    ->andWhere(['flag' => 1])
                    ->andWhere(['is', 'id_parent', null])
                    ->all();
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
            $parent['proyek_keuangan_masuk'] = function ($model) {
                $rel = $model
                    ->getProyekKeuanganMasuks()
                    ->select('id,id_kategori,item,tanggal,jumlah,keterangan,created_at,created_by')
                    ->andWhere(['flag' => 1])
                    ->all();
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
            $parent['proyek_keuangan_keluar'] = function ($model) {
                $rel = $model
                    ->getProyekKeuanganKeluars()
                    ->select('id,no_po,dokumen_po,no_invoice,keterangan,tanggal,dibayar,total_dibayarkan,total_jumlah,vendor,tipe,status')
                    ->andWhere(['flag' => 1])
                    ->all();
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
            $parent['proyek_keuangan'] = function ($model) {
                $total_anggaran = $model->nilai_kontrak;
                $total_pemasukkan = intval($model
                    ->getProyekKeuanganMasuks()
                    ->andWhere(['is', 't_proyek_keuangan_masuk.deleted_at', null])
                    ->sum('jumlah'));
                $total_pengeluaran = (intval($model
                    ->getProyekKeuanganKeluars()
                    ->andWhere(['and', ['is', 't_proyek_keuangan_keluar.deleted_at', null], ['t_proyek_keuangan_keluar.tipe' => 0]])
                    ->sum('total_jumlah'))
                    + intval($model
                        ->getProyekKeuanganKeluars()
                        ->andWhere(['and', ['is', 't_proyek_keuangan_keluar.deleted_at', null], ['t_proyek_keuangan_keluar.tipe' => 1]])
                        ->sum('total_dibayarkan')));
                $sisa_anggaran = $total_pemasukkan - $total_pengeluaran;
                $total_hutang = intval($model
                    ->getProyekKeuanganKeluars()
                    ->andWhere(['and', ['is', 't_proyek_keuangan_keluar.deleted_at', null], ['t_proyek_keuangan_keluar.tipe' => 1]])
                    ->sum('total_jumlah')) - intval($model
                    ->getProyekKeuanganKeluars()
                    ->andWhere(['and', ['is', 't_proyek_keuangan_keluar.deleted_at', null], ['t_proyek_keuangan_keluar.tipe' => 1]])
                    ->sum('total_dibayarkan'));


                return [
                    "total_anggaran" => $total_anggaran,
                    "sisa_anggaran" => $sisa_anggaran,
                    "total_pemasukan" => $total_pemasukkan,
                    "total_pengeluaran" => $total_pengeluaran,
                    "total_hutang" => $total_hutang
                ];
            };
            $parent['proyek_termin'] = function ($model) {
                $data = $model->getProyekTermin()->all();
                return $data;
            };

            $parent['approval'] = function ($model) {
                return $model->getApprovalSebelumPekerjaans()->select([
                    'id',
                    'nama_progress',
                    'foto_material',
                    'keterangan',
                    'revisi',
                    'created_at',
                    'updated_at',
                    'status',
                ])->orderBy([
                    'status' => [
                        \app\models\ApprovalSebelumPekerjaan::STATUS_PENDING,
                        \app\models\ApprovalSebelumPekerjaan::STATUS_REJECTED,
                        \app\models\ApprovalSebelumPekerjaan::STATUS_APPROVED,
                    ],
                    'updated_at' => SORT_DESC,
                ])->all();
            };

            $parent['info'] = function ($model) {
                return \yii\helpers\Url::to(['tool/grafik', 'id' => $model->id], true);
                // $model_targets = \app\models\ProyekKemajuanTarget::find()->where(['kode_proyek' => $model->kode_unik])->all();

                // $target_perminggu = [];
                // foreach ($model_targets as $target) {
                //     $target_perminggu[] = number_format($target->jumlah_target, 2);
                // }

                // $dari = $model->tanggal_awal_kontrak;
                // $akhir = $model->tanggal_akhir_kontrak;
                // $sampai = date('Y-m-d', strtotime('+7 day', strtotime($akhir)));
                // $daftar_tanggal = \app\components\Tanggal::dateRange($dari, $sampai, '+7 day');
                // $daftar_tanggal_harian = \app\components\Tanggal::dateRange($dari, $sampai, '+1 day');

                // $progress_perminggu = array();
                // $total_progress_mingguan = 0;
                // foreach ($daftar_tanggal as $tanggal) {
                //     $next_week = date('Y-m-d', strtotime('+7 day', strtotime($tanggal)));
                //     $awal =  $model->getRealisasiByRangeDate($tanggal, $next_week);
                //     $total_progress_mingguan = $total_progress_mingguan + $awal;
                //     $progress_perminggu[] =  number_format($total_progress_mingguan, 2);
                // }

                // return Yii::$app->controller->renderAjax('/proyek/_view/info', [
                //     'model' => $model,
                //     'daftar_tanggal' => json_encode($daftar_tanggal),
                //     'target_perminggu' => json_encode($target_perminggu),
                //     'progress_perminggu' => json_encode($progress_perminggu),
                // ]);
            };
        }
        // $parent['proyek_kemajuan'] = function ($model) {
        //     $rel = $model->proyekKemajuans;
        //     if ($rel) :
        //         return $rel;
        //     endif;
        //     return null;
        // };

        return $parent;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_proyek';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date("Y-m-d H:i:s"),
            ],
            [
                'class' => BlameableBehavior::class,
                'value' => \Yii::$app->user->id,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama_proyek', 'deskripsi_proyek', 'id_user', 'nilai_kontrak', 'tanggal_awal_kontrak', 'tanggal_akhir_kontrak', 'latitude_proyek', 'longitude_proyek',], 'required'],
            [['kode_unik'], 'unique'],
            [['deskripsi_proyek', 'keterangan_pembayaran', 'alasan_tolak', 'catatan_revisi'], 'string'],
            [['created_by', 'updated_by', 'deleted_by', 'bulan', 'status_pembayaran', 'status', 'flag'], 'integer'],
            [['nilai_kontrak', 'jenis_pembayaran', 'dp_pembayaran', 'nilai_dp'], 'number'],
            [['tanggal_awal_kontrak', 'tanggal_akhir_kontrak', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['dp_pembayaran'], 'file', 'skipOnEmpty' => false, 'maxSize' => 1024 * 1024 * 2, 'extensions' => 'png, jpg, jpeg, gif'],
            [['nama_proyek', 'bukti_pembayaran'], 'string', 'max' => 200],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\User::className(), 'targetAttribute' => ['id_user' => 'id']],
            [['latitude_proyek', 'longitude_proyek'], 'string', 'max' => 20],
            ['tanggal_awal_kontrak', 'validateDate'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\User::class, 'targetAttribute' => ['updated_by' => 'id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\User::class, 'targetAttribute' => ['deleted_by' => 'id']]
        ];
    }

    public function validateDate($attribute)
    {
        return strtotime($this->tanggal_awal_kontrak) >= strtotime($this->tanggal_akhir_kontrak) ? $this->addError($attribute, $this->getAttributeLabel('tanggal_awal_kontrak') . ' tidak boleh lebih besar dari ' . $this->getAttributeLabel('tanggal_akhir_kontrak')) : null;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'User',
            'nama_proyek' => 'Nama Proyek',
            'deskripsi_proyek' => 'Alamat Proyek',
            'nilai_kontrak' => 'Nilai Kontrak',
            'tanggal_awal_kontrak' => 'Tanggal Awal Kontrak',
            'tanggal_akhir_kontrak' => 'Tanggal Akhir Kontrak',
            'latitude_proyek' => 'Latitude Proyek',
            'longitude_proyek' => 'Longitude Proyek',
            'jenis_pembayaran' => 'Jenis Pembayaran',
            'dp_pembayaran' => 'DP Pembayaran (persen)',
            'nilai_dp' => 'Nilai DP',
            'bukti_pembayaran' => 'Bukti Pembayaran',
            'keterangan_pembayaran' => 'Keterangan Pembayaran',
            'bulan' => 'Lama Cicilan',
            'total_pembayaran' => 'Total yang Telah Dibayar',
            'status_pembayaran' => 'Status Pembayaran',
            'alasan_tolak' => 'Alasan Tolak',
            'catatan_revisi' => 'Catatan Revisi',
            'status' => 'Status',
            'created_at' => 'Dibuat pada',
            'updated_at' => 'Diubah Pada',
            'deleted_at' => 'Dihapus pada',
            'created_by' => 'Dibuat oleh',
            'updated_by' => 'Diubah oleh',
            'deleted_by' => 'Dihapus oleh',
            'flag' => 'Flag'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(\app\models\User::class, ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(\app\models\User::class, ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(\app\models\User::class, ['id' => 'deleted_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyekDokumens()
    {
        return $this->hasMany(\app\models\ProyekDokumen::class, ['id_proyek' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyekGaleris()
    {
        return $this->hasMany(\app\models\ProyekGaleri::class, ['id_proyek' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyekKemajuans()
    {
        return $this->hasMany(\app\models\ProyekKemajuan::class, ['id_proyek' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyekKeuanganMasuks()
    {
        return $this->hasMany(\app\models\ProyekKeuanganMasuk::class, ['id_proyek' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyekKeuanganKeluars()
    {
        return $this->hasMany(\app\models\ProyekKeuanganKeluar::class, ['id_proyek' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasterKategoriKeuanganMasuks()
    {
        return $this->hasMany(\app\models\MasterKategoriKeuanganMasuk::class, ['id_proyek' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMasterKategoriKeuanganKeluars()
    {
        return $this->hasMany(\app\models\MasterKategoriKeuanganKeluar::class, ['id_proyek' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyekAnggotas()
    {
        return $this->hasMany(\app\models\ProyekAnggota::class, ['id_proyek' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyekCctvs()
    {
        return $this->hasMany(\app\models\ProyekCctv::class, ['id_proyek' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprovalSebelumPekerjaans()
    {
        return $this->hasMany(\app\models\ApprovalSebelumPekerjaan::class, ['id_proyek' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyekCicilan()
    {
        return $this->hasMany(\app\models\ProyekCicilan::class, ['id_proyek' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyekTermin()
    {
        return $this->hasMany(\app\models\ProyekTermin::class, ['proyek_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyekDp()
    {
        return $this->hasOne(\app\models\ProyekDp::class, ['id_proyek' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTargetProgress()
    {
        return $this->hasMany(\app\models\ProyekKemajuanTarget::class, ['id_proyek' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprovalPekerjaans()
    {
        return $this->hasMany(\app\models\ApprovalSebelumPekerjaan::class, ['id_proyek' => 'id']);
    }


    /**
     * @inheritdoc
     * @return \app\models\query\ProyekQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\ProyekQuery(get_called_class());
    }



    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'id_user',
            'nama_proyek',
            'deskripsi_proyek',
            'nilai_kontrak',
            'total_pembayaran',
            'tanggal_awal_kontrak',
            'tanggal_akhir_kontrak',
            'latitude_proyek',
            'longitude_proyek',
            'created_at',
            'updated_at',
            'deleted_at',
            'created_by',
            'updated_by',
            'deleted_by',
            'flag'
        ];

        $delete = [
            'deleted_at',
            'deleted_by',
            'flag'
        ];

        $dp = [
            // 'jenis_pembayaran',
            // 'dp_pembayaran',
            'nilai_dp',
        ];

        $pembayaran = [
            // 'jenis_pembayaran',
            // 'dp_pembayaran',
            // 'nilai_dp',
            'bukti_pembayaran',
            'keterangan_pembayaran',
            'bulan',
            'total_pembayaran',
            'status_pembayaran',
        ];

        $termin = [
            // 'jenis_pembayaran',
            // 'dp_pembayaran',
            // 'nilai_dp',
            'total_pembayaran',
        ];

        $konfirmasi = [
            'status_pembayaran',
            'alasan_tolak'
        ];

        $tolak = [
            'status_pembayaran',
            'keterangan_pembayaran',
            'total_pembayaran',
            'alasan_tolak'
        ];

        $dp_ulang = [
            'bukti_pembayaran',
            'keterangan_pembayaran',
            'total_pembayaran',
            'status_pembayaran',
        ];

        $pengajuan_selesai = [
            'status',
        ];

        $pengajuan_revisi = [
            'catatan_revisi',
            'status',
        ];

        $parent[static::SCENARIO_CREATE] = $columns;
        $parent[static::SCENARIO_UPDATE] = $columns;
        $parent[static::SCENARIO_DELETE] = $delete;
        $parent[static::SCENARIO_DP] = $dp;
        $parent[static::SCENARIO_BAYARDP] = $pembayaran;
        $parent[static::SCENARIO_BAYAR_TERMIN] = $termin;
        $parent[static::SCENARIO_KONFIRMASI] = $konfirmasi;
        $parent[static::SCENARIO_TOLAK] = $tolak;
        $parent[static::SCENARIO_BAYARDPULANG] = $dp_ulang;
        $parent[static::SCENARIO_PENGAJUAN_SELESAI] = $pengajuan_selesai;
        $parent[static::SCENARIO_PENGAJUAN_REVISI] = $pengajuan_revisi;
        $parent[static::SCENARIO_PROYEK_SELESAI] = $pengajuan_revisi;
        return $parent;
    }

    public function setRender($arr)
    {
        $this->_render = array_merge($this->_render, $arr);
    }

    public function removeRender($arr)
    {
        unset($this->_render[$arr]);
    }

    /**
     * Simplify return data xD
     */
    public function render()
    {
        return array_merge($this->_render, [
            "model" => $this,
        ]);
    }

    /**
     * override validate
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        return parent::validate($attributeNames, $clearErrors);
    }

    /**
     * override load
     */
    public function load($data, $formName = null, $service = "web")
    {
        return parent::load($data, $formName);
    }
}