<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build
// Modified by Defri Indra
// 2021

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "t_master_lantai".
 *
 * @property integer $id
 * @property string $nama
 *
 * @property \app\models\IsianLanjutan[] $isianLanjutans
 * @property string $aliasModel
 */
abstract class MasterLantai extends \yii\db\ActiveRecord
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
        if (isset($parent['nama'])) :
            unset($parent['nama']);
            $parent['nama'] = function ($model) {
                return $model->nama;
            };
        endif;


        // $parent['isian_lanjutan'] = function($model) {
        //     $rel = $model->isianLanjutans;
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
        return 't_master_lantai';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['nama'], 'string', 'max' => 255]
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
    public function getIsianLanjutans()
    {
        return $this->hasMany(\app\models\IsianLanjutan::className(), ['id_lantai' => 'id']);
    }



    /**
     * @inheritdoc
     * @return \app\models\query\masterLantaiQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\MasterLantaiQuery(get_called_class());
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
