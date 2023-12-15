<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property int $is_active
 * @property integer $role_id
 * @property string $photo_url
 * @property string $last_login
 * @property string $last_logout
 * @property \app\models\Role $role
 */
class GoogleAuth extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = "app-user-update";
    const SCENARIO_REGISTER_APP = "app-user-appregister";

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'role_id'], 'required'],
            [['role_id', 'is_active'], 'integer'],
            [['last_login', 'last_logout'], 'safe'],
            [['username', 'name'], 'string', 'max' => 50],
            [['photo_url', 'password', 'gid'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password'], 'string', 'min' => 8],
            [['username'], 'match', 'pattern' => '/^[A-Za-z0-9_\-]+$/u'], // only allowed alphanumeric
        ];
    }

    public function scenarios()
    {
        $parent = parent::scenarios();
        $columns = ["gid", "name", "photo_url", "role_id"];

        $parent[static::SCENARIO_CREATE] = $columns;
        $parent[static::SCENARIO_REGISTER_APP] = $columns;
        return $parent;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Nama Pengguna',
            'password' => 'Kata Sandi',
            'name' => 'Nama',
            'role_id' => 'Hak Akses',
            'photo_url' => 'Url Foto',
            'ttd' => 'Tanda Tangan',
            'is_active' => 'Status Aktif',
            'last_login' => 'Terakhir Masuk',
            'last_logout' => 'Terakhir Keluar',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(\app\models\Role::class, ['id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    // public function getRoles()
    // {
    //     return $this->hasMany(\app\models\RoleUser::class, ['id_user' => 'id']);
    // }

    /**
     * @inheiritance
     */
    public function fields()
    {
        $parent = parent::fields();

        if (isset($parent['id'])) :
            unset($parent['id']);
        endif;
        if (isset($parent['password'])) :
            unset($parent['password']);
        endif;
        if (isset($parent['role_id'])) :
            unset($parent['role_id']);
        endif;
        if (isset($parent['secret_token'])) :
            unset($parent['secret_token']);
        endif;
        if (isset($parent['is_active'])) :
            unset($parent['is_active']);
            $parent['is_active'] = function ($model) {
                return $model->is_active;
            };
        endif;
        if (isset($parent['photo_url'])) :
            unset($parent['photo_url']);
            $parent['photo_url'] = function ($model) {
                return Yii::getAlias("@file/{$model->photo_url}");
            };
        endif;
        return $parent;
    }

    /**
     * @inheritdoc
     * @return \app\models\query\TransaksiQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\UserQuery(get_called_class());
    }
}
