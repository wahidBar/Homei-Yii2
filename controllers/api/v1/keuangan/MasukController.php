<?php

/**
 * Defri Indra Mahardika
 * ---- ----- --- -----
 **/

namespace app\controllers\api\v1\keuangan;

use app\components\Constant;
use Yii;
use yii\web\HttpException;

/**
 * ProyekKeuanganMasukController implements the CRUD actions for ProyekKeuanganMasuk model.
 **/
class MasukController extends BaseController
{
    public $modelClass = 'app\models\ProyekKeuanganMasuk';

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
     * actionView
     */
    public function actionView($id_project, $id)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $model = $this->modelClass::findOne([
            "id" => $id,
            "id_proyek" => $project->id,
            "flag" => 1,
        ]);

        return [
            "success" => true,
            "data" => $model
        ];
    }

    /**
     * actionCreate
     * action-id: keuangan/masuk/create
     * @param integer $id_project
     * @return mixed
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
                    $model->flag = 1;
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
     * actionUpdate
     * action-id: keuangan/masuk/update
     * @param integer $id_project
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id_project, $id)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $model = $this->findModel([
            "id" => $id,
            "id_proyek" => $project->id,
            "flag" => 1
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
     * actionDelete
     * action-id: keuangan/masuk/delete
     * @param integer $id_project
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id_project, $id)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $model = $this->findModel([
            "id" => $id,
            "id_proyek" => $project->id,
            "flag" => 1
        ]);
        $model->scenario = $model::SCENARIO_DELETE;

        try {
            if ($model->validate()) :
                $model->flag = 0;
                $model->deleted_at = date("Y-m-d H:i:s");
                $model->deleted_by = Yii::$app->user->id;
                $model->save();

                return [
                    "success" => true,
                    "message" => $this->messageDeleteSuccess()
                ];
            endif;
            throw new HttpException(422, $this->messageValidationFailed());
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? "Terjadi kesalahan pada server");
        }

        throw new HttpException(400, "Data tidak valid");
    }
}
