<?php

namespace app\controllers;

/**
 * This is the class for controller "ProfileTukangController".
 * Modified by Defri Indra
 */
class ProfileTukangController extends \app\controllers\base\ProfileTukangController
{
    /**
     * Approve Tukang
     * @param integer $id
     * @return mixed
     */
    public function actionApprove()
    {
        $request = \Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);

        if ($model->load($request->post()) && $model->approve()) {
            \Yii::$app->session->setFlash('success', 'Berhasil menyetujui profil tukang');
            return $this->redirect(['index']);
        }

        return $this->render('approve', [
            'model' => $model,
        ]);
    }


    /**
     * Nonactive Tukang
     * @param integer $id
     * @return mixed
     */
    public function actionNonactivate()
    {
        $request = \Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);

        if ($model->nonactivate()) {
            \Yii::$app->session->setFlash('success', 'Berhasil menonaktifkan profil tukang');
        } else {
            \Yii::$app->session->setFlash('error', 'Gagal menonaktifkan profil tukang');
        }

        return $this->redirect(['index']);
    }


    /**
     * Activate Tukang
     * @param integer $id
     * @return mixed
     */
    public function actionActivate()
    {
        $request = \Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);

        if ($model->activate()) {
            \Yii::$app->session->setFlash('success', 'Berhasil mengaktifkan profil tukang');
        } else {
            \Yii::$app->session->setFlash('error', 'Gagal mengaktifkan profil tukang');
        }

        return $this->redirect(['index']);
    }
}
