<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\base;

use app\models\HargaMaterial;
use app\models\LogHargaMaterial;
use app\models\MasterMaterial;
use app\models\Model;
use app\models\search\MasterMaterialSearch;
use app\models\Supplier;
use app\models\WilayahKota;
use app\models\WilayahProvinsi;
use yii\web\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * MasterMaterialController implements the CRUD actions for MasterMaterial model.
 **/
class MasterMaterialController extends \app\components\productive\DefaultActiveController
{

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     **/
    public $enableCsrfValidation = false;


    /**
     * Lists all MasterMaterial models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new MasterMaterialSearch;
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
     * Creates a new MasterMaterial model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $dbtrans = Yii::$app->db->beginTransaction();
        $model = new MasterMaterial;
        $modelHarga = [new HargaMaterial()];
        $model->scenario = $model::SCENARIO_CREATE;

        try {
            if ($model->load($_POST)) :
                if ($model->validate()) :
                    $model->save();

                    $harga = Model::createMultiple(HargaMaterial::classname());
                    Model::loadMultiple($harga, Yii::$app->request->post());

                    foreach ($harga as $idx => $item) {
                        $harga[$idx]->id_material = $model->id;
                        $u = str_replace(",", "", $harga[$idx]->harga);
                        $harga[$idx]->harga = $u;
                    }

                    // validate all models
                    $valid = $model->validate();
                    $valid = Model::validateMultiple($harga) && $valid;

                    if ($valid) {
                        foreach ($harga as $idx => $item) {
                            if($item->save()){
                                $material = MasterMaterial::findOne(['id'=>$item->id_material]);
                        $provinsi = WilayahProvinsi::findOne(['id'=>$item->id_provinsi]);
                        $kota = WilayahKota::findOne(['id'=>$item->id_kota]);
                        $supplier = Supplier::findOne(['id'=>$item->id_supplier]);
                        $now = new Expression('NOW()');
                        $log= new LogHargaMaterial();
                        $log->nama_material=$material->nama;
                        $log->harga_material=$item->harga;
                        $log->provinsi=$provinsi->nama;
                        $log->kota=$kota->nama;
                        $log->nama_supplier=$supplier->nama_supplier;
                        $log->created_at=$now;
                        $log->save();
                            }
                            // $item->save();
                        }

                        $dbtrans->commit();
                        $this->messageCreateSuccess();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                endif;
                $dbtrans->rollBack();
                $this->messageValidationFailed();
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $dbtrans->rollBack();
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        $model->setRender(compact('model', 'modelHarga'));
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

        $dbtrans = Yii::$app->db->beginTransaction();
        $model = $this->findModel($id);
        $modelHarga = $model->hargaMaterials;
        $model->scenario = $model::SCENARIO_UPDATE;

        try {
            if ($model->load($_POST)) :
                if ($model->validate()) :
                    if($model->save()){
                    $oldIDs = ArrayHelper::map($modelHarga, 'id', 'id');
                    $harga = Model::createMultiple(HargaMaterial::classname(),$modelHarga);
                    Model::loadMultiple($harga, Yii::$app->request->post());
                    $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelHarga, 'id', 'id')));

                    foreach ($harga as $idx => $item) {
                        $harga[$idx]->id_material = $model->id;
                        $u = str_replace(",", "", $harga[$idx]->harga);
                        $harga[$idx]->harga = $u;
                    }
                    // var_dump($harga);die;
                    // validate all models
                    $valid = $model->validate();
                    $valid = Model::validateMultiple($harga) && $valid;
                    // var_dump($modelHarga);die;
                    if ($valid) {
                        if (!empty($deletedIDs)) {
                            HargaMaterial::findOne(['id' => $deletedIDs])->delete();
                        }
                        foreach ($harga as $idx => $item) {

                            $item->id_material = $model->id;
                            if($item->save()){
                                $material = MasterMaterial::findOne(['id'=>$item->id_material]);
                        $provinsi = WilayahProvinsi::findOne(['id'=>$item->id_provinsi]);
                        $kota = WilayahKota::findOne(['id'=>$item->id_kota]);
                        $supplier = Supplier::findOne(['id'=>$item->id_supplier]);
                        $now = new Expression('NOW()');
                        $log= new LogHargaMaterial();
                        $log->nama_material=$material->nama;
                        $log->harga_material=$item->harga;
                        $log->provinsi=$provinsi->nama;
                        $log->kota=$kota->nama;
                        $log->nama_supplier=$supplier->nama_supplier;
                        $log->created_at=$now;
                        $log->save();
                            }
                            // $item->save();
                        }
                    }
                        $dbtrans->commit();
                    $this->messageUpdateSuccess();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                endif;
                $this->messageValidationFailed();
            endif;
            goto end;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        $model->setRender(compact('model', 'modelHarga'));
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
     * Finds the MasterMaterial model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MasterMaterial the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MasterMaterial::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
