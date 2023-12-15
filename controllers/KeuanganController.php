<?php

namespace app\controllers;

use dmstr\bootstrap\Tabs;
use app\components\productive\DefaultActiveController;
use app\models\Proyek;
use yii\helpers\Url;
use yii\web\HttpException;

class KeuanganController extends DefaultActiveController
{
    /**
     * RBAC filter
     */
    public function behaviors()
    {
        return \app\models\Action::getAccess($this->id, true, "id");
    }

    public function actionView($id)
    {
        $model = Proyek::findOne($id);
        if ($model == null) throw new HttpException(404);
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember();
        Tabs::rememberActiveState();

        $total_anggaran = $model->nilai_kontrak;
        $total_pemasukkan = intval($model->getProyekKeuanganMasuks()
            ->andWhere(['is', 't_proyek_keuangan_masuk.deleted_at', null])
            ->sum('jumlah'));

        $total_pengeluaran = (intval($model->getProyekKeuanganKeluars()
            ->andWhere(['and', ['is', 't_proyek_keuangan_keluar.deleted_at', null], ['t_proyek_keuangan_keluar.tipe' => 0]])
            ->sum('total_jumlah'))
            + intval($model->getProyekKeuanganKeluars()
                ->andWhere(['and', ['is', 't_proyek_keuangan_keluar.deleted_at', null], ['t_proyek_keuangan_keluar.tipe' => 1]])
                ->sum('total_dibayarkan')));
        $sisa_anggaran = $total_pemasukkan - $total_pengeluaran;
        $total_hutang = intval($model->getProyekKeuanganKeluars()
            ->andWhere(['and', ['is', 't_proyek_keuangan_keluar.deleted_at', null], ['t_proyek_keuangan_keluar.tipe' => 1]])
            ->sum('total_jumlah')) - intval($model->getProyekKeuanganKeluars()
            ->andWhere(['and', ['is', 't_proyek_keuangan_keluar.deleted_at', null], ['t_proyek_keuangan_keluar.tipe' => 1]])
            ->sum('total_dibayarkan'));

        return $this->render(
            '/proyek/keuangan/view',
            compact('model', 'total_anggaran', 'sisa_anggaran', 'total_pemasukkan', 'total_pengeluaran', 'total_hutang')
        );
    }
}
