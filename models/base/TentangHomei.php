<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build
// Modified by Defri Indra
// 2021

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "tentang_homei".
 *
 * @property integer $id
 * @property string $judul_kiri
 * @property string $judul_atas
 * @property string $gambar
 * @property string $judul_utama
 * @property string $isi
 * @property string $aliasModel
 */
abstract class TentangHomei extends \yii\db\ActiveRecord
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
        if (isset($parent['judul_kiri'])) :
            unset($parent['judul_kiri']);
            $parent['judul_kiri'] = function ($model) {
                return $model->judul_kiri;
            };
        endif;
        if (isset($parent['judul_atas'])) :
            unset($parent['judul_atas']);
            $parent['judul_atas'] = function ($model) {
                return $model->judul_atas;
            };
        endif;
        if (isset($parent['gambar'])) :
            unset($parent['gambar']);
            $parent['gambar'] = function ($model) {
                return \Yii::$app->formatter->asMyimage($model->gambar, false);
            };
        endif;
        if (isset($parent['judul_utama'])) :
            unset($parent['judul_utama']);
            $parent['judul_utama'] = function ($model) {
                return $model->judul_utama;
            };
        endif;
        if (isset($parent['isi'])) :
            unset($parent['isi']);
            $parent['isi'] = function ($model) {
                return $model->isi;
            };
        endif;



        return $parent;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tentang_homei';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['judul_kiri', 'judul_atas', 'judul_utama', 'isi'], 'required'],
            [['isi'], 'string'],
            [['judul_kiri', 'judul_atas', 'judul_utama'], 'string', 'max' => 50],
            [['gambar'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'judul_kiri' => 'Judul Kiri',
            'judul_atas' => 'Judul Atas',
            'gambar' => 'Gambar',
            'judul_utama' => 'Judul Utama',
            'isi' => 'Isi',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetailTentangHomei()
    {
        return $this->hasMany(\app\models\TentangHomeiDetail::className(), ['id_tentang_homei' => 'id']);
    }



    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'judul_kiri',
            'judul_atas',
            'gambar',
            'judul_utama',
            'isi',
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
