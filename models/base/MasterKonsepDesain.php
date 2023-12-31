<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build
// Modified by Defri Indra
// 2021

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "t_master_konsep_desain".
 *
 * @property integer $id
 * @property string $nama_konsep
 * @property string $gambar
 * @property integer $flag
 *
 * @property \app\models\IsianLanjutan[] $isianLanjutans
 * @property string $aliasModel
 */
abstract class MasterKonsepDesain extends \yii\db\ActiveRecord
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
        if (isset($parent['nama_konsep'])) :
            unset($parent['nama_konsep']);
            $parent['nama_konsep'] = function ($model) {
                return $model->nama_konsep;
            };
        endif;
        if (isset($parent['gambar'])) :
            unset($parent['gambar']);
            $parent['gambar'] = function ($model) {
                return \Yii::$app->formatter->asMyImage($model->gambar, false);
            };
        endif;
        if (isset($parent['flag'])) :
            unset($parent['flag']);
            $parent['flag'] = function ($model) {
                return $model->flag;
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
        return 't_master_konsep_desain';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama_konsep'], 'required'],
            [['flag'], 'integer'],
            [['nama_konsep'], 'string', 'max' => 100],
            [['gambar'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_konsep' => 'Nama Konsep',
            'gambar' => 'Gambar',
            'flag' => 'Flag',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIsianLanjutans()
    {
        return $this->hasMany(\app\models\IsianLanjutan::className(), ['id_konsep_design' => 'id']);
    }



    /**
     * @inheritdoc
     * @return \app\models\query\MasterKonsepDesainQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\MasterKonsepDesainQuery(get_called_class());
    }



    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'nama_konsep',
            'gambar',
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
