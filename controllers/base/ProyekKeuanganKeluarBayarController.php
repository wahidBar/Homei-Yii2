<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\base;

use app\components\Constant;
use app\models\ProyekKeuanganKeluarBayar;
use app\models\search\ProyekKeuanganKeluarBayarSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;
use Yii;

/**
 * ProyekKeuanganKeluarBayarController implements the CRUD actions for ProyekKeuanganKeluarBayar model.
 **/
class ProyekKeuanganKeluarBayarController extends \app\components\productive\DefaultActiveController
{

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     **/
    public $enableCsrfValidation = false;


    /**
     * Displays a single SuratBeritaAcaraSosialisasi model.
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id_project)
    {
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember();
        Tabs::rememberActiveState();

        return $this->render('view', [
            'model' => $this->findModel($id_project),
        ]);
    }

    /**
     * Creates a new ProyekKeuanganKeluarBayar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_project)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $model = new ProyekKeuanganKeluarBayar;
        $model->scenario = $model::SCENARIO_CREATE;
        $model->load($_GET);
        $keuanganKeluar = $model->keuanganKeluar;
        if ($keuanganKeluar == null) throw new HttpException(404);

        // $id_proyek = $model->id_proyek;
        // $id_keuangan_keluar = $keuanganKeluar->id;

        if (Yii::$app->request->isAjax) $render = "renderAjax";
        else $render = "render";

        try {
            if ($model->load($_POST)) :
                $model->dibayar = intval(str_replace(",", "", $model->dibayar));
                if ($model->dibayar <= 0) {
                    toastError("Nilai dibayarkan harus lebih besar dari 0");
                    goto end;
                }
                if ($model->validate()) :
                    $model->flag = 1;
                    $model->save();

                    $keuanganKeluar->total_dibayarkan = $keuanganKeluar->getProyekKeuanganKeluarBayars()->where(['flag' => 1])->sum('dibayar');
                    $keuanganKeluar->status = 1;

                    if ($keuanganKeluar->getProyekKeuanganKeluarBayars()->count() == 0) {
                        $keuanganKeluar->status = 0;
                    } else if (intval($keuanganKeluar->total_dibayarkan) > intval($keuanganKeluar->total_jumlah)) {
                        $transaction->rollBack();
                        $keuanganKeluar->total_dibayarkan = $keuanganKeluar->getProyekKeuanganKeluarBayars()->sum('dibayar'); // refresh nilai ke asal
                        toastError("Pembayaran tidak boleh melebihi total jumlah");
                        goto end;
                    } else if (intval($keuanganKeluar->total_dibayarkan) == intval($keuanganKeluar->total_jumlah)) {
                        $keuanganKeluar->status = 2;
                    }

                    $keuanganKeluar->save();
                    $transaction->commit();
                    $this->messageCreateSuccess();
                    // return $this->redirect(['/proyek-keuangan-keluar-bayar/create', 'ProyekKeuanganKeluarBayar' => ["id_proyek" => $id_proyek, "id_keuangan_keluar" => $id_keuangan_keluar]]);
                    return $this->redirect(['/keuangan/view', 'id' => $model->id_proyek]);
                endif;
                $this->messageValidationFailed();
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $transaction->rollBack();
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        $model->setRender(['keuanganKeluar' => $keuanganKeluar]);
        return $this->$render('create', $model->render());
    }

    /**
     * Updates an existing SuratBeritaAcaraPemasanganAlat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id_project, $id)
    {
        $model = $this->findModel($id);
        $model->scenario = $model::SCENARIO_UPDATE;

        if (Yii::$app->request->isAjax) $render = "renderAjax";
        else $render = "render";

        try {
            if ($model->load($_POST)) :
                if ($model->validate()) :
                    $model->save();
                    $this->messageUpdateSuccess();
                    return $this->redirect(['/keuangan/view', 'id' => $model->id_proyek]);
                endif;
                $this->messageValidationFailed();
            endif;
            goto end;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->$render('update', $model->render());
    }

    /**
     * Deletes an existing SuratBeritaAcaraPemasanganAlat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id_project, $id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = $this->findModel($id);
            $model->scenario = $model::SCENARIO_DELETE;
            $id_proyek = $model->id_proyek;
            $id_keuangan_keluar = $model->id_keuangan_keluar;
            $keuanganKeluar = $model->keuanganKeluar;

            // $model->dibayar = 0;
            $model->deleted_at = date("Y-m-d H:i:s");
            $model->deleted_by = Constant::getUser()->id;
            $model->flag = 0;
            if ($model->validate() == false) {
                toastError("Telah terjadi kesalahan");
                $transaction->rollBack();
                goto end;
            }
            $model->save();
            $keuanganKeluar->total_dibayarkan = intval($keuanganKeluar->getProyekKeuanganKeluarBayars()->where(['is', 'deleted_at', null])->sum('dibayar'));
            if ($keuanganKeluar->total_dibayarkan == 0) {
                $keuanganKeluar->status = 0;
            } else if ($keuanganKeluar->total_dibayarkan != $keuanganKeluar->total_jumlah) {
                $keuanganKeluar->status = 1;
            }

            if ($keuanganKeluar->validate() == false) {
                toastError("Telah terjadi kesalahan");
                $transaction->rollBack();
                goto end;
            }
            $keuanganKeluar->save();



            $transaction->commit();
            $this->messageDeleteSuccess();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            \Yii::$app->getSession()->addFlash('error', $msg);
            return $this->redirect(Url::previous());
        }
        end:
        // return $this->redirect(['/proyek-keuangan-keluar-bayar/create', 'ProyekKeuanganKeluarBayar' => ["id_proyek" => $id_proyek, "id_keuangan_keluar" => $id_keuangan_keluar]]);
        return $this->redirect(['/keuangan/view', 'id' => $model->id_proyek]);
    }

    /**
     * Finds the ProyekKeuanganKeluarBayar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProyekKeuanganKeluarBayar the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProyekKeuanganKeluarBayar::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
