<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\base;

use app\components\annex\ActiveForm;
use app\models\Kontraktor;
use app\models\KontraktorDetail;
use app\models\Model;
use app\models\search\KontraktorSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;
use Exception;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * KontraktorController implements the CRUD actions for Kontraktor model.
 **/
class KontraktorController extends \app\components\productive\DefaultActiveController
{

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     **/
    public $enableCsrfValidation = false;


    /**
     * Lists all Kontraktor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new KontraktorSearch;
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
     * Creates a new Kontraktor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Kontraktor;
        $modelDetail = [new KontraktorDetail];
        $model->scenario = $model::SCENARIO_CREATE;

        try {
            if ($model->load($_POST)) :
                if ($model->validate()) :
                    if ($model->save()) {
                        $modelDetail = Model::createMultiple(KontraktorDetail::classname());
                        Model::loadMultiple($modelDetail, Yii::$app->request->post());

                        // // ajax validation
                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = Response::FORMAT_JSON;
                            return ArrayHelper::merge(
                                ActiveForm::validateMultiple($modelDetail),
                                ActiveForm::validate($model)
                            );
                        }
                        $valid = $model->validate();
                        $valid = Model::validateMultiple($modelDetail) && $valid;
                        if ($valid) {
                            $transaction = \Yii::$app->db->beginTransaction();
                            try {
                                if ($flag = $model->save(false)) {
                                    foreach ($modelDetail as $detail) {
                                        $detail->id_kontraktor = $model->id;
                                        if (!($flag = $detail->save(false))) {
                                            $transaction->rollBack();
                                            break;
                                        }
                                    }
                                }
                                if ($flag) {
                                    $transaction->commit();
                                    // var_dump($transaction);
                                    // die;
                                    return $this->redirect(['view', 'id' => $model->id]);
                                }
                            } catch (Exception $e) {
                                $transaction->rollBack();
                            }
                        }
                    }
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
        // return $this->render('create', $model->render());
        return $this->render('create', [
            'model' => $model,
            'modelDetail' => (empty($modelDetail)) ? [new KontraktorDetail] : $modelDetail
        ]);
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
        $modelDetail = $model->kontraktorDetails;
        $model->scenario = $model::SCENARIO_UPDATE;

        try {
            if ($model->load($_POST)) :
                if ($model->validate()) :
                    if ($model->save()) {
                        $oldIDs = ArrayHelper::map($modelDetail, 'id', 'id');
                        $modelDetail = Model::createMultiple(KontraktorDetail::classname(), $modelDetail);
                        Model::loadMultiple($modelDetail, Yii::$app->request->post());
                        $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelDetail, 'id', 'id')));


                        // ajax validation
                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = Response::FORMAT_JSON;
                            return ArrayHelper::merge(
                                ActiveForm::validateMultiple($modelDetail),
                                ActiveForm::validate($model)
                            );
                        }

                        // validate all models
                        $valid = $model->validate();
                        $valid = Model::validateMultiple($modelDetail) && $valid;

                        if ($valid) {
                            $transaction = \Yii::$app->db->beginTransaction();
                            try {
                                if ($flag = $model->save(false)) {
                                    if (!empty($deletedIDs)) {
                                        KontraktorDetail::findOne(['id' => $deletedIDs])->delete();
                                    }
                                    foreach ($modelDetail as $modelsBbm) {
                                        $modelsBbm->id_kontraktor = $model->id;
                                        if (!($flag = $modelsBbm->save(false))) {
                                            $transaction->rollBack();
                                            break;
                                        }
                                    }
                                }
                                if ($flag) {
                                    $transaction->commit();
                                    return $this->redirect(['view', 'id' => $model->id]);
                                }
                            } catch (Exception $e) {
                                $transaction->rollBack();
                            }
                        }
                    }
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
        return $this->render('update', [
            'model' => $model,
            'modelDetail' => (empty($modelDetail)) ? [new KontraktorDetail] : $modelDetail
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->scenario = $model::SCENARIO_DELETE;

        try {
            if ($model->validate()) :
                $model->flag = 0;
                $model->deleted_at = date("Y-m-d H:i:s");
                $model->deleted_by = Yii::$app->user->id;
                $model->save();
                $this->messageDeleteSuccess();
                return $this->redirect(['index']);
            endif;
            $this->messageValidationFailed();
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
    public function actionDeletePermanent($id)
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
     * Finds the Kontraktor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Kontraktor the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Kontraktor::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
