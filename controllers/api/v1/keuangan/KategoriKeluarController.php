<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\api\v1\keuangan;

use Yii;
use yii\web\HttpException;

class KategoriKeluarController extends BaseController
{

    public $modelClass = 'app\models\MasterKategoriKeuanganKeluar';

    const ALLOWED_ROLES = [
        \app\components\Constant::ROLE_KEUANGAN,
    ];

    /**
     * actionIndex
     */
    public function actionIndex($id_project)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $query = $this->modelClass::showAtIndex($project->id);

        return $this->dataProvider($query);
    }

    /**
     * actionCreate
     * action-id: keuangan/kategori-masuk/create
     */
    public function actionCreate($id_project)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $model = new $this->modelClass;
        $model->scenario = $model::SCENARIO_CREATE;

        try {
            if ($model->load($_POST, '')) :
                $model->id_proyek = $project->id;

                if ($model->validate()) :
                    $model->save();
                    return [
                        "success" => true,
                        "message" => $this->messageCreateSuccess()
                    ];
                endif;
                throw new HttpException(422, $this->messageValidationFailed());
            endif;
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? "Terjadi kesalahan pada server");
        }

        throw new HttpException(400, "Data tidak valid");
    }


    /**
     * Updates an existing SuratBeritaAcaraPemasanganAlat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id_project, $id)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $model = $this->findModel([
            "id_proyek" => $project->id,
            "id" => $id,
            "flag" => 1,
        ]);
        $model->scenario = $model::SCENARIO_UPDATE;

        try {
            if ($model->load($_POST, '')) :
                $model->id_proyek = $project->id;
                if ($model->validate()) :
                    $model->save();

                    return [
                        "success" => true,
                        "message" => $this->messageUpdateSuccess()
                    ];
                endif;
                throw new HttpException(422, $this->messageValidationFailed());
            endif;
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? "Terjadi kesalahan pada server");
        }

        throw new HttpException(400, "Data tidak valid");
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
            $project = $this->hasAccessAtThisProject($id_project);
            $model = $this->findModel([
                "id_proyek" => $project->id,
                "id" => $id,
                "flag" => 1,
            ]);

            if ($model->flag == 1) {
                $model->flag = 0;
            } else {
                $model->flag = 1;
            }

            $model->save();
            $transaction->commit();

            return [
                "success" => true,
                "message" => $this->messageDeleteSuccess()
            ];
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? "Terjadi kesalahan pada server");
        }

        throw new HttpException(400, "Data tidak valid");
    }
}
