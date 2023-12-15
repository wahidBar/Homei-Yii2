<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build
// Modified by Defri Indra
// 2021

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "wilayah_provinsi".
 *
 * @property string $id
 * @property string $nama
 *
 * @property \app\models\THargaMaterial[] $tHargaMaterials
 * @property \app\models\TIsianLanjutan[] $tIsianLanjutans
 * @property \app\models\TSupplier[] $tSuppliers
 * @property \app\models\WilayahKota[] $wilayahKotas
 * @property string $aliasModel
 */
abstract class WilayahProvinsi extends \yii\db\ActiveRecord
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

        if(isset($parent['id'])) :
            unset($parent['id']);
            $parent['id'] = function($model) {
                return $model->id;
            };
        endif;
        if(isset($parent['nama'])) :
            unset($parent['nama']);
            $parent['nama'] = function($model) {
                return $model->nama;
            };
        endif;


    // $parent['t_harga_material'] = function($model) {
    //     $rel = $model->tHargaMaterials;
    //     if($rel) :
    //         return $rel;
    //     endif;
    //     return null;
    // };
    // $parent['t_isian_lanjutan'] = function($model) {
    //     $rel = $model->tIsianLanjutans;
    //     if($rel) :
    //         return $rel;
    //     endif;
    //     return null;
    // };
    // $parent['t_supplier'] = function($model) {
    //     $rel = $model->tSuppliers;
    //     if($rel) :
    //         return $rel;
    //     endif;
    //     return null;
    // };
    // $parent['wilayah_kota'] = function($model) {
    //     $rel = $model->wilayahKotas;
    //     if($rel) :
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
        return 'wilayah_provinsi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nama'], 'required'],
            [['id'], 'string', 'max' => 2],
            [['nama'], 'string', 'max' => 255],
            [['id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        'id' => 'ID',
        'nama' => 'Nama',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTHargaMaterials()
    {
        return $this->hasMany(\app\models\THargaMaterial::className(), ['id_provinsi' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTIsianLanjutans()
    {
        return $this->hasMany(\app\models\TIsianLanjutan::className(), ['id_provinsi' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTSuppliers()
    {
        return $this->hasMany(\app\models\TSupplier::className(), ['id_provinsi' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWilayahKotas()
    {
        return $this->hasMany(\app\models\WilayahKota::className(), ['provinsi_id' => 'id']);
    }





    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'nama',
        ];

        $parent[static::SCENARIO_CREATE] = $columns;
        $parent[static::SCENARIO_UPDATE] = $columns;
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
