<?php

namespace app\models;

use app\components\UploadFile;
use Yii;
use yii\base\Model;

/**
 * RegisterForm is the model behind the register form.
 */
class RegisterTukangForm extends Model
{
    use UploadFile;

    public $type = 'api';
    public $username;
    public $password;
    public $name;
    public $email;
    public $no_hp;
    public $keahlian;
    public $id_layanan;
    public $alamat;
    public $idcard;
    public $agreeTerm;
    public $role_id = 8; // Tukang

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password', 'email', 'name', 'no_hp', 'keahlian', 'alamat', 'agreeTerm'], 'required'],
            [['alamat', 'keahlian'], 'safe'],
            [['password'], 'string', 'min' => 8],
            [['role_id'], 'integer'],
            [['username', 'name', 'email', 'no_hp'], 'string', 'max' => 255],
            [['idcard'], 'file', 'extensions' => 'jpg, png, jpeg'],
            [['agreeTerm'], 'boolean'],
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function register()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$this->validate()) {
                return false;
            }
            if ($this->agreeTerm == 0) {
                $this->addError('agreeTerm', 'Anda harus menyetujui syarat dan ketentuan yang berlaku.');
                return false;
            }

            $user = new User();
            $user->scenario = $user::SCENARIO_REGISTER_APP;
            $user->username = $this->username;
            $user->name = $this->name;
            $user->email = $this->email;
            $user->no_hp = $this->no_hp;

            $user->role_id = $this->role_id;
            $user->password = $this->password;
            $user->is_active = 1;
            $user->status = 0;
            if ($user->validate()) {
                $user->password = \Yii::$app->security->generatePasswordHash($this->password);

                $user->save();
                $tukang = new ProfileTukang();
                $tukang->nama = $this->name;
                $tukang->id_user = $user->id;
                $tukang->keahlian = $this->keahlian;
                $tukang->alamat = $this->alamat;
                if ($this->type == "api") {
                    $instance = \yii\web\UploadedFile::getInstanceByName('idcard');
                } else {
                    $instance = \yii\web\UploadedFile::getInstance($this, 'idcard');
                }

                if ($instance) {
                    $response = $this->uploadFile($instance, 'idcard');
                    if ($response->success) {
                        $tukang->foto_ktp = $response->filename;
                    } else {
                        $this->addError('idcard', $response->message);
                        return false;
                    }
                } else {
                    $this->addError('idcard', 'ID Card is required');
                    return false;
                }

                if ($tukang->validate()) {
                    $tukang->save();
                    $transaction->commit();
                    return true;
                } else {
                    $this->addErrors($tukang->errors);

                    if ($transaction->isActive) {
                        $transaction->rollBack();
                    }

                    return false;
                }
            }
            $this->addErrors($user->errors);

            if ($transaction->isActive) {
                $transaction->rollBack();
            }

            return false;
        } catch (\Throwable $th) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }
            $this->addError('username', $th->getMessage());

            return false;
        }
    }

    public function attributeLabels()
    {
        return [
            'id_layanan' => 'Layanan',
            'idcard' => 'ID Card',
        ];
    }
}
