<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build
// Modified by Defri Indra
// 2021

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "t_master_variable_hitungan".
 *
 * @property integer $id
 * @property string $nama
 * @property string $keterangan
 * @property integer $flag
 * @property string $aliasModel
 */
abstract class MasterVariableHitungan extends \yii\db\ActiveRecord
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
        if (isset($parent['keterangan'])) :
            unset($parent['keterangan']);
            $parent['keterangan'] = function ($model) {
                return $model->keterangan;
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
        return 't_master_variable_hitungan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama', 'keterangan'], 'required'],
            [['keterangan'], 'string'],
            [['flag'], 'integer'],
            [['nama'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('cruds', 'ID'),
            'nama' => Yii::t('cruds', 'Nama'),
            'keterangan' => Yii::t('cruds', 'Keterangan'),
            'flag' => Yii::t('cruds', 'Flag'),
        ];
    }



    /**
     * @inheritdoc
     * @return \app\models\query\MasterVariableHitunganQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\MasterVariableHitunganQuery(get_called_class());
    }



    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'nama',
            'keterangan',
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
