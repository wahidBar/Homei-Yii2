<?php

namespace app\controllers;

use app\models\Config;
use yii\web\HttpException;

/**
 * This is the class for controller "ConfigController".
 * Modified by Defri Indra
 */
class ConfigController extends \app\controllers\base\ConfigController
{

    public function actionEditValue($id)
    {
        $model = $this->findModel($id);
        $model->scenario = Config::SCENARIO_UPDATE;
        if ($model->load(\Yii::$app->request->post())) {
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', \Yii::t('app', 'Successfully saved'));
                return $this->redirect(['index']);
            }
        }
        return $this->render('edit-value', [
            'model' => $model,
        ]);
    }
}
