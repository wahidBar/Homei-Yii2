<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\base;

use app\models\Smarthome;
use app\models\search\SmarthomeSearch;
use yii\web\HttpException;
use yii\helpers\Url;
use app\components\annex\Tabs;
use app\components\Constant;
use app\components\ConstantHomeis;
use app\models\SmarthomeKontrol;
use app\models\SmarthomeLog;
use app\models\SmarthomeMasterProduk;
use app\models\SmarthomeSirkuit;
use Yii;
use yii\web\Response;

/**
 * SmarthomeController implements the CRUD actions for Smarthome model.
 **/
class SmarthomeController extends \app\components\productive\DefaultActiveController
{

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     **/
    public $enableCsrfValidation = false;


    /**
     * Lists all Smarthome models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new SmarthomeSearch;
        $dataProvider = $searchModel->search($_GET);

        Tabs::clearLocalStorage();

        Url::remember();
        \Yii::$app->session['__crudReturnUrl'] = null;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        // apex chart category hours
        $category_hours = null;
        // $category_hours = [];
        // for ($i = 0; $i < 24; $i++) {
        //     $category_hours[] = $i;
        // }

        $dropdown = SmarthomeSirkuit::find()->select(['id', 'nama'])->where(['id_smarthome' => $model->id])->active()->asArray()->all();

        $sirkuit_id = Yii::$app->request->get('sirkuit_id');
        $sirkuit = SmarthomeLog::getLogPerSirkuit($dropdown, $sirkuit_id);

        $graphdaya = $this->generateGraph($model, 'daya', $category_hours, $sirkuit_id);
        $graphampere = $this->generateGraph($model, 'ampere', $category_hours, $sirkuit_id);

        return $this->render('view', compact('model', 'graphdaya', 'graphampere', 'sirkuit', 'dropdown'));
    }

    protected function generateGraph($model, $data_variable, $category_hours = null, $sirkuit_id = null)
    {
        // colors
        $graph['colors'] = [];

        // create series by id_sirkuit
        $graph['series'] = [];
        $list_sirkuit = $model->getSmarthomeSirkuits()->select(['id', 'nama']);
        if ($sirkuit_id) {
            $list_sirkuit->andWhere(['id' => $sirkuit_id]);
        }
        $list_sirkuit = $list_sirkuit->limit(1)->active()->all();

        $graph['categories'] = [];
        foreach ($list_sirkuit as $sirkuit) {
            // add random color
            $graph['colors'][] = '#' . substr(md5(rand()), 0, 6);
            $graph['series'][] = [
                'name' => $sirkuit->nama,
                'data' => SmarthomeLog::getDataSirkuitperJam($model->id, $sirkuit->id, $category_hours, $data_variable),
            ];
        }

        // set categories to chart
        if ($category_hours == null) {
            foreach ($graph['series'] as $key => $value) {
                $graph['categories'] = array_keys($value['data']);
                $graph['series'][$key]['data'] = array_values($graph['series'][$key]['data']);
            }
        } else {
            $graph['categories'] = $category_hours;
        }

        // set graph
        $graph['title'] = 'Grafik ' . $data_variable;
        $graph['subtitle'] = 'Periode terakhir 10 data';
        $graph['yAxis'] = $data_variable;
        $graph['type'] = 'line';
        return $graph;
    }

    /**
     * Creates a new Smarthome model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Smarthome;
        $model->scenario = $model::SCENARIO_CREATE;

        $model->initValue();

        try {
            if ($model->load($_POST)) :
                $model->initValue();
                $model->token = Constant::generateRandomString(15);
                if ($model->validate()) :
                    $model->save();
                    $this->messageCreateSuccess();
                    return $this->redirect(['update', 'id' => $model->id]);
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
        Tabs::rememberActiveState(['relation-tabs-form', 'relation-tabs-list']);

        if ($_GET['_detail']) {
            $modelKontrol = SmarthomeKontrol::findOne($_GET['_detail']);
            if (!$modelKontrol) {
                throw new HttpException(404, 'Data tidak ditemukan');
            }

            $dropdownPin = [
                $modelKontrol->pin  => ConstantHomeis::PIN[$modelKontrol->pin],
            ];
        } else {
            $modelKontrol = new SmarthomeKontrol();
            $dropdownPin = [];
        }

        if ($_GET['_sirkuit']) {
            $modelSirkuit = SmarthomeSirkuit::findOne($_GET['_sirkuit']);
            if (!$modelSirkuit) {
                throw new HttpException(404, 'Data tidak ditemukan');
            }
        } else {
            $modelSirkuit = new SmarthomeSirkuit();
        }

        try {
            if ($model->load($_POST)) :
                if ($model->validate()) :
                    $model->save();
                    $this->messageUpdateSuccess("Smart Home");
                else :
                    $this->messageValidationFailed();
                endif;
                $redirect = true;
            endif;
            if ($modelKontrol->load($_POST)) :
                if ($modelKontrol->isNewRecord) {
                    $total_detail = intval($model->getSmarthomeKontrols()->where(['id_sirkuit' => $modelKontrol->id_sirkuit])->count());
                    if ($total_detail > 5) {
                        $this->messageValidationFailed("maksimal 6 kontrol");
                        goto end;
                    }

                    $pin_disirkuit = $model->getSmarthomeKontrols()->where(['id_sirkuit' => $modelKontrol->id_sirkuit, 'pin' => $modelKontrol->pin])->exists();
                    if ($pin_disirkuit) {
                        $this->messageValidationFailed("pin di sirkuit ini sudah dipakai");
                        goto end;
                    }
                } else {
                    $pin_disirkuit = $model->getSmarthomeKontrols()->where([
                        'and',
                        ['!=', 'id', $modelKontrol->id],
                        ['id_sirkuit' => $modelKontrol->id_sirkuit, 'pin' => $modelKontrol->pin],
                    ])->exists();
                    if ($pin_disirkuit) {
                        $this->messageValidationFailed("pin di sirkuit ini sudah dipakai");
                        goto end;
                    }
                }
                $modelKontrol->ikon = str_replace("fa-", "", $modelKontrol->ikon);
                $modelKontrol->id_smarthome = $model->id;
                $modelKontrol->value = 0;
                if ($modelKontrol->validate()) :
                    $modelKontrol->save();
                    $this->messageUpdateSuccess("Kontrol");
                else :
                    $this->messageValidationFailed();
                endif;
                $redirect = true;
            endif;

            if ($modelSirkuit->load($_POST)) :
                $modelSirkuit->id_smarthome = $model->id;
                // cek kode produk
                if ($modelSirkuit->isNewRecord) {
                    $kode_produk = $modelSirkuit->kode_produk;
                    $cek_kode_produk = SmarthomeMasterProduk::find()->where(['kode_produk' => $kode_produk, 'digunakan' => 0])->active()->one();
                    if (!$cek_kode_produk) {
                        $this->messageValidationFailed("Kode produk tidak ditemukan");
                        goto end;
                    }

                    // check pairing code
                    if ($cek_kode_produk->getNotUsedPairingCode() !== $modelSirkuit->kode_pairing) {
                        $this->messageValidationFailed("Kode pairing tidak cocok");
                        goto end;
                    }

                    $cek_kode_produk->activateProduct();

                    $modelSirkuit->id_produk = $cek_kode_produk->id;
                } else {
                    if ($modelSirkuit->kode_produk != $modelSirkuit->getOldAttribute('kode_produk')) {
                        $kode_produk = $modelSirkuit->kode_produk;
                        $cek_kode_produk = SmarthomeMasterProduk::find()->where(['kode_produk' => $kode_produk, 'digunakan' => 0])->one();

                        if (!$cek_kode_produk) {
                            $this->messageValidationFailed("Kode produk tidak ditemukan");
                            goto end;
                        }

                        if ($cek_kode_produk->getActivePairingCode(false, true) === null) {
                            $this->messageValidationFailed("Tidak ada kode pairing yang aktif");
                            goto end;
                        }
                    }
                }

                if ($modelSirkuit->validate()) :
                    $modelSirkuit->save();
                    $this->messageUpdateSuccess("Sirkuit");
                else :
                    $this->messageValidationFailed();
                endif;
                $redirect = true;
            endif;

            if ($redirect) {
                return $this->redirect(['update', 'id' => $model->id]);
            }
            goto end;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }

        end:
        return $this->render('update', [
            "model" => $model,
            "modelKontrol" => $modelKontrol,
            "modelSirkuit" => $modelSirkuit,
            'dropdownPin' => $dropdownPin,
        ]);
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
            $sirkuit = $model->getSmarthomeSirkuits()->active()->all();
            foreach ($sirkuit as $s) {
                // get produk
                $produk = $s->produk;
                $produk->nonActivateProduct();
            }

            // softdelete sirkuits and kontrols
            SmarthomeSirkuit::updateAll(['flag' => 0], ['id_smarthome' => $model->id]);
            SmarthomeKontrol::updateAll(['flag' => 0], ['id_smarthome' => $model->id]);

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

    public function actionUbahdetail()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = SmarthomeKontrol::findOne($_POST['id']);
        if (!$model) {
            return ["success" => false, "message" => "Data tidak ditemukan"];
        }
        if ($_POST['value'] == "true") {
            $model->value = 1;
        } else {
            $model->value = 0;
        }
        if ($model->validate()) {
            $model->save();
            return ["success" => true, "message" => "Data berhasil diubah"];
        } else {
            return ["success" => false, "message" => "Data gagal diubah"];
        }
    }

    /**
     * Finds the Smarthome model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Smarthome the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Smarthome::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}
