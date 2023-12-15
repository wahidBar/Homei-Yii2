<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\base;

use app\components\Constant;
use app\models\Konsultasi;
use app\models\KonsultasiChat;
use app\models\search\KonsultasiSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;
use Yii;

/**
 * KonsultasiController implements the CRUD actions for Konsultasi model.
 **/
class KonsultasiController extends \app\components\productive\DefaultActiveController
{

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     **/
    public $enableCsrfValidation = false;


    /**
     * Lists all Konsultasi models.
     * @return mixed
     */
    public function actionIndex($ticket = null)
    {
        $this->view->title = 'Konsultasi Chat';
        $me = Constant::getUser();
        $model = Konsultasi::find()
            ->joinWith(['konsultasiChats'])
            ->andWhere(['id_konsultan' => $me->id])
            ->orderBy('t_konsultasi_chat.created_at DESC')
            ->limit(100)->all();
        if ($ticket) {
            $chat_active = Konsultasi::find()->andWhere(['id_konsultan' => $me->id, 'ticket' => $ticket])->one();
        } else {
            $chat_active = null;
        }

        if ($chat_active) {
            $user = $chat_active->user;
            KonsultasiChat::updateAll(['read' => 1], [
                'and',
                ['!=', 'user_id', $me->id],
                [
                    'read' => 0,
                    'konsultasi_id' => $chat_active->id
                ]
            ]);
            $list_chat = $chat_active->getKonsultasiChats()->all();
        } else {
            $list_chat = [];
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('index', compact('model', 'user', 'chat_active', 'list_chat', 'me'));
        }
        return $this->render('index', compact('model', 'user', 'chat_active', 'list_chat', 'me'));
    }

    /**
     * Creates a new Konsultasi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Konsultasi;
        $model->scenario = $model::SCENARIO_CREATE;

        try {
            if ($model->load($_POST)) :
                if ($model->validate()) :
                    $model->save();
                    $this->messageCreateSuccess();
                    return $this->redirect(['view', 'id' => $model->id]);
                endif;
                $this->messageValidationFailed();
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->render('create', $model->render());
    }

    /**
     * Updates an existing SuratBeritaAcaraPemasanganAlat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = $model::SCENARIO_UPDATE;

        try {
            if ($model->load($_POST)) :
                if ($model->validate()) :
                    $model->save();
                    $this->messageUpdateSuccess();
                    return $this->redirect(['view', 'id' => $model->id]);
                endif;
                $this->messageValidationFailed();
            endif;
            goto end;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->render('update', $model->render());
    }

    /**
     * Deletes an existing SuratBeritaAcaraPemasanganAlat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = $this->findModel($id);

            $model->delete();

            $transaction->commit();
            $this->messageDeleteSuccess();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            \Yii::$app->getSession()->addFlash('error', $msg);
            return $this->redirect(Url::previous());
        }

        // TODO: improve detection
        $isPivot = strstr('$id', ',');
        if ($isPivot == true) :
            return $this->redirect(Url::previous());
        elseif (isset(\Yii::$app->session['__crudReturnUrl']) && \Yii::$app->session['__crudReturnUrl'] != '/') :
            Url::remember(null);
            $url = \Yii::$app->session['__crudReturnUrl'];
            \Yii::$app->session['__crudReturnUrl'] = null;

            return $this->redirect($url);
        else :
            return $this->redirect(['index']);
        endif;
    }

    /**
     * Finds the Konsultasi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Konsultasi the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Konsultasi::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
