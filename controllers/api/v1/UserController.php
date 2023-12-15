<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "UserController".
 */

use app\components\Angka;
use app\components\Constant;
use app\components\Email;
use app\components\SSOToken;
use app\models\Konsultasi;
use app\models\User;
use app\models\UserOtp;
use app\models\UserSocialMedia;
use Yii;
use yii\web\HttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class UserController extends \yii\rest\ActiveController
{
    use \app\components\UploadFile;
    public $modelClass = 'app\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authentication'] = [
            'class' => \app\components\CustomAuth::class,
            'except' => ['login', 'register', 'kirim-otp', 'reset-password', 'register-sosmed'],
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['view']);
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        return $actions;
    }

    public function actionLogout()
    {
        $user = User::findOne(["id" => Yii::$app->user->id]);
        if ($user == null) {
            throw new HttpException(404);
        }

        $user->fcm_token = null;
        $user->secret_token = null;
        $user->save(false);
        return ["success" => true, "message" => "Berhasil logout"];
    }

    public function actionSecretMethodToCheckYourTokenIsValidOrNot()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $response = SSOToken::checkToken();
        return $response;
    }

    public function actionThisIsReallyReallySecretMethodToGetDataForRegistrationAnotherModule()
    {
        $user = User::findOne(["id" => Yii::$app->user->id]);
        if ($user == null) {
            throw new HttpException(404);
        }

        $fields = $_POST['fields'];

        $data = [];
        if (is_array($fields)) :
            foreach ($fields as $field) :
                if ($user->hasAttribute($field)) :
                    $data[$field] = $user->$field;
                endif;

            endforeach;
        else :
            if ($user->hasAttribute($fields)) :
                $data[$fields] = $user->$fields;
            endif;
        endif;

        return [
            "success" => true,
            "message" => "successfully fetched data.",
            "data" => $data,
        ];
    }

    public function actionRegister()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;


        $user = new \app\models\User;
        $user->scenario = \app\models\User::SCENARIO_REGISTER_APP;

        $request = \yii::$app->request->post();
        $user->load($request, '');

        $user->no_hp = Constant::purifyPhone(Yii::$app->request->post('phone'));
        $user->role_id = 3; // role
        if ($user->save()) {
            $user->password = \Yii::$app->security->generatePasswordHash($user->password);
            $generate_random_string = SSOToken::generateToken();
            $user->secret_token = $generate_random_string;
            $user->save();

            return ['success' => true, 'message' => Yii::t("action_message", "Berhasil melakukan registrasi"), 'token' => $user->secret_token];
        } else {
            throw new HttpException(422, $this->message422(
                \app\components\Constant::flattenError(
                    $user->getErrors()
                )
            ));
        }
    }

    // public function actionRegister()
    // {
    //     Yii::$app->response->format = Response::FORMAT_JSON;
    //     if (strtolower(Yii::$app->request->method) != "post") {
    //         throw new HttpException(405);
    //     }

    //     $request = \yii::$app->request->post();
    //     $user = new User;
    //     $user->scenario = $user::SCENARIO_REGISTER_APP;
    //     $user->load($request, '');
    //     // $user->username = $user->email;
    //     $user->no_hp = Constant::purifyPhone(Yii::$app->request->post('phone'));
    //     $user->role_id = 3;
    //     $user->is_active = 1;
    //     if ($user->validate()) {
    //         $user->password = \Yii::$app->security->generatePasswordHash($user->password);
    //         $generate_random_string = SSOToken::generateToken();
    //         $user->secret_token = $generate_random_string;
    //         $user->save();
    //         return ['success' => true, 'message' => 'success', 'token' => $user->secret_token];
    //     } else {
    //         throw new HttpException(400, Constant::flattenError($user->getErrors()));
    //     }
    // }

    public function actionLogin()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $params = Yii::$app->request->post();
        if (strtolower(Yii::$app->request->method) != "post") {
            throw new HttpException(405);
        }
        try {
            $user = \app\models\User::findByUsername($params['username']);
            // $valid = \app\models\User::validateUser('username', 'password');  
            // var_dump($user);die;

            if (isset($user)) :
                if (\Yii::$app->security->validatePassword($params['password'], $user->password) == false)
                    throw new HttpException(400, Yii::t("action_message", "Password Salah"));
                $user->scenario = $user::SCENARIO_UPDATE;
                $generate_random_string = SSOToken::generateToken();
                $user->secret_token = $generate_random_string;
                $user->fcm_token = $params['fcm_token'];
                $user->validate();
                $user->save();
                // var_dump($user);die;

                $token = $generate_random_string;

                return (object) [
                    "success" => true,
                    "message" => Yii::t("action_message", "Login Berhasil"),
                    "token" => $token,
                ];
            endif;
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }

        throw new HttpException(400, Yii::t("action_message", "Login gagal"));
    }

    // public function actionLogin()
    // {
    //     Yii::$app->response->format = Response::FORMAT_JSON;
    //     if (strtolower(Yii::$app->request->method) != "post") {
    //         throw new HttpException(405);
    //     }

    //     // $flag = 0;
    //     // $message = "Login gagal";
    //     // $token = "";
    //     // $data = [];

    //     try {
    //         $user = User::find()->where('binary username=:username', [':username' => $_POST['username']])->one();

    //         if (isset($user)) :
    //             if (\Yii::$app->security->validatePassword($_POST['password'], $user->password) == false) {
    //                 $message = "User tidak ditemukan";
    //                 goto end;
    //             }

    //             $user->scenario = $user::SCENARIO_UPDATE;
    //             $generate_random_string = SSOToken::generateToken();
    //             $user->secret_token = $generate_random_string;
    //             $user->fcm_token = $_POST['fcm_token'];
    //             if ($user->validate() == false) {
    //                 $message = Constant::flattenError($user->getErrors());
    //                 goto end;
    //             }
    //             if ($user->is_active == 0) {
    //                 $message = "User belum aktif";
    //                 goto end;
    //             }
    //             $user->is_active = 1;
    //             $user->save();

    //             $flag = 1;
    //             $message = 'Login Berhasil';
    //             $token = $generate_random_string;
    //             $data = $user;
    //         endif;
    //     } catch (\Exception $e) {
    //         throw new HttpException(500, $e->getMessage());
    //     }

    //     end:
    //     return (object) [
    //         "success" => ($flag == 1),
    //         "message" => $message,
    //         "token" => $token,
    //         "data" => $data,
    //     ];
    // }

    public function actionTautkan()
    {
        $user = Yii::$app->user->identity;
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (strtolower(Yii::$app->request->method) != "post") {
            throw new HttpException(405);
        }

        $user_taut = UserSocialMedia::find()->innerJoin('user', 'user.id=user_social_media.user_id')->where([
            'user_id' => $user->id,
            'type' => \Yii::$app->request->post('type'),
        ])->one();

        if ($user_taut) {
            throw new \yii\web\HttpException(400, "Telah ditautkan di " . \Yii::$app->request->post('type'));
        }

        $cek = UserSocialMedia::findOne([
            'uniqid' => \Yii::$app->request->post('id'),
            'identifier' => \Yii::$app->request->post('email'),
            'type' => \Yii::$app->request->post('type'),
        ]);

        if ($cek) {
            throw new \yii\web\HttpException(400, "Media sosial telah ditautkan");
        }

        $user_sosmed = new UserSocialMedia;
        $user_sosmed->user_id = $user->id;
        $user_sosmed->uniqid = \Yii::$app->request->post('id');
        $user_sosmed->identifier = \Yii::$app->request->post('email');
        $user_sosmed->type = \Yii::$app->request->post('type');

        if ($user_sosmed->validate() == false) {
            throw new \yii\web\HttpException(400);
        }

        $user_sosmed->save();
        return ['success' => true, 'message' => 'Berhasil menautkan akun', 'code' => 200];
    }

    public function actionRegisterSosmed()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (strtolower(Yii::$app->request->method) != "post") {
            throw new HttpException(405);
        }

        $request = \yii::$app->request->post();

        $cek = UserSocialMedia::findOne([
            'uniqid' => \Yii::$app->request->post('id'),
            'identifier' => \Yii::$app->request->post('email'),
            'type' => \Yii::$app->request->post('type'),
        ]);

        $transaction = Yii::$app->db->beginTransaction();
        if ($cek) {
            $user = $cek->user;
            if ($user->status == false) {
                throw new HttpException(400, "User belum aktif");
            }
            $generate_random_string = SSOToken::generateToken();
            $user->secret_token = $generate_random_string;
            $user->fcm_token = $_POST['fcm_token'];
            $user->last_login = date("Y-m-d H:i:s");
            $user->gid = \Yii::$app->request->post('id');
            $user->is_active = 1;

            $user->save(false);
            $transaction->commit();

            return (object) [
                "success" => true,
                "message" => 'Login Berhasil',
                "token" => $generate_random_string,
            ];
        }

        $user_exist = User::findOne(['email' => \Yii::$app->request->post('email')]);
        if ($user_exist) {
            if ($user_exist->status == false) {
                throw new HttpException(400, "User belum aktif");
            }
            $generate_random_string = SSOToken::generateToken();
            $user_exist->secret_token = $generate_random_string;
            $user_exist->is_active = 1;
            $user_exist->save(false);

            $user_sosmed = new UserSocialMedia;
            $user_sosmed->user_id = $user_exist->id;
            $user_sosmed->uniqid = \Yii::$app->request->post('id');
            $user_sosmed->identifier = \Yii::$app->request->post('email');
            $user_sosmed->type = \Yii::$app->request->post('type');

            if ($user_sosmed->validate() == false) {
                throw new \yii\web\HttpException(400);
            }
            $user_sosmed->save();
            $transaction->commit();
            return ['success' => true, 'message' => 'success', 'token' => $user_exist->secret_token];
        }

        $user = new User;
        $user->scenario = $user::SCENARIO_REGISTER_SOSMED;
        $user->load($request, '');
        $user->is_active = 1;
        $user->username = \Yii::$app->request->post('username');
        $user->name = \Yii::$app->request->post('name');
        $user->email = \Yii::$app->request->post('email');
        $user->photo_url = \Yii::$app->request->post('photo_url');
        $user->role_id = 3;
        $user->registered_at = date("Y-m-d H:i:s");
        $user->password = Yii::$app->security->generatePasswordHash(\Yii::$app->request->post('password'));

        if ($user->validate()) {
            $generate_random_string = SSOToken::generateToken();
            $user->secret_token = $generate_random_string;
            $user->save(false);

            $user_sosmed = new UserSocialMedia;
            $user_sosmed->user_id = $user->id;
            $user_sosmed->uniqid = \Yii::$app->request->post('id');
            $user_sosmed->identifier = \Yii::$app->request->post('email');
            $user_sosmed->type = \Yii::$app->request->post('type');

            if ($user_sosmed->validate() == false) throw new \yii\web\HttpException(400);

            $user_sosmed->save();
            $transaction->commit();
            return ['success' => true, 'message' => 'success', 'token' => $user->secret_token];
        } else {
            $transaction->rollBack();
            throw new HttpException(400, Constant::flattenError($user->getErrors()));
        }
    }

    public function actionUpdate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request->bodyParams;

        $user = User::findOne(["id" => Yii::$app->user->id]);
        $photo_url = $user->photo_url;
        $user->scenario = $user::SCENARIO_UPDATE;
        $user->load($request);

        $image = UploadedFile::getInstanceByName("photo_url");
        if ($image) {
            $response = $this->uploadImage($image, "user");
            if ($response->success == false) {
                throw new HttpException(419, "Gambar gagal diunggah");
            }
            $user->photo_url = $response->filename;
        } else {
            $user->photo_url = $photo_url;
        }

        $flag = 0;
        $message = "Profile gagal di update.";

        if ($user->validate()) {
            $password = $request["User"]['password'];
            if ($password) {
                $user->password = \Yii::$app->security->generatePasswordHash($user->password);
            }
            $user->save();
            $flag = 1;
            $message = "Profile berhasil di update.";
        }

        return [
            "success" => ($flag == 1),
            "message" => $message,
            "data" => $user,
        ];
    }

    public function actionView()
    {
        $message = "Data berhasil didapatkan.";
        $user = User::findOne(["id" => Yii::$app->user->id]);
        return [
            "success" => (1 == 1),
            "message" => $message,
            "data" => $user,
        ];
    }

    public function actionGetSessionId()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $chat_id = $_REQUEST['chat_id'];
        $user = Yii::$app->user->identity;

        if ($user == null) {
            SSOToken::checkToken();
            $user = Yii::$app->user->identity;
        }

        $token = Yii::$app->request->headers->get('Authorization');
        // $token = str_replace("Bearer ", "", $token);

        if ($user) {
            if ($user->role_id === Constant::ROLE_KONSULTAN) {
                $allow_chat = Konsultasi::find()->where(['ticket' => $chat_id, 'id_konsultan' => $user->id, 'is_active' => 1])->one();
            } else {
                $allow_chat = Konsultasi::find()->where(['ticket' => $chat_id, 'id_user' => $user->id, 'is_active' => 1])->one();
            }

            if ($allow_chat) {
                return [
                    "allow_chat" => true,
                    "chat_id" => $chat_id,
                    "id_user" => $user,
                    "token" => $token,
                ];
            } else {
                return [
                    "allow_chat" => false,
                    "chat_id" => $chat_id,
                    "id_user" => null,
                    "token" => $token,
                ];
            }
        } else {
            return [
                "success" => false,
                "data" => [
                    'token' => null
                ],
            ];
        }
    }


    /**
     * action to perform reset password
     * this function need input email, new_password, otp_code and fcmt_token from user
     * with request method post
     * 
     * @return throwable|array
     */
    public function actionResetPassword()
    {
        if (Constant::isMethod('post') == false) throw new HttpException(405);

        $request = Yii::$app->request->post();

        $user2 = User::find();
        if (Yii::$app->request->post('email')) {
            $user2->where(['email' => Yii::$app->request->post('email')]);
        } else if (Yii::$app->request->post('username')) {
            $user2->where(['username' => Yii::$app->request->post('username')]);
        }

        $user = $user2->one();
        if ($user == null) throw new HttpException(404, "User tidak dapat temukan");

        $user->scenario = $user::SCENARIO_RESET_PASSWORD;

        $not_used = UserOtp::find()->activeOtp($request["otp_code"], $user)->withExpired()->one();
        if ($not_used == null) throw new HttpException(404, "Token tidak valid");
        $user->password = $request['new_password'];

        if ($user->validate()) {
            $user->password = \Yii::$app->security->generatePasswordHash($request['new_password']);
            $generate_random_string = SSOToken::generateToken();
            $user->secret_token = $generate_random_string;
            $user->fcm_token = $_POST['fcm_token'];
            $user->last_login = date("Y-m-d H:i:s");

            $user->validate();
            $user->save();

            $not_used->is_used = 1;
            $not_used->save();
            $user->save(false);

            return [
                "success" => true,
                "message" => "Password berhasil diupdate",
            ];
        }
        throw new HttpException(400, Constant::flattenError($user->getErrors()));
    }


    public function actionKirimOtp()
    {
        if (Constant::isMethod(['POST']) == false) throw new HttpException(405, "Method tidak diijinkan");

        $user2 = User::find();
        if (Yii::$app->request->post('email')) {
            $user2->where(['email' => Yii::$app->request->post('email')]);
        } else if (Yii::$app->request->post('username')) {
            $user2->where(['email' => Yii::$app->request->post('username')]);
        }

        $user2 = $user2->one();
        $user = Yii::$app->user->identity;
        return $this->generateOtp($user2 ?? $user);
    }


    private  function generateOtp($user)
    {
        if ($user == null) return [
            "success" => false,
            "message" => "User tidak ditemukan",
        ];

        $last_otp = UserOtp::find()->where(['user_id' => $user->id, 'is_used' => 0])->orderBy(['created_at' => SORT_DESC])->one();
        $time = time();

        if ($last_otp != null) {
            if ($time - 60 < strtotime($last_otp->created_at)) {
                $delay = (strtotime($last_otp->created_at) + 60) - $time;
                return [
                    "success" => false,
                    "message" => "Dapat mengirim ulang OTP dalam {$delay} detik",
                ];
            }
        }

        $not_used = UserOtp::find()->where(['user_id' => $user->id, 'is_used' => 0])->all();
        foreach ($not_used as $_nu) {
            $_nu->is_used = 1;
            $_nu->save();
        }

        $new_otp = new UserOtp();
        $new_otp->user_id = $user->id;
        $new_otp->otp_code = Angka::randomNumber(6);
        $new_otp->created_at = date("Y-m-d H:i:s");
        $new_otp->expired_at = date("Y-m-d H:i:s", $time + (60 * 5)); //expired 5 menit
        $new_otp->save();
        Email::send($user->email, "OTP CODE", "Kode OTP Anda " . $new_otp->otp_code . ". Hanya berlaku 5 menit");

        return [
            "success" => true,
            "message" => "OTP berhasil digenerate, Silahkan cek email anda.",
        ];
    }
}
