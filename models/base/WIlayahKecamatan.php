<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build
// Modified by Defri Indra
// 2021

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "wilayah_kecamatan".
 *
 * @property string $id
 * @property string $kota_id
 * @property string $nama
 *
 * @property \app\models\WilayahDesa[] $wilayahDesas
 * @property \app\models\WilayahKota $kota
 * @property string $aliasModel
 */
abstract class WilayahKecamatan extends \yii\db\ActiveRecord
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
        if (isset($parent['kota_id'])) :
            unset($parent['kota_id']);
            $parent['kota_id'] = function ($model) {
                return $model->kota_id;
            };
            $parent['_kota'] = function ($model) {
                $rel = $model->kota;
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
        endif;
        if (isset($parent['nama'])) :
            unset($parent['nama']);
            $parent['nama'] = function ($model) {
                return $model->nama;
            };
        endif;


        // $parent['wilayah_desa'] = function($model) {
        //     $rel = $model->wilayahDesas;
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
        return 'wilayah_kecamatan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'kota_id', 'nama'], 'required'],
            [['id'], 'string', 'max' => 7],
            [['kota_id'], 'string', 'max' => 4],
            [['nama'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['kota_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\WilayahKota::className(), 'targetAttribute' => ['kota_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kota_id' => 'Kota',
            'nama' => 'Nama',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWilayahDesas()
    {
        return $this->hasMany(\app\models\WilayahDesa::className(), ['kecamatan_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKota()
    {
        return $this->hasOne(\app\models\WilayahKota::className(), ['id' => 'kota_id']);
    }





    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'kota_id',
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
