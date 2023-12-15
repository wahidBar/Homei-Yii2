<?php

namespace app\components\productive;

use app\components\Constant;
use app\models\AccessLog;
use app\models\Action;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class DefaultActiveController extends Controller
{
    use Messages;

    public function afterAction($action, $result)
    {

        // other custom code here
        $log = new AccessLog();
        $log->ip = Yii::$app->request->userIP;
        $log->controller = get_called_class();
        $log->request = json_encode(Yii::$app->request->bodyParams);
        $log->method = Yii::$app->request->method;
        $log->type = "web";

        $user = Constant::getUser();
        if ($user) {
            $log->user_id = $user->id;
            $log->username = $user->username;
            $log->role = $user->role->name;
        } else {
            $log->user_id = null;
            $log->username = null;
            $log->role = null;
        }

        $log->save();


        return parent::afterAction($action, $result); // or false to not run the action
    }


    /**
     * RBAC filter
     */
    public function behaviors()
    {
        return Action::getAccess($this->id);
    }

    /**
     * Displays a single SuratBeritaAcaraSosialisasi model.
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
}
