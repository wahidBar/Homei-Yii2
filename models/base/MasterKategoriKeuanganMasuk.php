<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build
// Modified by Defri Indra
// 2021

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "t_master_kategori_keuangan_masuk".
 *
 * @property integer $id
 * @property string $nama_kategori
 * @property integer $flag
 *
 * @property \app\models\ProyekKeuanganMasuk[] $proyekKeuanganMasuks
 * @property string $aliasModel
 */
abstract class MasterKategoriKeuanganMasuk extends \yii\db\ActiveRecord
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
        if (isset($parent['id_proyek'])) :
            unset($parent['id_proyek']);
            $parent['id_proyek'] = function ($model) {
                return $model->id_proyek;
            };
            // $parent['_proyek'] = function ($model) {

            //     return $model->proye;
            // };
        endif;
        if (isset($parent['nama_kategori'])) :
            unset($parent['nama_kategori']);
            $parent['nama_kategori'] = function ($model) {
                return $model->nama_kategori;
            };
        endif;
        if (isset($parent['flag'])) :
            unset($parent['flag']);
            $parent['flag'] = function ($model) {
                return $model->flag;
            };
        endif;


        // $parent['proyek_keuangan_masuk'] = function($model) {
        //     $rel = $model->proyekKeuanganMasuks;
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
        return 't_master_kategori_keuangan_masuk';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama_kategori'], 'required'],
            [['flag', 'id_proyek'], 'integer'],
            [['nama_kategori'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_kategori' => 'Nama Kategori',
            'flag' => 'Flag',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyekKeuanganMasuks()
    {
        return $this->hasMany(\app\models\ProyekKeuanganMasuk::className(), ['id_kategori' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyek()
    {
        return $this->hasMany(\app\models\Proyek::className(), ['id_proyek' => 'id']);
    }



    /**
     * @inheritdoc
     * @return \app\models\query\MasterKategoriKeuanganMasukQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\MasterKategoriKeuanganMasukQuery(get_called_class());
    }



    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'id_proyek',
            'nama_kategori',
            'flag',
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
