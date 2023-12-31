<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build
// Modified by Defri Indra
// 2021

namespace app\models\base;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "t_proyek_termin".
 *
 * @property integer $id
 * @property string $kode_unik
 * @property integer $proyek_id
 * @property integer $user_id
 * @property string $termin
 * @property integer $penyelesaian_pekerjaan
 * @property integer $nilai_pembayaran
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property \app\models\TProyek $proyek
 * @property \app\models\User $user
 * @property string $aliasModel
 */
abstract class ProyekTermin extends \yii\db\ActiveRecord
{



    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_DELETE = 'delete';
    const SCENARIO_BAYAR_TERMIN = 'bayar-termin';
    const SCENARIO_TOLAK_BAYAR_TERMIN = 'tolak-bayar-termin';
    const SCENARIO_KONFIRMASI_BAYAR_TERMIN = 'konfirmasi-bayar-termin';
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
        if (isset($parent['kode_unik'])) :
            unset($parent['kode_unik']);
            $parent['kode_unik'] = function ($model) {
                return $model->kode_unik;
            };
        endif;
        if (isset($parent['kode_proyek'])) :
            unset($parent['kode_proyek']);
            $parent['kode_proyek'] = function ($model) {
                return $model->kode_proyek;
            };
        endif;
        if (isset($parent['proyek_id'])) :
            unset($parent['proyek_id']);
            // $parent['proyek_id'] = function ($model) {
            //     return $model->proyek_id;
            // };
        // $parent['_proyek'] = function ($model) {
        //     $rel = $model->proyek;
        //     if ($rel) :
        //         return $rel;
        //     endif;
        //     return null;
        // };
        endif;
        if (isset($parent['id_proyek'])) :
            unset($parent['id_proyek']);
            $parent['id_proyek'] = function ($model) {
                return $model->proyek_id;
            };
        // $parent['_proyek'] = function ($model) {
        //     $rel = $model->proyek;
        //     if ($rel) :
        //         return $rel;
        //     endif;
        //     return null;
        // };
        endif;
        if (isset($parent['user_id'])) :
            unset($parent['user_id']);
            $parent['user_id'] = function ($model) {
                return $model->user_id;
            };
            $parent['_user'] = function ($model) {
                $rel = $model->user;
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
        endif;
        if (isset($parent['termin'])) :
            unset($parent['termin']);
            $parent['termin'] = function ($model) {
                return $model->termin;
            };
        endif;
        if (isset($parent['penyelesaian_pekerjaan'])) :
            unset($parent['penyelesaian_pekerjaan']);
            $parent['penyelesaian_pekerjaan'] = function ($model) {
                return $model->penyelesaian_pekerjaan;
            };
        endif;
        if (isset($parent['nilai_pembayaran'])) :
            unset($parent['nilai_pembayaran']);
            $parent['nilai_pembayaran'] = function ($model) {
                return $model->nilai_pembayaran;
            };
        endif;
        if (isset($parent['jadwal_pembayaran'])) :
            unset($parent['jadwal_pembayaran']);
            $parent['jadwal_pembayaran'] = function ($model) {
                return $model->jadwal_pembayaran;
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
        if (isset($parent['tanggal_pembayaran'])) :
            unset($parent['tanggal_pembayaran']);
            $parent['tanggal_pembayaran'] = function ($model) {
                return \app\components\Tanggal::toReadableDate($model->tanggal_pembayaran, false);
            };
        endif;
        if (isset($parent['alasan_tolak_pembayaran'])) :
            unset($parent['alasan_tolak_pembayaran']);
            $parent['alasan_tolak_pembayaran'] = function ($model) {
                return $model->alasan_tolak_pembayaran;
            };
        endif;

        if (isset($parent['status'])) :
            unset($parent['status']);
            $parent['status'] = function ($model) {
                return $model->status;
            };
            $parent['_status'] = function ($model) {
                return $model::getStatuses()[$model->status];
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
        if (isset($parent['created_by'])) :
            unset($parent['created_by']);
            $parent['created_by'] = function ($model) {
                return $model->created_by;
            };
        endif;
        if (isset($parent['updated_by'])) :
            unset($parent['updated_by']);
            $parent['updated_by'] = function ($model) {
                return $model->created_by;
            };
        endif;
        if (isset($parent['flag'])) :
            unset($parent['flag']);
            $parent['flag'] = function ($model) {
                return $model->flag;
            };
        endif;


        return $parent;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_proyek_termin';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date("Y-m-d H:i:s"),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode_unik', 'proyek_id', 'kode_proyek', 'user_id', 'termin', 'penyelesaian_pekerjaan', 'nilai_pembayaran'], 'required'],
            [['proyek_id', 'user_id', 'penyelesaian_pekerjaan', 'status', 'created_by', 'updated_by', 'flag'], 'integer'],
            [['created_at', 'updated_at', 'tanggal_pembayaran', 'bukti_pembayaran'], 'safe'],
            [['kode_unik'], 'string', 'max' => 100],
            [['termin', 'tanggal_pembayaran', 'jadwal_pembayaran', 'keterangan_pembayaran'], 'string', 'max' => 255],
            [['bukti_pembayaran'], 'file', 'skipOnEmpty' => true, 'maxSize' => 1024 * 1024 * 2, 'extensions' => 'png, jpg, jpeg'],
            [['proyek_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Proyek::className(), 'targetAttribute' => ['proyek_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\User::className(), 'targetAttribute' => ['user_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_unik' => 'Kode Unik',
            'proyek_id' => 'Proyek',
            'user_id' => 'Pengguna',
            'termin' => 'Termin',
            'penyelesaian_pekerjaan' => 'Penyelesaian Pekerjaan',
            'nilai_pembayaran' => 'Nilai Pembayaran',
            'jadwal_pembayaran' => 'Jadwal Pembayaran',
            'bukti_pembayaran' => 'Bukti Pembayaran',
            'keterangan_pembayaran' => 'Keterangan Pembayaran',
            'tanggal_pembayaran' => 'Tanggal Pembayaran',
            'status' => 'Status',
            'created_at' => 'Dibuat pada',
            'updated_at' => 'Diubah Pada',
            'created_by' => 'Dibuat oleh',
            'updated_by' => 'Diubah oleh',
            'flag' => 'Flag'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
            'penyelesaian_pekerjaan' => 'bentuk persen',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyek()
    {
        return $this->hasOne(\app\models\Proyek::className(), ['id' => 'proyek_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'user_id']);
    }



    /**
     * @inheritdoc
     * @return \app\models\query\ProyekTerminQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\ProyekTerminQuery(get_called_class());
    }



    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'kode_unik',
            'kode_proyek',
            'proyek_id',
            'user_id',
            'termin',
            'penyelesaian_pekerjaan',
            'nilai_pembayaran',
            'jadwal_pembayaran',
            'keterangan_pembayaran',
            'tanggal_pembayaran',
            'status',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'flag'
        ];

        $delete = [
            'deleted_at',
            'updated_by',
            'flag'
        ];

        $termin = [
            'bukti_pembayaran',
            'keterangan_pembayaran',
            'tanggal_pembayaran',
            'status',
            'alasan_tolak_pembayaran',
        ];

        $aksi_pembayaran = [
            'alasan_tolak_pembayaran',
            'status'
        ];


        $parent[static::SCENARIO_CREATE] = $columns;
        $parent[static::SCENARIO_UPDATE] = $columns;
        $parent[static::SCENARIO_DELETE] = $delete;
        $parent[static::SCENARIO_BAYAR_TERMIN] = $termin;
        $parent[static::SCENARIO_TOLAK_BAYAR_TERMIN] = $aksi_pembayaran;
        $parent[static::SCENARIO_KONFIRMASI_BAYAR_TERMIN] = $aksi_pembayaran;
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
