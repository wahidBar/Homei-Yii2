<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build
// Modified by Defri Indra
// 2021

namespace app\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "t_notification".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $description
 * @property string $controller
 * @property string $params
 * @property integer $read
 * @property string $created_at
 *
 * @property \app\models\User $user
 * @property string $aliasModel
 */
abstract class Notification extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_CREATE_ADMIN = 'create-admin';
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
        // if(isset($parent['user_id'])) :
        //     unset($parent['user_id']);
        //     $parent['user_id'] = function($model) {
        //         return $model->user_id;
        //     };
        //     $parent['_user'] = function($model) {
        //         $rel = $model->user;
        //         if ($rel) :
        //             return $rel;
        //         endif;
        //         return null;
        //     };
        // endif;
        if (isset($parent['title'])) :
            unset($parent['title']);
            $parent['title'] = function ($model) {
                return $model->title;
            };
        endif;
        if (isset($parent['description'])) :
            unset($parent['description']);
            $parent['description'] = function ($model) {
                return $model->description;
            };
        endif;
        if (isset($parent['controller'])) :
            unset($parent['controller']);
            $parent['controller'] = function ($model) {
                return $model->controller;
            };
        endif;
        if (isset($parent['params'])) :
            unset($parent['params']);
            $parent['params'] = function ($model) {
                return $model->params;
            };
        endif;
        if (isset($parent['read'])) :
            unset($parent['read']);
            $parent['read'] = function ($model) {
                return $model->read;
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
        return 't_notification';
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
            [['title', 'description', 'controller'], 'required'],
            [['user_id', 'read'], 'integer'],
            [['description', 'params'], 'string'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['controller'], 'string', 'max' => 150],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\User::className(), 'targetAttribute' => ['user_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Pengguna',
            'title' => 'Judul',
            'description' => 'Deskripsi',
            'controller' => 'Kontroler',
            'params' => 'Parameter',
            'read' => 'Sudah dibaca',
            'created_at' => 'Dibuat pada',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'user_id']);
    }



    /**
     * @inheritdoc
     * @return \app\models\query\NotificationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\NotificationQuery(get_called_class());
    }



    public function scenarios()
    {
        $parent = parent::scenarios();

        $columns = [
            'id',
            'user_id',
            'title',
            'description',
            'controller',
            'params',
            'read',
            'created_at',
        ];

        $admin = [
            'id',
            'title',
            'description',
            'controller',
            'params',
            'read',
            'created_at',
        ];

        $parent[static::SCENARIO_CREATE] = $columns;
        $parent[static::SCENARIO_CREATE_ADMIN] = $admin;
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
