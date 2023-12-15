<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\api\v1\kontraktor;

use yii\web\HttpException;
use Yii;

/**
 * ProyekCctvController implements the CRUD actions for ProyekCctv model.
 **/
class ProyekCctvController extends \app\controllers\api\v1\kontraktor\BaseController
{
    public $modelClass = 'app\models\ProyekCctv';

    const ALLOWED_ROLES = [
        \app\components\Constant::ROLE_TUKANG_SAMEDAY,
        \app\components\Constant::ROLE_KONTRAKTOR,
    ];
    /**
     * actionIndex
     */
    public function actionTipe()
    {
        $template = [];
        foreach ($this->modelClass::getTipes() as $key => $value) {
            $template[] = [
                'id' => $key,
                'name' => $value,
            ];
        }

        return [
            "success" => true,
            "data" => $template,
        ];
    }

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
     * actionView
     */
    public function actionView($id_project, $id)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $model = $this->findModel($id);

        if ($model->id_proyek != $project->id) {
            throw new HttpException(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }

        return $model;
    }

    /**
     * actionCreate
     * action-id: api/v1/kontraktor/proyek-cctv/create
     */
    public function actionCreate($id_project)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $model = new $this->modelClass();
        $model->scenario = $model::SCENARIO_CREATE;

        try {
            if ($model->load($_POST, '')) :
                $model->id_proyek = $project->id;
                if ($model->validate()) :
                    $model->save();
                    $this->messageCreateSuccess();

                    return [
                        "success" => true,
                        "message" => $this->messageCreateSuccess("Cctv"),
                    ];
                endif;
                throw new HttpException(422, $this->messageValidationError($model->errors));
            endif;
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? 'Internal Server Error');
        }

        throw new HttpException(400, "Data gagal disimpan.");
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
            "id" => $id,
            "id_proyek" => $project->id
        ]);
        $model->scenario = $model::SCENARIO_UPDATE;

        try {
            if ($model->load($_POST, '')) :
                $model->id_proyek = $project->id;
                if ($model->validate()) :
                    $model->save();
                    $this->messageUpdateSuccess();
                    return [
                        "success" => true,
                        "message" => $this->messageUpdateSuccess("Cctv"),
                    ];
                endif;
                $this->messageValidationFailed();
            endif;
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? 'Internal Server Error');
        }

        throw new HttpException(400, 'Gagal mengubah data.');
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
                "id" => $id,
                "id_proyek" => $project->id,
                'flag' => 1
            ]);

            $model->scenario = $model::SCENARIO_DELETE;
            $model->flag = 0;
            $model->deleted_at = date("Y-m-d H:i:s");
            $model->deleted_by = Yii::$app->user->id;
            $model->save();

            $transaction->commit();
            return [
                "status" => "success",
                "message" => "Data berhasil dihapus",
            ];
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? 'Internal Server Error');
        }

        throw new HttpException(400, 'Invalid request. Please do not repeat this request again.');
    }
}
