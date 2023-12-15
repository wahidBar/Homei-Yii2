<?php

namespace app\controllers;

use app\components\UploadFile;
use app\models\MasterSatuan;
use app\models\Proyek;
use app\models\ProyekKemajuan;
use app\models\ProyekKemajuanHarian;
use Exception;
use PHPExcel;
use PHPExcel_Cell;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Color;
use PHPExcel_Style_Fill;
use Yii;
use yii\web\HttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * This is the class for controller "ProyekKemajuanController".
 * Modified by Defri Indra
 */
class ProyekKemajuanController extends \app\controllers\base\ProyekKemajuanController
{
    use UploadFile;

    public function actionShowHistory($id)
    {
        $model = $this->findModel($id);
        $model = ProyekKemajuanHarian::find()->where(['id_proyek_kemajuan' => $model->id])->all();
        return $this->renderPartial('table-history', compact('model'));
    }

    public function actionGetParent()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Yii::$app->user->identity;
        $query = Yii::$app->request->get("q");
        $id = Yii::$app->request->get("id");
        $proyek = Proyek::findOne(['id' => $id]);

        if ($user == null || $proyek == null) return ["results" => []];

        $model = ProyekKemajuan::find()
            ->andWhere([
                'id_proyek' => $proyek->id,
            ])
            ->andWhere([
                'like',
                "item",
                "$query"
            ])
            // ->andWhere([
            //     'and',
            //     ['is', 'id_satuan', null],
            //     ['is', 'volume', null],
            //     ['is', 'bobot', null],
            // ])
            ->select("id, item as text")
            ->limit(25)
            ->asArray()
            ->all();
        return ["results" => $model];
    }

    public function actionViewChild($id)
    {
        $modelKemajuan = ProyekKemajuan::find()->andWhere(['id' => $id])->one();
        $model = Proyek::find()->andWhere(['id' => $modelKemajuan->id_proyek])->one();
        if (($modelKemajuan && $model) == false) throw new HttpException(404);
        return $this->render("view-child", compact("model", "modelKemajuan"));
    }

    public function actionExport($id)
    {
        set_time_limit(0); // infinity excecution time
        $project = Proyek::findOne($id);
        if ($project == null) throw new HttpException(404, "Data tidak ditemukan");
        $model = ProyekKemajuan::find()
            ->where(['t_proyek_kemajuan.id_proyek' => $id, 't_proyek_kemajuan.flag' => 1])
            ->andWhere(['is', 't_proyek_kemajuan.id_parent', null])
            ->select([
                '*',
                'child' => ProyekKemajuan::find()
                    ->select(['COUNT(*)'])
                    ->where('b.id_parent = t_proyek_kemajuan.id')
                    ->andWhere(['b.id_proyek' => $id, 'b.flag' => 1])
                    ->alias('b')
            ])
            ->orderBy([
                'child' => SORT_DESC,
                't_proyek_kemajuan.item' => SORT_ASC
            ])
            ->all();
        $export = new \app\components\exports\ProyekKemajuanExport($project, $model);
        set_time_limit(30); // kembalikan ke semula
        return $export->download('Report Progress - ' . date("d F Y"));
    }

    public function actionImportExcel($id)
    {
        $model = new ProyekKemajuan;
        $model->scenario = $model::SCENARIO_CREATE;

        try {
            if ($model->load($_POST)) {
                $file = UploadedFile::getInstance($model, "excel");
                if ($file) {
                    $response = $this->uploadFile($file, "progress-excel");
                    if ($response->success == false) {
                        throw new HttpException(419, "File gagal diunggah");
                    }
                    $file = $response->filename;
                    $inputFile = Yii::getAlias("uploads/$file");
                }

                $inputFileType = \PHPExcel_IOFactory::identify($inputFile);
                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFile);
                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                for ($row = 1; $row <= $highestRow; $row++) {
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                    if ($row == 1) {
                        continue;
                    }
                    $no = $rowData[0][0];
                    $item = $rowData[0][1];
                    $volume = $rowData[0][2];
                    $bobot = $rowData[0][3];
                    $satuan = $rowData[0][4];

                    if ($volume == null || $bobot == null || $satuan == null) {
                        if (ctype_alpha($no) == true) {
                            $model = new ProyekKemajuan;
                            $model->scenario = $model::SCENARIO_CREATE;
                            $model->id_proyek = $id;
                            $model->id_parent = null;
                            $model->item = $item;
                            $model->volume = 0;
                            $model->bobot = 0;
                            $model->id_satuan = 2;
                            $model->status_verifikasi = 0;
                            if ($model->validate()) {
                                $model->save();
                            } else {
                                print_r($model->getErrors());
                            }
                        } elseif (is_numeric($no) == true) {
                            $last_parent = ProyekKemajuan::find()
                                ->andWhere(['id_proyek' => $id])
                                ->andWhere(['is', 'id_parent', null])
                                ->andWhere(['volume' => 0])
                                ->andWhere(['bobot' => 0])
                                ->orderBy(['id' => SORT_DESC])
                                ->one();
                            $model = new ProyekKemajuan;
                            $model->scenario = $model::SCENARIO_CREATE;
                            $model->id_proyek = $id;
                            $model->id_parent = $last_parent->id;
                            $model->item = $item;
                            $model->volume = 0;
                            $model->bobot = 0;
                            $model->id_satuan = 2;
                            $model->status_verifikasi = 0;
                            if ($model->validate()) {
                                $model->save();
                            } else {
                                print_r($model->getErrors());
                            }
                        }
                    }

                    if ($volume != null || $bobot != null || $satuan != null) {
                        $last_subparent = ProyekKemajuan::find()
                            ->andWhere(['id_proyek' => $id])
                            ->andWhere(['not', ['id_parent' => null]])
                            ->andWhere(['volume' => 0])
                            ->andWhere(['bobot' => 0])
                            ->orderBy(['id' => SORT_DESC])
                            ->one();

                        $last_parent = ProyekKemajuan::find()
                            ->andWhere(['id_proyek' => $id])
                            ->andWhere(['is', 'id_parent', null])
                            ->andWhere(['volume' => 0])
                            ->andWhere(['bobot' => 0])
                            ->orderBy(['id' => SORT_DESC])
                            ->one();

                        $model = new ProyekKemajuan;
                        $model->scenario = $model::SCENARIO_CREATE;
                        $model->id_proyek = $id;
                        if ($last_parent->id > $last_subparent->id) {
                            $model->id_parent = $last_parent->id;
                        } else {
                            $model->id_parent = $last_subparent->id;
                        }
                        $model->item = $item;
                        $model->volume = $volume;
                        $model->bobot = $bobot;
                        $master_satuan = MasterSatuan::find()->where(['like', 'nama', $satuan])->one();
                        if ($master_satuan) {
                            $model->id_satuan = $master_satuan->id;
                        } else {
                            $newSatuan = new MasterSatuan();
                            $newSatuan->scenario = $newSatuan::SCENARIO_CREATE;
                            $newSatuan->nama = $satuan;
                            $newSatuan->keterangan = $satuan;
                            $newSatuan->jenis_satuan_id = 6;
                            $newSatuan->save();

                            $model->id_satuan = $newSatuan->id;
                        }
                        $model->status_verifikasi = 0;
                        if ($model->validate()) {
                            $model->save();
                        } else {
                            print_r($model->getErrors());
                        }
                    }
                }

                return $this->redirect(['/proyek/view', 'id' => $model->id_proyek]);
            }
        } catch (Exception $e) {
            die('Error ' . $e);
        }
        return $this->render('upload-excel', $model->render());
    }


    public function actionRdelete($id)
    {
        try {
            //code...
            $model = $this->findModel($id);
            if ($model->getChildren()->count() == 0) {
                throw new HttpException(400, "Terjadi Kesalahan");
            }
            $allChild = explode(",", $model->getAllChildren());
    
            ProyekKemajuan::updateAll(["flag" => 0, "deleted_at" => date("Y-m-d H:i:s"), "deleted_by" => Yii::$app->user->id], ["id" => $allChild]);
            $model->flag = 0;
            $model->deleted_by = Yii::$app->user->id;
            $model->deleted_at = date("Y-m-d H:i:s");
            $model->save();
    
            toastSuccess("Data berhasil dihapus");
        } catch (\Throwable $th) {
            //throw $th;
            toastError("Telah terjadi kesalahan");
        }
        return $this->redirect(['/proyek/view', 'id' => $model->id_proyek]);
    }
}
