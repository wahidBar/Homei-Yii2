<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\base;

use app\models\IsianLanjutan;
use app\models\IsianLanjutanRuangan;
use app\models\search\IsianLanjutanSearch;
use yii\web\HttpException;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use Yii;

/**
 * IsianLanjutanController implements the CRUD actions for IsianLanjutan model.
 **/
class IsianLanjutanController extends \app\components\productive\DefaultActiveController
{

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     **/
    public $enableCsrfValidation = false;


    /**
     * Lists all IsianLanjutan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new IsianLanjutanSearch;
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
     * Creates a new IsianLanjutan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new IsianLanjutan;
        $model->scenario = $model::SCENARIO_CREATE;

        $model->list_ruangan = $oldCategoryIDs = $list_ruangan = $model->getListRuangan();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load($_POST)) :
                $model->budget = str_replace(",", "", $model->budget);

                $model->id_satuan = 2; // set default satuan
                if ($model->validate()) :
                    $model->luas_tanah = $model->panjang * $model->lebar;
                    $model->save();
                    $model->status = 1;
                    // list_ruangan
                    $list_ruangan = Yii::$app->request->post('IsianLanjutan')['list_ruangan'];
                    $deletedCategoryIDs = array_diff($oldCategoryIDs, $list_ruangan);
                    foreach ($list_ruangan as $cat) {
                        $exist = IsianLanjutanRuangan::findOne(['id_isian_lanjutan' => $model->id, 'id_ruangan' => $cat]);
                        if ($exist == false) {
                            $create_category = new IsianLanjutanRuangan();
                            $create_category->id_isian_lanjutan = $model->id;
                            $create_category->id_ruangan = $cat;
                            if ($create_category->validate() == false) {
                                $transaction->rollBack();
                                toastError("Gagal menyimpan data kategori");
                                goto end;
                            }
                            $create_category->save();
                        }
                    }
                    $transaction->commit();
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
        $model->list_ruangan = $oldCategoryIDs = $list_ruangan = $model->getListRuangan();
        $model->scenario = $model::SCENARIO_UPDATE;
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->load($_POST)) :
                $model->budget = str_replace(",", "", $model->budget);
                if ($model->status == 0) {
                    $model->status = 1;
                }

                // list_ruangan
                $list_ruangan = Yii::$app->request->post('IsianLanjutan')['list_ruangan'];
                if (is_array($list_ruangan) == false) $list_ruangan = [];
                $deletedCategoryIDs = array_diff($oldCategoryIDs, $list_ruangan);

                $model->id_satuan = 2; // set default satuan
                if ($model->validate()) :
                    $model->luas_tanah = $model->panjang * $model->lebar;

                    // list_ruangan
                    if (!empty($deletedCategoryIDs)) {
                        IsianLanjutanRuangan::deleteAll([
                            'and',
                            ['id_isian_lanjutan' => $model->id],
                            ['id_ruangan' => $deletedCategoryIDs]
                        ]);
                    }

                    foreach ($list_ruangan as $cat) {
                        $exist = IsianLanjutanRuangan::findOne(['id_isian_lanjutan' => $model->id, 'id_ruangan' => $cat]);
                        if ($exist == false) {
                            $create_category = new IsianLanjutanRuangan();
                            $create_category->id_isian_lanjutan = $model->id;
                            $create_category->id_ruangan = $cat;
                            if ($create_category->validate() == false) {
                                $transaction->rollBack();
                                toastError("Gagal menyimpan data kategori");
                                goto end;
                            }
                            $create_category->save();
                        }
                    }

                    \app\components\Notif::log(
                        $model->id_user,
                        "Admin Telah Mengisi Data Proyek",
                        "Hallo {$model->user->name}, Admin telah melengkapi data untuk proyek Anda.",
                        [
                            "controller" => "home/form-rencana-pembangunan/view",
                            "android_route" => "app-checkout",
                            "params" => [
                                "id" => $model->kode_unik
                            ]
                        ]
                    );

                    $model->save();
                    $transaction->commit();
                    $this->messageUpdateSuccess();
                    return $this->redirect(['view', 'id' => $model->id]);
                endif;
                $this->messageValidationFailed();
            endif;
            goto end;
        } catch (\Exception $e) {
            dd($e);
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
            $model->scenario = $model::SCENARIO_DELETE;
            $model->deleted_by = Yii::$app->user->id;
            $model->flag = 0;
            $model->save();

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
     * Finds the IsianLanjutan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IsianLanjutan the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IsianLanjutan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
