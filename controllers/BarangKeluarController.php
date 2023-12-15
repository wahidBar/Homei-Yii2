<?php

namespace app\controllers;

use app\components\annex\Tabs;
use app\models\Action;
use app\models\IsianLanjutan;
use app\models\search\SupplierOrderBarangKeluarSearch;
use app\models\search\SupplierOrderSearch;
use app\models\SupplierBoqProyek;
use app\models\SupplierOrder;
use app\models\SupplierOrderDetail;
use app\models\SupplierPengiriman;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * This is the class for controller "SupplierOrderController".
 * Modified by Defri Indra
 */
class BarangKeluarController extends \app\components\productive\DefaultActiveController
{

    public function actionIndex()
    {
        // $query = new Query();
        // $query->select([
        //     't_supplier_order.user_id',
        //     't_supplier_order.status',
        //     't_supplier_order_detail.supplier_barang_id',
        //     't_supplier_order_detail.jumlah',
        // ])
        //     ->from('t_supplier_order')
        //     ->join(
        //         'LEFT JOIN',
        //         't_supplier_order_detail',
        //         't_supplier_order_detail.supplier_order_id = t_supplier_order.id'
        //     )
        //     ->andWhere(['not', ['t_supplier_order_detail.jumlah' => null]])
        //     ->andWhere(['t_supplier_order.status' => 2]);
        // $command = $query->createCommand();
        // $count = $command->execute();
        // $models = $command->queryAll();

        $searchModel  = new SupplierOrderBarangKeluarSearch();
        $dataProvider = $searchModel->search($_GET);

        Tabs::clearLocalStorage();

        Url::remember();
        \Yii::$app->session['__crudReturnUrl'] = null;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionExport()
    {
        $start = $_GET['start'];
        $end = $_GET['end'];
        if ($start != null && $end != null) {
            $query = SupplierOrderDetail::find();
            $query->joinWith(['supplierOrder']);
            $query->andWhere(['not', ['jumlah' => null]])
                ->andWhere(['t_supplier_order.status' => [2, 4]])
                ->andWhere(['between', 'date(t_supplier_order_detail.created_at)', $start, $end]);
            $models = $query->all();
            $export = new \app\components\exports\BarangKeluarExport($models);
            return $export->download('Report Barang Keluar - ' . $start . ' - ' . $end);
        } else {
            // $models = SupplierOrder::find()
            //     ->joinWith(['supplierOrderDetails'])
            //     ->andWhere(['not', ['t_supplier_order_detail.jumlah' => null]])
            //     ->andWhere(['status' => [2, 4]])
            //     ->all();

            $query = SupplierOrderDetail::find();
            $query->joinWith(['supplierOrder']);
            $query->andWhere(['not', ['jumlah' => null]])
                ->andWhere(['t_supplier_order.status' => [2, 4]]);
            $models = $query->all();
            $export = new \app\components\exports\BarangKeluarExport($models);
            return $export->download('Report Barang Keluar - ' . date("d F Y"));
        }
    }
}
