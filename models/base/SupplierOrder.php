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
 * This is the base-model class for table "t_supplier_order".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $kode_unik
 * @property string $no_nota
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $flag
 *
 * @property \app\models\User $user
 * @property \app\models\SupplierOrderDetail[] $tSupplierOrderDetails
 * @property string $aliasModel
 */
abstract class SupplierOrder extends \yii\db\ActiveRecord
{



    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_BAYAR = 'bayar';
    const SCENARIO_KONFIRMASI = 'konfirmasi';
    const SCENARIO_TOLAK_BAYAR = 'tolak-bayar';
    const SCENARIO_BAYARULANG = 'bayar-ulang';

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
        if (isset($parent['user_id'])) :
            unset($parent['user_id']);
            $parent['user_id'] = function ($model) {
                return $model->user_id;
            };
            $parent['_user'] = function ($model) {
                $rel = $model->getUser()->select(['username'])->one();
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
        endif;
        if (isset($parent['id_isian_lanjutan'])) :
            unset($parent['id_isian_lanjutan']);
        //     $parent['id_isian_lanjutan'] = function ($model) {
        //         return $model->id_isian_lanjutan;
        //     };
        //     $parent['_user'] = function ($model) {
        //         $rel = $model->isianLanjutan;
        //         if ($rel) :
        //             return $rel;
        //         endif;
        //         return null;
        //     };
        endif;
        if (isset($parent['layanan_supplier'])) :
            unset($parent['layanan_supplier']);
        // $parent['layanan_supplier'] = function ($model) {
        //     return $model->layanan_supplier;
        // };
        endif;
        if (isset($parent['kode_isian_lanjutan'])) :
            unset($parent['kode_isian_lanjutan']);
        // $parent['kode_isian_lanjutan'] = function ($model) {
        //     return $model->kode_isian_lanjutan;
        // };
        endif;
        if (isset($parent['kode_unik'])) :
            unset($parent['kode_unik']);
            $parent['kode_unik'] = function ($model) {
                return $model->kode_unik;
            };
        endif;
        if (isset($parent['no_nota'])) :
            unset($parent['no_nota']);
            $parent['no_nota'] = function ($model) {
                return $model->no_nota;
            };
        endif;
        if (isset($parent['nama_penerima'])) :
            unset($parent['nama_penerima']);
            $parent['nama_penerima'] = function ($model) {
                return $model->nama_penerima;
            };
        endif;
        if (isset($parent['catatan'])) :
            unset($parent['catatan']);
            $parent['catatan'] = function ($model) {
                return $model->catatan;
            };
        endif;
        if (isset($parent['total_harga'])) :
            unset($parent['total_harga']);
            $parent['total_harga'] = function ($model) {
                return $model->total_harga;
            };
        endif;
        if (isset($parent['alamat'])) :
            unset($parent['alamat']);
            $parent['alamat'] = function ($model) {
                return $model->alamat;
            };
        endif;
        if (isset($parent['status'])) :
            unset($parent['status']);
            $parent['status'] = function ($model) {
                return $model->status;
            };
            $parent['_status'] = function ($model) {
                return \app\models\SupplierOrder::getStatuses()[$model->status];
            };
        endif;
        if (isset($parent['latitude'])) :
            unset($parent['latitude']);
            $parent['latitude'] = function ($model) {
                return $model->latitude;
            };
        endif;
        if (isset($parent['longitude'])) :
            unset($parent['longitude']);
            $parent['longitude'] = function ($model) {
                return $model->longitude;
            };
        endif;
        if (isset($parent['bukti_bayar'])) :
            unset($parent['bukti_bayar']);
            $parent['bukti_bayar'] = function ($model) {
                return Yii::$app->formatter->asMyImage($model->bukti_bayar, false);
            };
        endif;
        if (isset($parent['deadline_bayar'])) :
            unset($parent['deadline_bayar']);
            $parent['deadline_bayar'] = function ($model) {
                return \app\components\Tanggal::toReadableDate($model->deadline_bayar, false);
            };
        endif;
        if (isset($parent['tanggal_bayar'])) :
            unset($parent['tanggal_bayar']);
            $parent['tanggal_bayar'] = function ($model) {
                if ($model->tanggal_bayar == null) return null;
                return \app\components\Tanggal::toReadableDate($model->tanggal_bayar, false);
            };
        endif;
        if (isset($parent['keterangan_bayar'])) :
            unset($parent['keterangan_bayar']);
            $parent['keterangan_bayar'] = function ($model) {
                return $model->keterangan_bayar;
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
        if (isset($parent['deleted_by'])) :
            unset($parent['deleted_by']);
            $parent['deleted_by'] = function ($model) {
                return $model->deleted_by;
            };
        endif;
        if (isset($parent['flag'])) :
            unset($parent['flag']);
        // $parent['flag'] = function ($model) {
        //     return $model->flag;
        // };
        endif;


        if (\app\components\Constant::isUriContain(['supplier-order/view'])) {
            $parent['_supplier_order_detail'] = function ($model) {
                $rel = $model->getSupplierOrderDetails()
                    ->select([
                        "id",
                        "kode_unik",
                        "material_id",
                        "submaterial_id",
                        "supplier_id",
                        // "supplier_order_id",
                        "kode_order",
                        "supplier_barang_id",
                        "jumlah",
                        "volume",
                        "total_ppn",
                        "harga_satuan",
                        "subtotal",
                        "catatan",
                        "voucher",
                        // "created_at",
                        // "updated_at",
                        // "deleted_at",
                        // "created_by",
                        // "updated_by",
                        // "deleted_by",
                        "no_spk",
                        "keterangan_proyek",
                        "valid_spk",
                        // "flag",
                    ])
                    ->andWhere(['flag' => 1])
                    ->all();
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
        }

        $parent['_jumlah_jenis_barang'] = function ($model) {
            $rel = $model->getSupplierOrderDetails()
                ->select([
                    "id"
                ])
                ->andWhere(['flag' => 1])
                ->count();
            if ($rel) :
                return intval($rel);
            endif;
            return null;
        };


        $parent['_pengiriman'] = function ($model) {
            $rel = $model->getSupplierPengirimans()
                ->select([
                    // "id",
                    "keterangan",
                    "tanggal"
                ])
                ->orderBy(['id' => SORT_DESC])
                ->all();
            if ($rel) :
                return $rel;
            endif;
            return null;
        };

        return $parent;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_supplier_order';
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
            [['user_id', 'kode_unik', 'no_nota'], 'required'],
            [[
                'alasan_tolak'
            ], 'required', 'on' => static::SCENARIO_TOLAK_BAYAR],
            [['user_id', 'id_isian_lanjutan', 'total_harga', 'status', 'created_by', 'updated_by', 'deleted_by', 'flag'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at', 'deadline_bayar', 'tanggal_bayar'], 'safe'],
            [['latitude', 'longitude', 'alasan_tolak', 'bukti_bayar', 'keterangan_bayar'], 'string'],
            [['kode_unik', 'kode_isian_lanjutan'], 'string', 'max' => 50],
            [['no_nota'], 'string', 'max' => 100],
            [['alamat'], 'string'],
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
            'user_id' => 'Pengguna',
            'id_isian_lanjutan' => 'Isian Lanjutan',
            'kode_isian_lanjutan' => 'Kode Isian Lanjutan',
            'kode_unik' => 'Kode Unik',
            'no_nota' => 'No Nota',
            'alamat' => 'Alamat',
            'total_harga' => 'Total Harga',
            'status' => 'Status',
            'bukti_bayar' => 'Bukti Pembayaran',
            'tanggal_bayar' => 'Tanggal Pembayaran',
            'deadline_bayar' => 'Batas Pembayaran',
            'keterangan_bayar' => 'Keterangan Pembayaran',
            'alasan_tolak' => 'Alasan Tolak',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'created_at' => 'Dibuat pada',
            'updated_at' => 'Diubah Pada',
            'deleted_at' => 'Dihapus pada',
            'created_by' => 'Dibuat oleh',
            'updated_by' => 'Diubah oleh',
            'deleted_by' => 'Dihapus oleh',
            'flag' => 'Flag',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
            'status' => 'status transaksi, 0 = default',
            'flag' => 'flag untuk softdelete 0=hapus, 1=blm dihapus',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierBoqProyek()
    {
        return $this->hasOne(\app\models\SupplierBoqProyek::className(), ['id' => 'id_supplier_boq_proyek']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsianLanjutan()
    {
        return $this->hasOne(\app\models\IsianLanjutan::className(), ['id' => 'id_isian_lanjutan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierPengirimans()
    {
        return $this->hasMany(\app\models\SupplierPengiriman::className(), ['supplier_order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierOrderDetails()
    {
        return $this->hasMany(\app\models\SupplierOrderDetail::className(), ['supplier_order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierOrderDetail()
    {
        return $this->hasOne(\app\models\SupplierOrderDetail::className(), ['supplier_order_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\query\SupplierOrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\SupplierOrderQuery(get_called_class());
    }



    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'user_id',
            'id_isian_lanjutan',
            'kode_isian_lanjutan',
            'kode_unik',
            'nama_penerima',
            'no_nota',
            'total_harga',
            'status',
            'alamat',
            'tanggal_bayar',
            'bukti_bayar',
            'deadline_bayar',
            'keterangan_bayar',
            'alasan_tolak',
            'latitude',
            'longitude',
            'created_at',
            'updated_at',
            'deleted_at',
            'created_by',
            'updated_by',
            'deleted_by',
            'flag',
        ];

        $pembayaran = [
            'bukti_bayar',
            'tanggal_bayar',
            'keterangan_bayar',
            'status',
            'alasan_tolak',
        ];

        $konfirmasi = [
            'status',
            'alasan_tolak'
        ];

        $tolak = [
            'status',
            'keterangan_bayar',
            'alasan_tolak'
        ];

        $dp_ulang = [
            'bukti_bayar',
            'tanggal_bayar',
            'keterangan_bayar',
            'status',
        ];

        $parent[static::SCENARIO_CREATE] = $columns;
        $parent[static::SCENARIO_UPDATE] = $columns;
        $parent[static::SCENARIO_BAYAR] = $pembayaran;
        $parent[static::SCENARIO_KONFIRMASI] = $konfirmasi;
        $parent[static::SCENARIO_TOLAK_BAYAR] = $tolak;
        $parent[static::SCENARIO_BAYARULANG] = $dp_ulang;
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
