<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build
// Modified by Defri Indra
// 2021

namespace app\models\base;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\helpers\Inflector;

/**
 * This is the base-model class for table "t_supplier_material".
 *
 * @property integer $id
 * @property string $nama
 *
 * @property \app\models\TSupplierSubmaterial[] $tSupplierSubmaterials
 * @property string $aliasModel
 */
abstract class SupplierMaterial extends \yii\db\ActiveRecord
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
        if (isset($parent['slug'])) :
            unset($parent['slug']);
            $parent['slug'] = function ($model) {
                return $model->slug;
            };
        endif;
        if (isset($parent['rumus'])) :
            unset($parent['rumus']);
        endif;


        // $parent['t_supplier_submaterial'] = function($model) {
        //     $rel = $model->tSupplierSubmaterials;
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
        return 't_supplier_material';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'value' => function ($event) {
                    $parts = [$this->nama];
                    return Inflector::slug(implode('-', $parts));
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama', 'slug', 'rumus'], 'required'],
            [['nama'], 'string', 'max' => 255],
            [['rumus'], 'safe'],
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
            'slug' => 'Slug',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierSubmaterials()
    {
        return $this->hasMany(\app\models\SupplierSubmaterial::className(), ['material_id' => 'id']);
    }



    /**
     * @inheritdoc
     * @return \app\models\query\SupplierMaterialQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\SupplierMaterialQuery(get_called_class());
    }



    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'nama',
            'slug',
            'rumus',
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
