<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build
// Modified by Defri Indra
// 2021

namespace app\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "access_log".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $username
 * @property string $role
 * @property string $controller
 * @property string $request
 * @property string $method
 * @property string $ip
 * @property string $created_at
 * @property string $aliasModel
 */
abstract class AccessLog extends \yii\db\ActiveRecord
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
        if (isset($parent['user_id'])) :
            unset($parent['user_id']);
            $parent['user_id'] = function ($model) {
                return $model->user_id;
            };
        endif;
        if (isset($parent['username'])) :
            unset($parent['username']);
            $parent['username'] = function ($model) {
                return $model->username;
            };
        endif;
        if (isset($parent['role'])) :
            unset($parent['role']);
            $parent['role'] = function ($model) {
                return $model->role;
            };
        endif;
        if (isset($parent['controller'])) :
            unset($parent['controller']);
            $parent['controller'] = function ($model) {
                return $model->controller;
            };
        endif;
        if (isset($parent['request'])) :
            unset($parent['request']);
            $parent['request'] = function ($model) {
                return $model->request;
            };
        endif;
        if (isset($parent['method'])) :
            unset($parent['method']);
            $parent['method'] = function ($model) {
                return $model->method;
            };
        endif;
        if (isset($parent['ip'])) :
            unset($parent['ip']);
            $parent['ip'] = function ($model) {
                return $model->ip;
            };
        endif;
        if (isset($parent['created_at'])) :
            unset($parent['created_at']);
            $parent['created_at'] = function ($model) {
                return \app\components\Tanggal::toReadableDate($model->created_at, false);
            };
        endif;



        return $parent;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'access_log';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => date("Y-m-d H:i:s"),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['controller', 'request', 'method', 'ip'], 'required'],
            [['request'], 'string'],
            [['created_at'], 'safe'],
            [['username', 'role'], 'string', 'max' => 100],
            [['controller'], 'string', 'max' => 140],
            [['method'], 'string', 'max' => 15],
            [['ip'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('cruds', 'ID'),
            'user_id' => Yii::t('cruds', 'Pengguna'),
            'username' => Yii::t('cruds', 'Nama Pengguna'),
            'role' => Yii::t('cruds', 'Hak Akses'),
            'controller' => Yii::t('cruds', 'Kontroler'),
            'request' => Yii::t('cruds', 'Permintaan'),
            'method' => Yii::t('cruds', 'Metode'),
            'ip' => Yii::t('cruds', 'Alamat IP'),
            'created_at' => 'Dibuat Pada',
        ];
    }



    /**
     * @inheritdoc
     * @return \app\models\query\AccessLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\AccessLogQuery(get_called_class());
    }



    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'user_id',
            'username',
            'role',
            'controller',
            'request',
            'method',
            'ip',
            'created_at',
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
