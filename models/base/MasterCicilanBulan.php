<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build
// Modified by Defri Indra
// 2021

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "t_master_cicilan_bulan".
 *
 * @property integer $id
 * @property integer $lama_cicilan
 * @property integer $flag
 * @property string $aliasModel
 */
abstract class MasterCicilanBulan extends \yii\db\ActiveRecord
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
        if (isset($parent['lama_cicilan'])) :
            unset($parent['lama_cicilan']);
            $parent['lama_cicilan'] = function ($model) {
                return $model->lama_cicilan;
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
        return 't_master_cicilan_bulan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lama_cicilan'], 'required'],
            [['lama_cicilan', 'flag'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lama_cicilan' => 'Lama Cicilan',
            'flag' => 'Flag',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
            'lama_cicilan' => 'satuan bulan',
        ]);
    }



    /**
     * @inheritdoc
     * @return \app\models\query\MasterCicilanBulanQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\MasterCicilanBulanQuery(get_called_class());
    }



    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'lama_cicilan',
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
