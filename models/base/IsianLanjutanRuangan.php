<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build
// Modified by Defri Indra
// 2021

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "t_isian_lanjutan_ruangan".
 *
 * @property integer $id
 * @property integer $id_isian_lanjutan
 * @property integer $id_ruangan
 *
 * @property \app\models\IsianLanjutan $isianLanjutan
 * @property \app\models\IsianLanjutan $isianLanjutan0
 * @property \app\models\MasterRuangan $ruangan
 * @property string $aliasModel
 */
abstract class IsianLanjutanRuangan extends \yii\db\ActiveRecord
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
        if (isset($parent['id_isian_lanjutan'])) :
            unset($parent['id_isian_lanjutan']);
            $parent['id_isian_lanjutan'] = function ($model) {
                return $model->id_isian_lanjutan;
            };
            $parent['_isian_lanjutan'] = function ($model) {
                $rel = $model->isianLanjutan;
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
            $parent['_isian_lanjutan0'] = function ($model) {
                $rel = $model->isianLanjutan0;
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
        endif;
        if (isset($parent['id_ruangan'])) :
            unset($parent['id_ruangan']);
            $parent['id_ruangan'] = function ($model) {
                return $model->id_ruangan;
            };
            $parent['_ruangan'] = function ($model) {
                $rel = $model->ruangan;
                if ($rel) :
                    return $rel;
                endif;
                return null;
            };
        endif;



        return $parent;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_isian_lanjutan_ruangan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_isian_lanjutan', 'id_ruangan'], 'required'],
            [['id_isian_lanjutan', 'id_ruangan'], 'integer'],
            [['id_isian_lanjutan'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\IsianLanjutan::className(), 'targetAttribute' => ['id_isian_lanjutan' => 'id']],
            [['id_isian_lanjutan'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\IsianLanjutan::className(), 'targetAttribute' => ['id_isian_lanjutan' => 'id']],
            [['id_ruangan'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\MasterRuangan::className(), 'targetAttribute' => ['id_ruangan' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_isian_lanjutan' => 'Isian Lanjutan',
            'id_ruangan' => 'Ruangan',
        ];
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
    public function getIsianLanjutan0()
    {
        return $this->hasOne(\app\models\IsianLanjutan::className(), ['id' => 'id_isian_lanjutan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuangan()
    {
        return $this->hasOne(\app\models\MasterRuangan::className(), ['id' => 'id_ruangan']);
    }



    /**
     * @inheritdoc
     * @return \app\models\query\IsianLanjuanRuanganQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\IsianLanjuanRuanganQuery(get_called_class());
    }



    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'id_isian_lanjutan',
            'id_ruangan',
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
