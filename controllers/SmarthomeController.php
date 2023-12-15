<?php

namespace app\controllers;

use app\components\ConstantHomeis;
use app\models\Smarthome;
use app\models\SmarthomeKontrol;
use app\models\SmarthomeLog;
use app\models\SmarthomeSirkuit;
use Yii;

/**
 * This is the class for controller "SmarthomeController".
 * Modified by Defri Indra
 */
class SmarthomeController extends \app\controllers\base\SmarthomeController
{


    public function actionHapuskontrol($id)
    {
        $model = SmarthomeKontrol::findOne($id);
        if (!$model) {
            toastError("Data tidak ditemukan");
            return $this->goBack();
        }
        $id_smarthome = $model->id_smarthome;

        try {
            $model->flag = 0;
            if ($model->save()) {
                toastSuccess("Data berhasil dihapus");
            } else {
                toastError("Data gagal dihapus");
            }
            return $this->redirect(['update', 'id' => $id_smarthome]);
        } catch (\Throwable $th) {
            Yii::error($th->getMessage());
            toastError("Mohon maaf, terjadi kesalahan sistem");
        }
        return $this->redirect(['update', 'id' => $id_smarthome]);
    }

    public function actionHapussirkuit($id)
    {
        $model = SmarthomeSirkuit::findOne($id);
        if (!$model) {
            toastError("Data tidak ditemukan");
            return $this->goBack();
        }
        $id_smarthome = $model->id_smarthome;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->flag = 0;
            if ($model->save()) {
                $model->produk->deletePairingCode();
                $transaction->commit();
                toastSuccess("Data berhasil dihapus");
            } else {
                toastError("Data gagal dihapus");
            }
        } catch (\Throwable $th) {
            $transaction->rollBack();
            Yii::error($th->getMessage());
            toastError("Mohon maaf, terjadi kesalahan sistem");
        }
        return $this->redirect(['update', 'id' => $id_smarthome]);
    }

    public function actionCekPin($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = Smarthome::findOne($id);
        if (!$model) {
            return [
                'status' => false,
                'message' => "Data tidak ditemukan"
            ];
        }
        $id_sirkuit = Yii::$app->request->post('id');
        $modelSirkuit = SmarthomeSirkuit::findOne($id_sirkuit);
        if (!$modelSirkuit) {
            return [
                'status' => false,
                'message' => "Data tidak ditemukan"
            ];
        }

        $pin = ConstantHomeis::PIN;
        $pin_used = SmarthomeKontrol::find()->where(['id_smarthome' => $id, 'id_sirkuit' => $id_sirkuit])->active()->select('pin')->column();
        // remove array from key $pin_used
        foreach ($pin_used as $value) {
            unset($pin[$value]);
        }

        $data = [];
        foreach ($pin as $key => $value) {
            $data[] = [
                'id' => $key,
                'text' => $value
            ];
        }

        $selected = Yii::$app->request->post('selected');
        if ($selected) {
            $pin_selected = SmarthomeKontrol::find()->where(['id_smarthome' => $id, 'id_sirkuit' => $id_sirkuit, 'id' => $selected])->active()->select('pin')->column();

            if ($pin_selected) {
                $data[] = [
                    'id' => $pin_selected[0],
                    'text' => ConstantHomeis::PIN[$pin_selected[0]],
                    'selected' => true,
                ];
            }
        }

        // order by id
        usort($data, function ($a, $b) {
            return $a['text'] <=> $b['text'];
        });

        return [
            'success' => true,
            'data' => $data
        ];
    }

    public function actionAutorefresh($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = $this->findModel($id);

        // apex chart category hours
        // $category_hours = [];
        // for ($i = 0; $i < 24; $i++) {
        //     $category_hours[] = $i;
        // }
        $category_hours = null;
        $sirkuit_id = Yii::$app->request->get('sirkuit_id');

        $list_sirkuit = SmarthomeSirkuit::find()->select(['id', 'nama'])->where(['id_smarthome' => $model->id])->all();

        $response['sirkuit'] = SmarthomeLog::getLogPerSirkuit($list_sirkuit, $sirkuit_id);
        $response["daya"] = $model->daya;
        $response["arus"] = $model->ampere;
        $response["daya_sebelumnya"] = $model->daya_sebelumnya;
        $response["arus_sebelumnya"] = $model->ampere_sebelumnya;

        $response['graphdaya'] = $this->generateGraph($model, 'daya', $category_hours, $sirkuit_id);

        $response['graphampere'] = $this->generateGraph($model, 'ampere', $category_hours, $sirkuit_id);

        return $response;
    }
}
