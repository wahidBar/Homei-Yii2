<?php
/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/
namespace app\controllers\base;

use app\models\base\MasterMaterial;
use app\models\HargaMaterial;
use app\models\LogHargaMaterial;
use app\models\MasterMaterial as ModelsMasterMaterial;
use app\models\MasterSatuan;
use app\models\search\HargaMaterialSearch;
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

/**
 * HargaMaterialController implements the CRUD actions for HargaMaterial model.
 **/
class HargaMaterialController extends \app\components\productive\DefaultActiveController
{

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     **/
    public $enableCsrfValidation = false;
    

    /**
    * Lists all HargaMaterial models.
    * @return mixed
    */
    public function actionIndex()
    {
        $searchModel  = new HargaMaterialSearch;
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
    * Creates a new HargaMaterial model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    * @return mixed
    */
    public function actionCreate()
    {
        $model = new HargaMaterial;
        $model->scenario = $model::SCENARIO_CREATE;
        
        try {
            if ($model->load($_POST)) :
                if($model->validate()):
                    if($model->save()){
                        $material = ModelsMasterMaterial::findOne(['id'=>$model->id_material]);
                        $provinsi = WilayahProvinsi::findOne(['id'=>$model->id_provinsi]);
                        $kota = WilayahKota::findOne(['id'=>$model->id_kota]);
                        $supplier = Supplier::findOne(['id'=>$model->id_supplier]);
                        $now = new Expression('NOW()');
                        $log= new LogHargaMaterial();
                        $log->nama_material=$material->nama;
                        $log->harga_material=$model->harga;
                        $log->provinsi=$provinsi->nama;
                        $log->kota=$kota->nama;
                        $log->nama_supplier=$supplier->nama_supplier;
                        $log->created_at=$now;
                        $log->save();
                    }
                    $this->messageCreateSuccess();
                    return $this->redirect(['view', 'id' => $model->id]);
                endif;
                $this->messageValidationFailed();
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
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
                if($model->validate()):
                    if($model->save()){
                        $material = ModelsMasterMaterial::findOne(['id'=>$model->id_material]);
                        $provinsi = WilayahProvinsi::findOne(['id'=>$model->id_provinsi]);
                        $kota = WilayahKota::findOne(['id'=>$model->id_kota]);
                        $supplier = Supplier::findOne(['id'=>$model->id_supplier]);
                        $now = new Expression('NOW()');
                        $log= new LogHargaMaterial();
                        $log->nama_material=$material->nama;
                        $log->harga_material=$model->harga;
                        $log->provinsi=$provinsi->nama;
                        $log->kota=$kota->nama;
                        $log->nama_supplier=$supplier->nama_supplier;
                        $log->created_at=$now;
                        $log->save();
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
        if ($isPivot == true):
            return $this->redirect(Url::previous());
        elseif (isset(\Yii::$app->session['__crudReturnUrl']) && \Yii::$app->session['__crudReturnUrl'] != '/'):
            Url::remember(null);
            $url = \Yii::$app->session['__crudReturnUrl'];
            \Yii::$app->session['__crudReturnUrl'] = null;

            return $this->redirect($url);
        else:
            return $this->redirect(['index']);
        endif;
    }

    /**
    * Finds the HargaMaterial model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    * @param integer $id
    * @return HargaMaterial the loaded model
    * @throws HttpException if the model cannot be found
    */
    protected function findModel($id)
    {
        if (($model = HargaMaterial::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
