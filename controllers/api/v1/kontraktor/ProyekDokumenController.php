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
class ProyekDokumenController extends \app\controllers\api\v1\kontraktor\BaseController
{
    use \app\components\UploadFile;
    public $modelClass = 'app\models\ProyekDokumen';

    const ALLOWED_ROLES = [
        \app\components\Constant::ROLE_TUKANG_SAMEDAY,
        \app\components\Constant::ROLE_KONTRAKTOR,
    ];

    public function actionType()
    {
        return $this->modelClass::TYPE_DOCUMENTS;
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
        $model = $this->findModel([
            "id" => $id,
            "flag" => 1,
        ]);

        if ($model->id_proyek != $project->id) {
            throw new HttpException(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }

        return $model;
    }


    /**
     * Creates a new $this->modelClass model.
     * If creation is successful, the browser will be redirected to the 'view' page.
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

                $instance = \yii\web\UploadedFile::getInstanceByName('pathfile');
                $ext = end(explode(".", $instance->name));
                $response = $this->uploadFile($instance, "project/documents/{$model->id_proyek}");
                if ($response->success == false) {
                    throw new HttpException(419, $response->message);
                }

                $model->pathfile = $response->filename;
                $model->nama_file .= ".{$ext}";
                $model->flag = 1;

                if ($model->validate()) :
                    $model->save();
                    return [
                        "success" => true,
                        "message" => $this->messageCreateSuccess("Dokumen"),
                    ];
                endif;

                throw new HttpException(419, \app\components\Constant::flattenError($model->errors));
            endif;
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? "Terjadi kesalahan saat menyimpan data.");
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
            'id_proyek' => $project->id,
            'id' => $id,
            "flag" => 1,
        ]);
        $old_path = $model->pathfile;
        $model->scenario = $model::SCENARIO_UPDATE;

        try {
            if ($model->load($_POST, '')) :
                $instance = \yii\web\UploadedFile::getInstanceByName('pathfile');
                if ($instance) {
                    $response = $this->uploadImage($instance, "project/documents/{$model->id_proyek}");
                    if ($response->success == false) {
                        throw new HttpException(419, $response->message);
                    }

                    $model->pathfile = $response->filename;
                    $this->deleteOne($old_path);
                } else {
                    $model->pathfile = $old_path;
                }

                if ($model->validate()) :
                    $model->save();
                    return [
                        "success" => true,
                        "message" => $this->messageUpdateSuccess("Dokumen"),
                    ];
                endif;
                throw new HttpException(419, \app\components\Constant::flattenError($model->errors));
            endif;
            throw new HttpException(400, "Data gagal disimpan.");
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? "Terjadi kesalahan saat menyimpan data.");
        }
    }

    public function actionDelete($id_project, $id)
    {
        $project = $this->hasAccessAtThisProject($id_project);
        $model = $this->findModel(
            [
                'id_proyek' => $project->id,
                'id' => $id,
                'flag' => 1,
            ]
        );
        $model->scenario = $model::SCENARIO_DELETE;

        try {
            if ($model->validate()) :
                $model->flag = 0;
                $model->deleted_at = date("Y-m-d H:i:s");
                $model->deleted_by = Yii::$app->user->id;
                $model->save();
                return [
                    "success" => true,
                    "message" => $this->messageDeleteSuccess("Dokumen"),
                ];
            endif;
            throw new HttpException(400, "Data gagal dihapus.");
        } catch (\Exception $e) {
            throw new HttpException($e->statusCode ?? 500, $e->getMessage() ?? "Terjadi kesalahan saat menghapus data.");
        }
    }
}
