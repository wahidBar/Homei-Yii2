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
 * This is the base-model class for table "t_supplier_order_detail".
 *
 * @property integer $id
 * @property string $kode_unik
 * @property integer $supplier_order_id
 * @property string $kode_order
 * @property integer $supplier_barang_id
 * @property integer $jumlah_per_buah
 * @property integer $jumlah_per_meter
 * @property integer $total_ppn
 * @property string $catatan
 * @property string $voucher
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property \app\models\SupplierOrder $supplierOrder
 * @property \app\models\SupplierBarang $supplierBarang
 * @property string $aliasModel
 */
abstract class SupplierOrderDetail extends \yii\db\ActiveRecord
{



    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
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
        if (isset($parent['supplier_order_id'])) :
            unset($parent['supplier_order_id']);
            $parent['supplier_order_id'] = function ($model) {
                return $model->supplier_order_id;
            };
        // $parent['_supplier_order'] = function($model) {
        //     $rel = $model->supplierOrder;
        //     if ($rel) :
        //         return $rel;
        //     endif;
        //     return null;
        // };
        endif;
        if (isset($parent['kode_order'])) :
            unset($parent['kode_order']);
            $parent['kode_order'] = function ($model) {
                return $model->kode_order;
            };
        endif;
        if (isset($parent['material_id'])) :
            unset($parent['material_id']);
            $parent['material_id'] = function ($model) {
                return $model->material_id;
            };
            $parent['_material'] = function ($model) {
                $rel = $model->getMaterial()->select('nama')->one();
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
        endif;
        if (isset($parent['submaterial_id'])) :
            unset($parent['submaterial_id']);
            $parent['submaterial_id'] = function ($model) {
                return $model->submaterial_id;
            };
            $parent['_submaterial'] = function ($model) {
                $rel = $model->getSubMaterial()->select('nama')->one();
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
        endif;
        if (isset($parent['supplier_id'])) :
            unset($parent['supplier_id']);
            $parent['supplier_id'] = function ($model) {
                return $model->supplier_id;
            };
            $parent['_supplier'] = function ($model) {
                $rel = $model->getSupplier()->select([
                    'nama_supplier',
                    'alamat',
                    'telepon'
                ])->one();
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
        endif;
        if (isset($parent['supplier_barang_id'])) :
            unset($parent['supplier_barang_id']);
            $parent['supplier_barang_id'] = function ($model) {
                return $model->supplier_barang_id;
            };
            $parent['_supplier_barang'] = function ($model) {
                $rel = $model->getSupplierBarang()->select([
                    "nama_barang",
                    "satuan_id",
                    "stok",
                ])->one();
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
        endif;
        if (isset($parent['jumlah'])) :
            unset($parent['jumlah']);
            $parent['jumlah'] = function ($model) {
                return $model->jumlah;
            };
        endif;
        if (isset($parent['volume'])) :
            unset($parent['volume']);
            $parent['volume'] = function ($model) {
                return $model->volume;
            };
        endif;
        if (isset($parent['total_ppn'])) :
            unset($parent['total_ppn']);
            $parent['total_ppn'] = function ($model) {
                return $model->total_ppn;
            };
        endif;
        if (isset($parent['harga_satuan'])) :
            unset($parent['harga_satuan']);
            $parent['harga_satuan'] = function ($model) {
                return $model->harga_satuan;
            };
        endif;
        if (isset($parent['subtotal'])) :
            unset($parent['subtotal']);
            $parent['subtotal'] = function ($model) {
                return $model->subtotal;
            };
        endif;
        if (isset($parent['catatan'])) :
            unset($parent['catatan']);
            $parent['catatan'] = function ($model) {
                return $model->catatan;
            };
        endif;
        if (isset($parent['no_spk'])) :
            unset($parent['no_spk']);
            $parent['no_spk'] = function ($model) {
                return $model->no_spk;
            };
        endif;
        if (isset($parent['keterangan_proyek'])) :
            unset($parent['keterangan_proyek']);
            $parent['keterangan_proyek'] = function ($model) {
                return $model->keterangan_proyek;
            };
        endif;
        if (isset($parent['valid_spk'])) :
            unset($parent['valid_spk']);
            $parent['valid_spk'] = function ($model) {
                return $model->valid_spk;
            };
        endif;
        if (isset($parent['voucher'])) :
            unset($parent['voucher']);
            $parent['voucher'] = function ($model) {
                return $model->voucher;
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
                return \app\components\Tanggal::toReadableDate($model->deleted_at, false);
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
                return $model->updated_by;
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
        return 't_supplier_order_detail';
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
                // 'deletedAtAttribute' => 'deleted_at',
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
            [['kode_unik', 'harga_satuan', 'supplier_order_id', 'kode_order', 'supplier_barang_id'], 'required'],
            [['supplier_order_id', 'supplier_barang_id', 'volume', 'total_ppn', 'created_by', 'updated_by', 'deleted_by', 'flag'], 'integer'],
            [['catatan'], 'string'],
            [['subtotal'], 'double'],
            [['jumlah'], 'number'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['kode_unik', 'kode_order', 'voucher'], 'string', 'max' => 50],
            [['supplier_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\SupplierOrder::className(), 'targetAttribute' => ['supplier_order_id' => 'id']],
            [['supplier_barang_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\SupplierBarang::className(), 'targetAttribute' => ['supplier_barang_id' => 'id']],
            [['material_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\SupplierMaterial::className(), 'targetAttribute' => ['material_id' => 'id']],
            [['submaterial_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\SupplierSubMaterial::className(), 'targetAttribute' => ['submaterial_id' => 'id']],
            [['supplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Supplier::className(), 'targetAttribute' => ['supplier_id' => 'id']],
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
            'supplier_order_id' => 'Pesanan',
            'kode_order' => 'Kode Pemesanan',
            'supplier_id' => 'Pemasok',
            'supplier_barang_id' => 'Barang',
            'volume' => 'Volume',
            'jumlah' => 'Jumlah',
            'total_ppn' => 'Total PPN',
            'catatan' => 'Catatan',
            'voucher' => 'Voucher',
            'subtotal' => 'Subtotal',
            'created_at' => 'Dibuat pada',
            'updated_at' => 'Diubah Pada',
            'deleted_at' => 'Dihapus Pada',
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
            'jumlah_per_buah' => 'jumlah satuan perbuah',
            'jumlah_per_meter' => 'jumlah satuan permeter kubik',
            'total_ppn' => 'ppn jika ada',
            'voucher' => 'voucher untuk diskon',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierOrder()
    {
        return $this->hasOne(\app\models\SupplierOrder::className(), ['id' => 'supplier_order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierBarang()
    {
        return $this->hasOne(\app\models\SupplierBarang::className(), ['id' => 'supplier_barang_id']);
    }



    /**
     * @inheritdoc
     * @return \app\models\query\SupplierOrderDetailQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\SupplierOrderDetailQuery(get_called_class());
    }



    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'kode_unik',
            'supplier_order_id',
            'kode_order',
            'material_id',
            'submaterial_id',
            'supplier_id',
            'supplier_barang_id',
            'volume',
            'jumlah',
            'total_ppn',
            'catatan',
            'voucher',
            'harga_satuan',
            'subtotal',
            'created_at',
            'updated_at',
            'deleted_at',
            'created_by',
            'updated_by',
            'deleted_by',
            'flag'
        ];

        $parent[static::SCENARIO_CREATE] = $columns;
        $parent[static::SCENARIO_UPDATE] = $columns;
        return $parent;
    }

    public function getMaterial()
    {
        return $this->hasOne(\app\models\SupplierMaterial::class, ["id" => "material_id"]);
    }

    public function getSubMaterial()
    {
        return $this->hasOne(\app\models\SupplierSubMaterial::class, ["id" => "submaterial_id"]);
    }

    public function getSupplier()
    {
        return $this->hasOne(\app\models\Supplier::class, ["id" => "supplier_id"]);
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
