<?php

namespace app\models\base;

use app\components\Constant;
use Yii;
use yii\behaviors\TimestampBehavior;

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
class User extends \yii\db\ActiveRecord
{
    const SCENARIO_UPDATE = "app-user-update";
    const SCENARIO_USEREDIT = "app-user-edit";
    const SCENARIO_LOGIN = "login";
    const SCENARIO_LOGOUT = "logout";
    const SCENARIO_LUPA = "lupa";
    const SCENARIO_PASSWORD = "password";

    /**
     * App Scenario
     */
    const SCENARIO_REGISTER_APP = "app-user-appregister";
    const SCENARIO_REGISTER_SOSMED = "register-sosmed";
    const SCENARIO_RESET_PASSWORD = "reset-password";

    public $_render = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'registered_at',
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
            [['username', 'password', 'name', 'email', 'no_hp', 'role_id'], 'required'],
            [['role_id', 'is_active', 'gid', 'token_is_used'], 'integer'],
            [['last_login', 'last_logout'], 'safe'],
            [['username', 'name'], 'string', 'max' => 50],
            [['photo_url', 'password', 'token', 'token_created_at'], 'string', 'max' => 255],
            [['photo_url'], 'file', 'except' => static::SCENARIO_REGISTER_SOSMED, 'skipOnEmpty' => false, 'maxSize' => 1024 * 1024 * 2, 'extensions' => 'png, jpg, jpeg, gif'],
            [['photo_url'], 'string', 'on' => static::SCENARIO_REGISTER_SOSMED],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password'], 'string', 'min' => 8],
            [['username'], 'match', 'pattern' => '/^[A-Za-z0-9_\-\.\@]+$/u'], // only allowed alphanumeric & special character @ and .
        ];
    }

    public function scenarios()
    {
        $parent = parent::scenarios();
        $columns = ["username", "password", "name", "email", "no_hp", "role_id"];
        $edit = ["username", "password", "name", "no_hp", "email", "photo_url"];
        $column_register = ["email", "username", "password", "name", "no_hp"];
        $login = ["is_active", "last_login"];
        $logout = ["is_active", "last_logout"];
        $lupa = ["token", "token_created_at", "token_is_used"];
        $ganti = ["token", "token_created_at", "token_is_used", "password"];

        $parent[static::SCENARIO_UPDATE] = $columns;
        $parent[static::SCENARIO_USEREDIT] = $edit;
        $parent[static::SCENARIO_LOGIN] = $login;
        $parent[static::SCENARIO_LOGOUT] = $logout;
        $parent[static::SCENARIO_LUPA] = $lupa;
        $parent[static::SCENARIO_PASSWORD] = $ganti;

        $parent[static::SCENARIO_REGISTER_APP] = $column_register;
        $parent[static::SCENARIO_RESET_PASSWORD] = [
            "password",
            "secret_token",
            "fcm_token",
            "last_login",
        ];

        $parent[static::SCENARIO_REGISTER_SOSMED] = [
            "username",
            "name",
            // "password",
            "photo_url",
            "last_login",
        ];

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
            'password' => 'Password',
            'name' => 'Nama',
            'no_hp' => 'No. HP',
            'role_id' => 'Hak Akses',
            'photo_url' => 'Foto Profil',
            'ttd' => 'Tanda Tangan',
            'is_active' => 'Pengguna Aktif',
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
        if (isset($parent['username'])) :
        // unset($parent['username']);
        endif;
        if (isset($parent['name'])) :
            unset($parent['name']);
            $parent['name'] = function ($model) {
                return $model->name;
            };
        endif;
        if (isset($parent['email'])) :
            unset($parent['email']);
            $parent['email'] = function ($model) {
                return $model->email;
            };
        endif;
        if (isset($parent['no_hp'])) :
            unset($parent['no_hp']);
            $parent['phone'] = function ($model) {
                return $model->no_hp;
            };
        endif;
        if (isset($parent['password'])) :
            unset($parent['password']);
        endif;
        if (isset($parent['role_id'])) :
            unset($parent['role_id']);
            $parent['role_id'] = function ($model) {
                return $model->role_id;
            };
            $parent['_role'] = function ($model) {
                return ($rel = $model->role) ? $rel->name : null;
            };
        endif;
        if (isset($parent['secret_token'])) :
            unset($parent['secret_token']);
        endif;
        if (isset($parent['gid'])) :
            unset($parent['gid']);
        endif;
        if (isset($parent['email'])) :
            unset($parent['email']);
        endif;
        if (isset($parent['token'])) :
            unset($parent['token']);
        endif;
        if (isset($parent['token_created_at'])) :
            unset($parent['token_created_at']);
        endif;
        if (isset($parent['token_is_userd'])) :
            unset($parent['token_is_userd']);
        endif;
        if (isset($parent['flag'])) :
            unset($parent['flag']);
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
                return \Yii::$app->formatter->asMyImage($model->photo_url, false, Constant::DEFAULT_IMAGE);
            };
        endif;

        $parent['is_constructor'] = function ($model) {
            return \app\models\ProyekAnggota::find()->where(['id_user' => $model->id])->exists();
        };

        return $parent;
    }

    public function getSupplier()
    {
        return $this->hasOne(\app\models\Supplier::class, ['id_user' => 'id']);
    }

    public function getProyekAnggota() {
        return $this->hasMany(\app\models\ProyekAnggota::class, ['id_user' => 'id']);
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
