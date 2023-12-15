<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * RegisterForm is the model behind the register form.
 */
class RegisterForm extends Model
{
    public $username;
    public $password;
    public $name;
    public $email;
    public $no_hp;
    public $agreeTerm;
    public $role_id = 3; //Regular User

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password', 'email', 'name', 'no_hp', 'agreeTerm'], 'required'],
            [['password'], 'string', 'min' => 8],
            [['role_id'], 'integer'],
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function register()
    {
        $user = new User();
        $user->scenario = $user::SCENARIO_REGISTER_APP;
        $user->username = $this->username;
        $user->name = $this->name;
        $user->email = $this->email;
        $user->no_hp = $this->no_hp;
        
        $user->role_id = $this->role_id;
        $user->is_active = 1;
        // var_dump($user);die;
        if ($this->validate()) {
            $user->password = \Yii::$app->security->generatePasswordHash($this->password);
            if ($user->save()) {
                return true;
            } else {
                if ($user->errors) {
                    $this->addErrors($user->errors);
                }
                return false;
            }
        }
        return false;
    }
}
