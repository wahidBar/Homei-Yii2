<?php

namespace app\controllers;

use app\components\Constant;
use app\components\SSOToken;
use app\components\UploadFile;
use app\models\Konsultasi;
use app\models\RoleUser;
use app\models\search\UserSearch;
use app\models\User;
use dmstr\bootstrap\Tabs;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\UploadedFile;
use \Yii;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    use UploadFile;
    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     */
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        //apply role_action table for privilege (doesn't apply to super admin)
        return \app\models\Action::getAccess($this->id);
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch;
        $dataProvider = $searchModel->search($_GET);

        Tabs::clearLocalStorage();

        Url::remember();
        \Yii::$app->session['__crudReturnUrl'] = null;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember();
        Tabs::rememberActiveState();

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User;

        try {
            if (\Yii::$app->request->isPost) {
                $model->load($_POST);
                $model->is_active = 1;
                $model->password = \Yii::$app->security->generatePasswordHash($model->password);
                $image = UploadedFile::getInstance($model, 'photo_url');
                if ($image != null) {
                    $response = $this->uploadImage($image, "user_image");
                    if ($response->success == false) {
                        toastError("Gambar gagal diunggah");
                        goto end;
                    }
                    $model->photo_url = $response->filename;
                } else {
                    $model->photo_url = "/uploads/default.png";
                }

                $model->save(false);
                if ($model->save(false)) {
                    // foreach($model->role_id as $val){
                    //     $role = new RoleUser;
                    //     $role->id_user = $model->id;
                    //     $role->id_role = $val;
                    //     $role->save();
                    // }
                }
                // var_dump($model);
                // die;
                return $this->redirect(Url::previous());
            } else {
                $model->load($_GET);
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $oldMd5Password = $model->password;
        $oldPhotoUrl = $model->photo_url;

        $model->password = "";

        if ($model->load($_POST)) {
            // var_dump($model);
            // die;
            //password
            if ($model->password != "") {
                $model->password = \Yii::$app->security->generatePasswordHash($model->password);
            } else {
                $model->password = $oldMd5Password;
            }

            # get the uploaded file instance
            $image = UploadedFile::getInstance($model, 'photo_url');
            if ($image != null) {
                $response = $this->uploadImage($image, "user_image");
                if ($response->success == false) {
                    toastError("Gambar gagal diunggah");
                    goto end;
                }
                $model->photo_url = $response->filename;
                $this->deleteOne($oldPhotoUrl);
            } else {
                $model->photo_url = $oldPhotoUrl;
            }

            if ($model->save(false)) {
                Yii::$app->session->addFlash("success", "Profile successfully updated.");
            } else {
                Yii::$app->session->addFlash("danger", "Profile cannot updated.");
            }
            return $this->redirect(["index"]);
        }
        end:
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $user = $this->findModel($id);
            if (isset($user->photo_url) && file_exists(\Yii::getAlias("@app/web") . $user->photo_url)) {
                UploadFile::deleteOne($user->photo_url);
            }
            $user->delete();
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            \Yii::$app->getSession()->setFlash('error', $msg);
            return $this->redirect(Url::previous());
        }

        // TODO: improve detection
        $isPivot = strstr('$id', ',');
        if ($isPivot == true) {
            return $this->redirect(Url::previous());
        } elseif (isset(\Yii::$app->session['__crudReturnUrl']) && \Yii::$app->session['__crudReturnUrl'] != '/') {
            Url::remember(null);
            $url = \Yii::$app->session['__crudReturnUrl'];
            \Yii::$app->session['__crudReturnUrl'] = null;

            return $this->redirect($url);
        } else {
            return $this->redirect(['index']);
        }
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
                    "token" => Yii::$app->session->getId(),
                ];
            } else {
                return [
                    "allow_chat" => false,
                    "chat_id" => $chat_id,
                    "id_user" => null,
                    "token" => null,
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

    public function actionGetUser()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Yii::$app->user->identity;
        $query = Yii::$app->request->get("q");

        if ($user == null) return ["results" => []];

        $model = User::find()
            ->where(['role_id' => Constant::ROLES['user']])
            ->andWhere([
                'or',
                ['like', "username", "$query"],
                ['like', "email", "$query"],
                ['like', "name", "$query"],
                ['like', "no_hp", "$query"],
            ])
            ->select(["id", "concat(username, ' | ', email, ' | ', name) as text"])
            ->limit(25)
            ->asArray()
            ->all();

        return ["results" => $model];
    }

    /**
     * getUserTukang
     * action untuk menampilkan data user tukang
     * @return mixed
     */
    public function actionGetUserTukang()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Yii::$app->user->identity;
        $query = Yii::$app->request->get("q");

        if ($user == null) return ["results" => []];

        $model = User::find()
            ->andWhere([
                'or',
                ['like', "username", "$query"],
                ['like', "email", "$query"],
                ['like', "name", "$query"],
            ])
            ->andWhere(['role_id' => Constant::ROLE_TUKANG_SAMEDAY])
            ->select(["id", "concat(username, ' | ', email, ' | ', name) as text"])
            ->limit(25)
            ->asArray()
            ->all();

        return ["results" => $model];
    }


    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
