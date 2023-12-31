<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build
// Modified by Defri Indra
// 2021

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "t_smarthome".
 *
 * @property integer $id
 * @property integer $id_user
 * @property string $nama
 * @property string $suhu
 * @property string $kelembapan
 * @property string $tegangan
 * @property string $daya
 * @property string $ampere
 * @property string $token
 * @property integer $flag
 *
 * @property \app\models\User $user
 * @property \app\models\SmarthomeKontrol[] $smarthomeKontrols
 * @property \app\models\SmarthomeSirkuit[] $smarthomeSirkuits
 * @property string $aliasModel
 */
abstract class Smarthome extends \yii\db\ActiveRecord
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
        if (isset($parent['id_user'])) :
            unset($parent['id_user']);
            $parent['id_user'] = function ($model) {
                return $model->id_user;
            };
            $parent['_user'] = function ($model) {
                $rel = $model->user;
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
        if (isset($parent['suhu'])) :
            unset($parent['suhu']);
            $parent['suhu'] = function ($model) {
                return $model->suhu;
            };
        endif;
        if (isset($parent['kelembapan'])) :
            unset($parent['kelembapan']);
            $parent['kelembapan'] = function ($model) {
                return $model->kelembapan;
            };
        endif;
        if (isset($parent['tegangan'])) :
            unset($parent['tegangan']);
            $parent['tegangan'] = function ($model) {
                return $model->tegangan;
            };
        endif;
        if (isset($parent['daya'])) :
            unset($parent['daya']);
            $parent['daya'] = function ($model) {
                return $model->daya;
            };
        endif;
        if (isset($parent['ampere'])) :
            unset($parent['ampere']);
            $parent['ampere'] = function ($model) {
                return $model->ampere;
            };
        endif;
        if (isset($parent['token'])) :
            unset($parent['token']);
            $parent['token'] = function ($model) {
                return $model->token;
            };
        endif;
        if (isset($parent['flag'])) :
            unset($parent['flag']);
            $parent['flag'] = function ($model) {
                return $model->flag;
            };
        endif;


        // $parent['smarthome_kontrol'] = function($model) {
        //     $rel = $model->smarthomeKontrols;
        //     if($rel) :
        //         return $rel;
        //     endif;
        //     return null;
        // };
        // $parent['smarthome_sirkuit'] = function($model) {
        //     $rel = $model->smarthomeSirkuits;
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
        return 't_smarthome';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'nama'], 'required'],
            [['id_user', 'flag'], 'integer'],
            [['nama', 'token'], 'string', 'max' => 100],
            [['suhu', 'kelembapan', 'tegangan', 'daya', 'ampere'], 'string', 'max' => 10],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\User::className(), 'targetAttribute' => ['id_user' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('cruds', 'ID'),
            'id_user' => Yii::t('cruds', 'Pengguna'),
            'nama' => Yii::t('cruds', 'Nama'),
            'suhu' => Yii::t('cruds', 'Suhu'),
            'kelembapan' => Yii::t('cruds', 'Kelembapan'),
            'tegangan' => Yii::t('cruds', 'Tegangan'),
            'daya' => Yii::t('cruds', 'Daya'),
            'ampere' => Yii::t('cruds', 'Ampere'),
            'token' => Yii::t('cruds', 'Token'),
            'flag' => Yii::t('cruds', 'Flag'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSmarthomeKontrols()
    {
        return $this->hasMany(\app\models\SmarthomeKontrol::className(), ['id_smarthome' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSmarthomeSirkuits()
    {
        return $this->hasMany(\app\models\SmarthomeSirkuit::className(), ['id_smarthome' => 'id']);
    }



    /**
     * @inheritdoc
     * @return \app\models\query\SmarthomeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\SmarthomeQuery(get_called_class());
    }



    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'id_user',
            'nama',
            'suhu',
            'kelembapan',
            'tegangan',
            'daya',
            'ampere',
            'token',
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
