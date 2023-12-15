<?php

namespace app\controllers\home;

use app\components\annex\Tabs;
use app\components\Constant;
use app\components\UploadFile;
use app\models\IsianLanjutan;
use app\models\MasterPembayaran;
use app\models\Model;
use app\models\search\SupplierOrderSearch;
use app\models\SiteSetting;
use app\models\SupplierBarang;
use app\models\SupplierMaterial;
use app\models\SupplierOrder;
use app\models\SupplierOrderCart;
use app\models\SupplierOrderDetail;
use app\models\SupplierPengiriman;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;

/**
 * This is the class sc controller "BeritaController".
 */
class BahanMaterialController extends BaseController
{
    use UploadFile;

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        $this->layout = '@app/views/layouts-home/main';
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'sub-material', 'detail-pesanan', 'daftar-barang', 'view', 'hitung', 'check-valid'],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            'add-to-cart',
                            'keranjang',
                            'hapus-item',
                            'checkout',
                            'pembayaran',
                            'input-data-boq',
                            'cek-min-pesanan',
                            'daftar-pesanan',
                            'detail-boq',
                            'increment-product',
                            'decrement-product',
                            'update-product',
                            'hitung',
                            'proses-pengiriman',
                            'insert-spk',
                            'cetak-invoice',
                            'pesanan-diterima',
                            // 'tes',
                            // 'ajax-remove-item',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],

            ],

        ];
    }

    public function actionCheckValid($id)
    {
        $model = SupplierOrder::findOne(['kode_unik' => $id]);
        return $this->render('check-valid', ["model" => $model]);
    }

    public function actionIndex()
    {
        $this->view->title = 'Homei - Bahan Material';
        $materials = SupplierMaterial::find()->all();
        $query_cart = SupplierOrderCart::find()
            ->where(['user_id' => \Yii::$app->user->identity->id])
            ->andWhere(['flag' => 1]);
        $carts = $query_cart
            ->orderBy(['id' => SORT_DESC])
            ->limit(5)
            ->all();
        $jumlah_carts = $query_cart->count();
        $subtotal_cart = $query_cart->sum('subtotal');
        $query = SupplierBarang::find()->where(['status' => 1]);

        if ($search = Yii::$app->request->get('q')) {
            $query
                ->andWhere(
                    [
                        'like',
                        'nama_barang',
                        $search
                    ]
                );
        }

        if ($material_id = Yii::$app->request->get('material_filter')) {
            $material_id = intval($material_id);
            $query
                ->andWhere(['material_id' => $material_id]);
        }

        if ($submaterial_id = Yii::$app->request->get('submaterial_filter')) {
            $submaterial_id = intval($submaterial_id);
            $query
                ->andWhere(['submaterial_id' => $submaterial_id]);
        }

        if ($sort_id = Yii::$app->request->get('sort')) {
            $sort_id = intval($sort_id);
            if ($sort_id == 1) {
                $query->orderBy([
                    'harga_proyek' => SORT_ASC,
                ]);
            } else if ($sort_id == 2) {
                $query->orderBy([
                    'harga_proyek' => SORT_DESC,
                ]);
            } else if ($sort_id == 3) {
                $query->orderBy([
                    'created_at' => SORT_ASC,
                ]);
            } else {
                $query->orderBy([
                    'created_at' => SORT_DESC,
                ]);
            }
        }



        $response = Model::pagination($query);
        $summary = Constant::getPaginationSummary($response->_pagination, $response->count);



        return $this->render('index', [
            'response' => $response,
            'materials' => $materials,
            'carts' => $carts,
            'jumlah_carts' => $jumlah_carts,
            'subtotal_cart' => $subtotal_cart,
            'summary' => $summary,
        ]);
    }

    public function actionView($id)
    {
        $barang = SupplierBarang::find()
            ->where(['slug' => $id])
            ->one();

        if ($barang == null) throw new \yii\web\HttpException(404, "Barang tidak ditemukan");
        $this->view->title = 'Homei - ' . $barang->nama_barang;

        $barang_terkaits = SupplierBarang::find()
            ->where(['submaterial_id' => $barang->submaterial_id])
            ->andWhere(['status' => 1])
            ->limit(4)
            ->all();
        $carts = SupplierOrderCart::find()
            ->where(['user_id' => \Yii::$app->user->identity->id])
            ->orderBy(['id' => SORT_DESC])
            ->limit(5)
            ->all();
        $jumlah_carts = SupplierOrderCart::find()
            ->where(['user_id' => \Yii::$app->user->identity->id])
            ->andWhere(['flag' => 1])
            ->count();

        $subtotal_cart = SupplierOrderCart::find()
            ->where(['user_id' => \Yii::$app->user->identity->id])
            ->andWhere(['flag' => 1])
            ->sum('subtotal');


        if ($user = \Yii::$app->user->identity) {
            $model = SupplierOrderCart::find()
                ->where(['user_id' => $user->id])
                ->andWhere(['supplier_barang_id' => $barang->id])
                ->one();
        } else {
            $model = new SupplierOrderCart;
            $model->scenario = $model::SCENARIO_CREATE;
        }

        try {
            if ($model->load($_POST)) :
                if (\Yii::$app->user->identity == null) {
                    toastError("Silahkan login terlebih dahulu");
                    return $this->redirect(['site/login']);
                }

                $barang = SupplierBarang::find()->where(['slug' => $id])->one();

                if ($barang->stok < 1) {
                    toastError("Stok kosong");
                    goto end;
                }

                if ($barang->stok < $model->jumlah) {
                    toastError("Stok barang $barang->nama_barang kurang. <br> Stok tersisa : $barang->stok");
                    goto end;
                }

                $model->supplier_id = $barang->supplier_id;
                $model->kode_unik = Yii::$app->security->generateRandomString(30);
                $model->supplier_barang_id = $barang->id;
                $model->user_id = \Yii::$app->user->identity->id;
                // $model->layanan_supplier = $model::LAYANAN_RITEL;
                $model->subtotal = $model->jumlah * $barang->harga_ritel;

                // $cek_double_item = SupplierOrderCart::find()
                //     ->where(['supplier_barang_id' => $barang->id])
                //     ->andWhere(['user_id' => \Yii::$app->user->identity->id])
                //     // ->andWhere(['layanan_supplier' => $model::LAYANAN_RITEL])
                //     ->one();
                // if ($cek_double_item != null) {
                //     toastError("Barang telah ada di keranjang");
                //     goto end;
                // }
                if ($model->validate()) :
                    $model->save();
                    toastSuccess("Barang telah dimasukkan ke keranjang");
                    return $this->redirect(['view', 'id' => $id]);
                endif;
                toastError("Data tidak berhasil disimpan : " . Constant::flattenError($model->getErrors()));
                goto end;
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }
        end:
        return $this->render('view', [
            'barang' => $barang,
            'barang_terkaits' => $barang_terkaits,
            'carts' => $carts,
            'jumlah_carts' => $jumlah_carts,
            'subtotal_cart' => $subtotal_cart,
            'model' => $model,
        ]);
    }

    public function actionAddToCart($id)
    {
        $parameter_barang = $_GET['barang'];
        $model = new SupplierOrderCart;
        $model->scenario = $model::SCENARIO_CREATE;
        $barang = SupplierBarang::find()->where(['slug' => $id])->one();
        if ($barang->stok < 1) {
            toastError("Stok kosong");
            if ($parameter_barang == null) :
                return $this->redirect(['index']);
            else :
                return $this->redirect(['view', 'id' => $id]);
            endif;
        }
        if ($barang->status != 1) {
            toastError("Barang tidak dapat diproses");
            if ($parameter_barang == null) :
                return $this->redirect(['index']);
            else :
                return $this->redirect(['view', 'id' => $id]);
            endif;
        }

        $model->material_id = $barang->material_id;
        $model->submaterial_id = $barang->submaterial_id;
        $model->supplier_id = $barang->supplier_id;
        $model->kode_unik = Yii::$app->security->generateRandomString(30);
        $model->supplier_barang_id = $barang->id;
        $model->user_id = \Yii::$app->user->identity->id;
        $model->jumlah = 1;
        $model->harga_satuan = $barang->harga_ritel;
        $model->subtotal = $model->jumlah * $barang->harga_ritel;

        $model->valid_spk = 0; // bypass bonus

        $cek_double_item = SupplierOrderCart::find()
            ->where(['supplier_barang_id' => $barang->id])
            ->andWhere(['user_id' => \Yii::$app->user->identity->id])
            ->one();

        if ($cek_double_item != null) {
            toastError("Barang telah ada di keranjang");
            if ($parameter_barang == null) :
                return $this->redirect(['index']);
            else :
                return $this->redirect(['view', 'id' => $id]);
            endif;
        }

        if ($model->validate()) :
            $model->save();
            toastSuccess("Barang telah dimasukkan ke keranjang");
            if ($parameter_barang == null) :
                return $this->redirect(['index']);
            else :
                return $this->redirect(['view', 'id' => $id]);
            endif;
        else :
            toastError("Data tidak berhasil disimpan");
            if ($parameter_barang == null) :
                return $this->redirect(['index']);
            else :
                return $this->redirect(['view', 'id' => $id]);
            endif;
        endif;
    }

    public function actionKeranjang()
    {
        $this->view->title = 'Homei - Keranjang';
        $cek_boq = IsianLanjutan::find()
            ->where(['status_boq' => 1])
            ->andWhere(['is_beli_material' => 1])
            ->andWhere(['id_user' => \Yii::$app->user->identity->id])
            ->one();
        $query = SupplierOrderCart::find()
            ->where(['user_id' => \Yii::$app->user->identity->id])
            ->andWhere(['flag' => 1]);
        $models = $query->all();
        $jumlah_carts = $query->count();
        $total_cart = $query->sum('subtotal');
        $posts = $_POST['SupplierOrderCart'];

        if ($posts) :
            try {
                if ($cek_boq == null) {
                    foreach ($posts as $post) :
                        $model = SupplierOrderCart::find()
                            ->where(['kode_unik' => $post['kode_unik']])
                            ->andWhere(['user_id' => \Yii::$app->user->identity->id])
                            ->one();
                        $max_beli_vol = $model->supplierBarang->minimal_beli_volume - 1;
                        $max_beli_satuan = $model->supplierBarang->minimal_beli_satuan - 1;
                        if ($post['volume'] > $max_beli_vol) :
                            toastError($model->supplierBarang->nama_barang . " melebihi batas maksimal jumlah pesanan retail");
                            return $this->redirect(['keranjang']);
                        endif;
                        if ($post['jumlah'] > $max_beli_satuan) :
                            toastError($model->supplierBarang->nama_barang . " melebihi batas maksimal jumlah pesanan retail");
                            return $this->redirect(['keranjang']);
                        endif;
                    endforeach;
                }
                foreach ($posts as $post) :
                    $model = SupplierOrderCart::find()
                        ->where(['kode_unik' => $post['kode_unik']])
                        ->andWhere(['user_id' => \Yii::$app->user->identity->id])
                        ->one();
                    $model->scenario = $model::SCENARIO_UPDATE_CART;

                    if ($post['jumlah']) :
                        $jumlah = $post['jumlah'];
                        if ($jumlah < $model->supplierBarang->minimal_beli_satuan) :
                            $harga_perbiji = $model->supplierBarang->getHargaPerbiji();
                            if ($harga_perbiji == -1) toastError("Ukuran barang belum diatur");
                            $subtotal = $harga_perbiji * $post['jumlah'];
                            $model->jumlah = floor($jumlah);
                            $kurangi_stok = $model->supplierBarang->stok - $model->jumlah;

                        else :
                            $harga_perbiji = $model->supplierBarang->getHargaPerbijiProyek();
                            if ($harga_perbiji == -1) toastError("Ukuran barang belum diatur");
                            $subtotal = $harga_perbiji * $post['jumlah'];
                            $model->jumlah = floor($jumlah);
                            $kurangi_stok = $model->supplierBarang->stok - $model->jumlah;
                        endif;
                    else :
                        $model->jumlah = null;
                    endif;

                    if ($post['volume']) :
                        $volume = $post['volume'];
                        if ($volume < $model->supplierBarang->minimal_beli_volume) :
                            $harga_barang = $model->supplierBarang->harga_ritel;
                            $subtotal = $volume * $harga_barang;
                            $volume = $post['volume'] * $model->supplierBarang->getJumlahPervolume();
                            $model->volume = $post['volume'];
                        else :
                            $harga_barang = $model->supplierBarang->harga_proyek;
                            $subtotal = $volume * $harga_barang;
                            $volume = $post['volume'] * $model->supplierBarang->getJumlahPervolumeProyek();
                            $model->volume = $post['volume'];
                        endif;
                    else :
                        $model->volume = null;
                    endif;

                    $model->subtotal = $subtotal;
                    if ($model->validate()) :
                        $model->save();
                        toastSuccess("Data Berhasil di Update");
                    else :
                        toastError("Validasi gagal");
                    endif;
                endforeach;
            } catch (\Throwable $th) {
                toastError($th->getMessage());
            }
            return $this->redirect(['keranjang']);
        endif;

        end:
        return $this->render('keranjang', [
            'models' => $models,
            'jumlah_carts' => $jumlah_carts,
            'total_cart' => $total_cart,
            'kode_boq' => $cek_boq->kode_unik,
            'proyek' => $cek_boq->label,
        ]);
    }

    public function actionHapusItem($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $model = SupplierOrderCart::find()
            ->where(['kode_unik' => $id])
            ->andWhere(['user_id' => \Yii::$app->user->identity->id])
            ->one();
        if ($model == null) {
            toastError("Barang tidak ditemukan didalam keranjang");
            return $this->redirect(['keranjang']);
        }

        try {
            $model->delete();
            $transaction->commit();
            toastSuccess("Data berhasil dihapus");
        } catch (\Exception $e) {
            $transaction->rollBack();
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            \Yii::$app->getSession()
                ->addFlash('error', $msg);
            return $this->redirect(['keranjang']);
        }

        // TODO: improve detection
        $isPivot = strstr('$id', ',');
        if ($isPivot == true) :
            return $this->redirect(['keranjang']);
        elseif (isset(\Yii::$app->session['__crudReturnUrl']) && \Yii::$app->session['__crudReturnUrl'] != '/') :
            Url::remember(null);
            $url = \Yii::$app->session['__crudReturnUrl'];
            \Yii::$app->session['__crudReturnUrl'] = null;

            return $this->redirect(['keranjang']);
        elseif (\Yii::$app->request->get('remember_url')) :
            return $this->redirect(\Yii::$app->request->get('remember_url'));
        else :
            return $this->redirect(['keranjang']);
        endif;
    }

    public function actionCheckout()
    {
        $user = Constant::getUser();

        if ($_POST['alamat_pengiriman'] == null) {
            toastError("Alamat Kosong");
            return $this->redirect(['keranjang']);
        }
        $carts = SupplierOrderCart::find()
            ->where(['user_id' => \Yii::$app->user->identity->id])
            ->all();
        $total_cart = SupplierOrderCart::find()
            ->where(['user_id' => \Yii::$app->user->identity->id])
            ->andWhere(['flag' => 1])
            ->sum('subtotal');

        // $cek_boq = IsianLanjutan::find()
        //     ->where(['status_boq' => 1])
        //     ->andWhere(['is_beli_material' => 1])
        //     ->andWhere(['id_user' => \Yii::$app->user->identity->id])->one();
        if ($carts == null) {
            toastError("Keranjang Kosong");
            return $this->redirect(['keranjang']);
        }

        $supp_order = new SupplierOrder();
        $pengaturan = SiteSetting::find()->one();
        $supp_order->scenario = $supp_order::SCENARIO_CREATE;

        $supp_order->user_id = \Yii::$app->user->identity->id;
        // if ($cek_boq != null) {
        //     $supp_order->id_isian_lanjutan = $cek_boq->id;
        //     $supp_order->kode_isian_lanjutan = $cek_boq->kode_unik;
        // }

        $supp_order->kode_unik = Yii::$app->security->generateRandomString(30);
        $supp_order->no_nota = 'INVHOMEI-' . date('YmdH') . Yii::$app->security->generateRandomString(5);
        $supp_order->status = 0;
        $supp_order->deadline_bayar = date("Y-m-d H:i:s", strtotime("+$pengaturan->batas_pembayaran minutes"));
        $supp_order->nama_penerima = $_POST['nama_penerima'] ? $_POST['nama_penerima'] : $user->username;
        $supp_order->alamat = $_POST['alamat_pengiriman'];
        $supp_order->latitude = $_POST['latitude'];
        $supp_order->longitude = $_POST['longitude'];
        $supp_order->catatan = $_POST['catatan'];
        $supp_order->total_harga = $total_cart;

        if ($supp_order->validate()) :
            $supp_order->save();

            \app\components\Notif::log(
                null,
                \Yii::$app->user->identity->name . " telah melakukan pemesanan",
                "Hallo Admin," . \Yii::$app->user->identity->name . " telah melakukan pemesanan material. Mohon cek transaksi",
                [
                    "controller" => "supplier-order/view",
                    "android_route" => null,
                    "params" => [
                        "id" => $supp_order->id
                    ]
                ]
            );
        // dd($supp_order->id);
        else :
            toastError("Order tidak berhasil disimpan");
            return $this->redirect(['keranjang']);
        endif;

        // if ($cek_boq != null) {
        //     $cek_boq->scenario = $cek_boq::SCENARIO_STATUS_BOQ;
        //     $cek_boq->status_boq = 0;
        //     if ($cek_boq->validate()) :
        //         $cek_boq->save();
        //     else :
        //         toastError("Order tidak berhasil disimpan");
        //         return $this->redirect(['keranjang']);
        //     endif;
        // }

        foreach ($carts as $cart) {

            $supplier = SupplierOrder::find()->where(['kode_unik' => $supp_order->kode_unik])->one();
            $order_detail = new SupplierOrderDetail();
            $order_detail->scenario = $order_detail::SCENARIO_CREATE;

            $order_detail->kode_unik = Yii::$app->security->generateRandomString(30);
            $order_detail->supplier_order_id = $supplier->id;
            $order_detail->kode_order = $supplier->kode_unik;
            $order_detail->supplier_barang_id = $cart->supplier_barang_id;
            $order_detail->jumlah = $cart->jumlah;
            $order_detail->volume = $cart->volume;
            $order_detail->catatan = null;
            $order_detail->total_ppn = 0;
            $order_detail->voucher = null;
            $order_detail->no_spk = $cart->no_spk;
            $order_detail->keterangan_proyek = $cart->keterangan_proyek;
            $order_detail->valid_spk = $cart->valid_spk;
            $order_detail->subtotal = $cart->subtotal;
            $order_detail->harga_satuan = $cart->harga_satuan;

            if ($order_detail->validate()) :
                $order_detail->save();
            endif;

            $hapus_cart = SupplierOrderCart::findOne($cart->id);
            $hapus_cart->scenario = $hapus_cart::SCENARIO_HAPUS_CART;
            $hapus_cart->deleted_at = date('d-m-y H:i:s');
            $hapus_cart->deleted_by = \Yii::$app->user->identity->id;
            $hapus_cart->flag = 0;

            if ($hapus_cart->validate()) :
                $hapus_cart->save();
            endif;
        }

        toastSuccess("Checkout");
        return $this->redirect(['pembayaran', 'id' => $supp_order->kode_unik]);
    }

    public function actionPembayaran($id)
    {
        date_default_timezone_set("Asia/Jakarta");
        $this->view->title = 'Homei - Pembayaran';
        $order = SupplierOrder::find()->where(['kode_unik' => $id])
            ->andWhere(['user_id' => \Yii::$app->user->identity->id])->one();
        $daftar_barangs = SupplierOrderDetail::find()->where(['supplier_order_id' => $order->id])
            ->andWhere(['created_by' => \Yii::$app->user->identity->id])->all();
        $pengaturan = SiteSetting::find()->one();
        $pembayarans = MasterPembayaran::find()->where(['status' => 1])->all();

        if ($order == null) throw new HttpException(404);
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();

        if ($order->status != 3) {
            $order->scenario = $order::SCENARIO_BAYAR;
        } else {
            $order->scenario = $order::SCENARIO_BAYARULANG;
        }
        $oldbukti = $order->bukti_bayar;

        try {
            if ($order->load($_POST)) :
                $to_time = strtotime($order->deadline_bayar);
                $from_time = strtotime(date('Y-m-d H:i:s'));
                $minute = round(abs($to_time - $from_time) / 60, 2);

                if ($minute > $pengaturan->batas_pembayaran) {
                    toastError("Order Telah Kadaluarsa. Silahkan Order kembali.");
                    return $this->redirect(['pembayaran', 'id' => $id]);
                }

                if ($order->status == 1 || $order->status == 2) {
                    \Yii::$app->getSession()->setFlash(
                        'error',
                        'DP Telah Dibayar'
                    );
                    return $this->redirect(['pembayaran', 'id' => $id]);
                }

                if ($order->status == 3) {
                    unlink(Yii::getAlias("@app/web/uploads/") . $oldbukti);
                }
                $instance = UploadedFile::getInstance($order, 'bukti_bayar');
                $ext = end(explode(".", $instance->name));
                $response = $this->uploadFile($instance, $order->relativeUploadPath());
                if ($response->success == false) {
                    toastError("Dokumen gagal diunggah");
                    goto end;
                }
                $order->bukti_bayar = $response->filename;
                $order->tanggal_bayar = date('Y-m-d H:i:s');
                // $oldtotal = $order->total_bayar;
                // $order->total_bayar = $oldtotal + $order->nilai_dp;
                $order->status = 1;
                $order->alasan_tolak = null;

                if ($order->validate()) :

                    \app\components\Notif::log(
                        null,
                        \Yii::$app->user->identity->name . " telah membayar pemesanan",
                        "Hallo Admin," . \Yii::$app->user->identity->name . " telah membayar pemesanan material. Silahkan cek transaksi",
                        [
                            "controller" => "supplier-order/view",
                            "android_route" => null,
                            "params" => [
                                "id" => $order->id,
                            ]
                        ]
                    );

                    $order->save();
                    \Yii::$app->getSession()->setFlash(
                        'success',
                        'Data Tersimpan'
                    );
                    return $this->redirect(['pembayaran', 'id' => $id]);
                endif;
                \Yii::$app->getSession()->setFlash(
                    'error',
                    'Terjadi Kesalahan'
                );
            elseif (!\Yii::$app->request->isPost) :
                $order->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $order->addError('_exception', $msg);
        }

        end:
        return $this->render('pembayaran', [
            'order' => $order,
            'daftar_barangs' => $daftar_barangs,
            'pembayarans' => $pembayarans
        ]);
    }

    public function actionDaftarPesanan()
    {
        $this->view->title = 'Homei - Pembayaran';
        $searchModel  = new SupplierOrderSearch();
        $dataProvider = $searchModel->search($_GET);

        Tabs::clearLocalStorage();

        Url::remember();
        \Yii::$app->session['__crudReturnUrl'] = null;

        return $this->render('daftar-pesanan', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionDetailPesanan($id)
    {
        $model = SupplierOrder::find()->where(['kode_unik' => $id])->one();
        // if ($model == null) return "Tidak ditemukan";
        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        return $this->renderPartial('_view_detail_2', compact('model'));
    }

    public function actionCetakInvoice($id)
    {
        // get your HTML raw content without any layouts or scripts
        $model = SupplierOrder::find()->where(['kode_unik' => $id])->andWhere(['user_id' => \Yii::$app->user->identity->id])->one();
        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        $orders = SupplierOrderDetail::find()->where(['supplier_order_id' => $model->id])->all();
        $user = User::findOne($model->user_id);
        $setting = SiteSetting::find()->one();


        $content = $this->renderPartial('pdf/_reportView', [
            'model' => $model,
            'orders' => $orders,
            'setting' => $setting,
            'user' => $user
        ]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            'marginHeader' => 0,
            'marginFooter' => 1,
            'marginTop' => 5,
            'marginBottom' => 5,
            'marginLeft' => 5,
            'marginRight' => 3,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => [
                'homepage/css/invoice.css',
                'homepage/vendor/bootstrap4/bootstrap.min.css',
                'homepage/css/style.css',
                'homepage/css/style-print.css',
            ],
            // any css to be embedded if required
            // 'cssInline' => '.kv-heading-1{font-size:18px;}',
            // set mPDF properties on the fly
            //  'options' => ['title' => 'Krajee Report Title'],
            'options' => [
                'defaultheaderline' => 0,  //for header
                'defaultfooterline' => 0,  //for footer
            ],
            // call mPDF methods on the fly
            // 'methods' => [

            //     'SetHeader' => $this->renderPartial('pdf/header'),
            //     'SetFooter' => $this->renderPartial('pdf/footer'),
            // ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    public function actionDetailBoq($id)
    {
        $model = IsianLanjutan::find()->where(['kode_unik' => $id])->one();
        if ($model == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        return $this->renderPartial('_view_detail_3', compact('model'));
    }

    // public function actionTes()
    // {
    //     return $this->render('pdf/tes', [
    //     ]);
    // }

    public function actionProsesPengiriman($id)
    {
        $this->view->title = 'Homei - Proses Pengiriman';
        $order = SupplierOrder::find()->where(['kode_unik' => $id])->one();
        if ($order == null) {
            throw new HttpException(404, "Data tidak ditemukan");
        }
        $models = SupplierPengiriman::find()->where(['kode_supplier_order' => $id])->all();

        return $this->render('proses-pengiriman', [
            'order' => $order,
            'models' => $models
        ]);
    }

    public function actionIncrementProduct($uniq)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Constant::getUser();
        $cart = SupplierOrderCart::findOne([
            "user_id" => $user->id,
            "kode_unik" => $uniq
        ]);

        if ($cart) {
            $cart->jumlah += 1;
            if ($cart->jumlah >= $cart->supplierBarang->minimal_beli_satuan && $cart->valid_spk == 1) $harga = $cart->supplierBarang->harga_proyek;
            else  $harga = $cart->supplierBarang->harga_ritel;
            $cart->harga_satuan = $harga;
            $cart->subtotal = $cart->jumlah * $harga;
            $cart->save();
            $sumtotal = SupplierOrderCart::find()->where(['user_id' => $user->id])->andWhere(['flag' => 1])->sum('subtotal');
            return [
                "success" => true,
                "data" => array_merge([
                    "subtotal" => \app\components\Angka::toReadableHarga($cart->subtotal, false),
                    "jumlah" => $cart->jumlah,
                    "sumtotal" => \app\components\Angka::toReadableHarga($sumtotal, false),
                ], $cart->checkTombolSpk() ? ['showbtn' => true] : []),
                "message" => "Berhasil menambahkan stok",
            ];
        }

        return [
            "success" => false,
            "message" => "Item tidak ditemukan dalam keranjang",
        ];
    }

    public function actionDecrementProduct($uniq)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Constant::getUser();
        $cart = SupplierOrderCart::findOne([
            "user_id" => $user->id,
            "kode_unik" => $uniq
        ]);

        if ($cart) {
            $cart->jumlah -= 1;
            if ($cart->jumlah == 0) {
                $cart->delete();
                $sumtotal = SupplierOrderCart::find()->where(['user_id' => $user->id])->andWhere(['flag' => 1])->sum('subtotal');
                return [
                    "success" => true,
                    "data" => [
                        "deleted" => true,
                        "sumtotal" => \app\components\Angka::toReadableHarga($sumtotal, false),
                    ],
                    "message" => "Item berhasil dihapus dari keranjang"
                ];
            }
            if ($cart->jumlah >= $cart->supplierBarang->minimal_beli_satuan && $cart->valid_spk == 1) $harga = $cart->supplierBarang->harga_proyek;
            else  $harga = $cart->supplierBarang->harga_ritel;
            $cart->harga_satuan = $harga;
            $cart->subtotal = $cart->jumlah * $harga;
            $cart->save();
            $sumtotal = SupplierOrderCart::find()->where(['user_id' => $user->id])->andWhere(['flag' => 1])->sum('subtotal');
            return [
                "success" => true,
                "data" => array_merge([
                    "subtotal" => \app\components\Angka::toReadableHarga($cart->subtotal, false),
                    "jumlah" => $cart->jumlah,
                    "sumtotal" => \app\components\Angka::toReadableHarga($sumtotal, false),
                ], $cart->checkTombolSpk() ? ['showbtn' => true] : []),
                "message" => "Berhasil menambahkan stok",
            ];
        }

        return [
            "success" => false,
            "message" => "Item tidak ditemukan dalam keranjang",
        ];
    }



    public function actionUpdateProduct($uniq)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Constant::getUser();
        $cart = SupplierOrderCart::findOne([
            "user_id" => $user->id,
            "kode_unik" => $uniq
        ]);

        if ($cart) {
            $cart->jumlah = floatval($_GET['jumlah']);
            if ($cart->jumlah <= 0) {
                $cart->delete();
                $sumtotal = SupplierOrderCart::find()->where(['user_id' => $user->id])->andWhere(['flag' => 1])->sum('subtotal');
                return [
                    "success" => true,
                    "data" => [
                        "deleted" => true,
                        "sumtotal" => \app\components\Angka::toReadableHarga($sumtotal, false),
                    ],
                    "message" => "Item berhasil dihapus dari keranjang"
                ];
            }
            if ($cart->jumlah >= $cart->supplierBarang->minimal_beli_satuan && $cart->valid_spk == 1) $harga = $cart->supplierBarang->harga_proyek;
            else  $harga = $cart->supplierBarang->harga_ritel;
            $cart->harga_satuan = $harga;
            $cart->subtotal = $cart->jumlah * $harga;
            $cart->save();
            $sumtotal = SupplierOrderCart::find()->where(['user_id' => $user->id])->andWhere(['flag' => 1])->sum('subtotal');
            return [
                "success" => true,
                "data" => array_merge([
                    "subtotal" => \app\components\Angka::toReadableHarga($cart->subtotal, false),
                    "jumlah" => $cart->jumlah,
                    "sumtotal" => \app\components\Angka::toReadableHarga($sumtotal, false),
                ], $cart->checkTombolSpk() ? ['showbtn' => true] : []),
                "message" => "Berhasil menambahkan stok",
            ];
        }

        return [
            "success" => false,
            "message" => "Item tidak ditemukan dalam keranjang",
        ];
    }

    /**
     * actionHitung
     * Di gunakan untuk menghitung kebutuhan yang diperlukan oleh user
     * Aksi ini berada pada detail bahan material
     * @param integer $id
     * @return exception|string
     */
    function actionHitung($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = SupplierBarang::findOne(["id" => $id]);
        $volume = Yii::$app->request->post("volume");
        if ($volume == null) {
            throw new HttpException(400, "Inputan volume tidak tersedia");
        }

        if ($model == null) {
            throw new HttpException(404, "Barang tidak ditemukan");
        } else {
            $material = $model->material;
            if ($material == null) {
                throw new HttpException(501, "Material belum diatur");
            }

            $rumus = implode(" ", json_decode($material->rumus));
            $params = json_decode($model->params);
            foreach ($params as $key => $param) {
                $rumus = str_replace($key, $param, $rumus);
            }

            $value_incm = eval("return " . $rumus . ";");
            $kebutuhan = number_format(($volume * 1000) / $value_incm, 2);
            return $kebutuhan . " " . $model->satuan->nama;
        }
    }

    function getParameter($id)
    {
        $model = SupplierMaterial::findOne(['id' => $id]);
        if ($model == null) {
            return [];
        }

        if ($model->rumus == "") return "";
        $params = json_decode($model->rumus);
        $params = array_diff($params, Constant::calculatorAllowedSymbol()); // remove symbol
        $params = array_unique($params);
        return $params;
    }


    /**
     * actionInsertSpk
     * Di gunakan untuk melakukan penambahan spk pada keranjang
     * Aksi ini berada pada detail bahan material
     * @param integer $id
     * @return exception|string
     */
    function actionInsertSpk($uniq)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Constant::getUser();
        $cart = SupplierOrderCart::findOne([
            "user_id" => $user->id,
            "kode_unik" => $uniq
        ]);

        if (!$cart) {
            throw new HttpException(404, "Item tidak ditemukan dalam keranjang");
        }

        if ($cart->jumlah < $cart->supplierBarang->minimal_beli_satuan) {
            throw new HttpException(400, "Belum memenuhi jumlah minimum pembelian");
        }

        $cart->scenario = $cart::SCENARIO_UPDATE_SPK;

        if ($data = Yii::$app->request->post()) {
            try {
                $cart->no_spk = $data['no_spk'];
                $cart->keterangan_proyek = $data['keterangan_proyek'];
                $cart->valid_spk = 1; // otomatis valid
                if ($cart->validate() == false) {
                    throw new HttpException(400, Constant::flattenError($cart->getErrors()));
                }

                $cart->subtotal = $cart->jumlah * $cart->supplierBarang->harga_proyek;
                $cart->save();
                $sumtotal = SupplierOrderCart::find()->where(['user_id' => $user->id])->andWhere(['flag' => 1])->sum('subtotal');
                return [
                    "success" => true,
                    "message" => "No SPK berhasil ditambahkan, selamat anda mendapatkan harga spesial untuk item ini",
                    "data" => [
                        "subtotal" => \app\components\Angka::toReadableHarga($cart->subtotal, false),
                        "sumtotal" => \app\components\Angka::toReadableHarga($sumtotal, false),
                    ]
                ];
            } catch (\Throwable $th) {
                throw new HttpException($th->statusCode ? $th->statusCode : 500, $th->getMessage() ? $th->getMessage() : "Telah terjadi kesalahan");
            }
        } else {
            throw new HttpException(405, "Data tidak boleh kosong");
        }
    }

    public function actionPesananDiterima($id)
    {
        date_default_timezone_set("Asia/Jakarta");
        // check if model exist and status is belum diterima
        $user = \app\components\Constant::getUser();
        $model = \app\models\SupplierOrder::find()->where([
            'user_id' => $user->id,
            'kode_unik' => $id,
            'status' => 2 // telah lunas
        ])->one();

        $daftar_barangs = SupplierOrderDetail::find()->where(['supplier_order_id' => $model->id])
            ->andWhere(['created_by' => \Yii::$app->user->identity->id])->all();
        // throw exception if model is null
        if ($model == null) throw new \yii\web\HttpException(400, "Pesanan tidak ditemukan");

        try {
            if ($model->load($_POST)) :
                // change status to diterima, validate, and save model
                $model->status = 4;
                $model->tanggal_diterima = date("Y-m-d H:i:s");
                // $model->bukti_diterima = Yii::$app->request->post('keterangan_diterima');

                // upload image bukti_diterima
                $instance = UploadedFile::getInstance($model, 'bukti_diterima');
                $ext = end(explode(".", $instance->name));
                $response = $this->uploadFile($instance, $model->relativeUploadPath());
                if ($response->success == false) {
                    toastError("Dokumen gagal diunggah");
                    goto end;
                }
                $model->bukti_diterima = $response->filename;
                if ($model->validate()) :

                    // send notification to admin
                    \app\components\Notif::log(
                        null,
                        "Pesanan " . $model->no_nota . " telah diterima",
                        "Pesanan " . $model->no_nota . " telah diterima oleh konsumen",
                        [
                            "controller" => "supplier-order/view",
                            "android_route" => null,
                            "params" => [
                                "id" => $model->id
                            ]
                        ]
                    );

                    $model->save();
                    \Yii::$app->getSession()->setFlash(
                        'success',
                        'Data Tersimpan'
                    );
                    return $this->redirect(['pembayaran', 'id' => $id]);
                endif;
                \Yii::$app->getSession()->setFlash(
                    'error',
                    'Terjadi Kesalahan'
                );
            elseif (!\Yii::$app->request->isPost) :
                $model->load($_GET);
            endif;
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }
        end:
        return $this->render('pesanan-diterima', [
            'model' => $model,
            'daftar_barangs' => $daftar_barangs
        ]);
    }


    // public function actionAjaxRemoveItem($uniq)
    // {
    //     Yii::$app->response->format = Response::FORMAT_JSON;

    //     if (!Constant::isMethod(['DELETE'])) {
    //         throw new HttpException(404, "Request tidak diijinkan.");
    //     }

    //     $user = Constant::getUser();
    //     $cart = SupplierOrderCart::findOne([
    //         "user_id" => $user->id,
    //         "kode_unik" => $uniq
    //     ]);

    //     if (!$cart) {
    //         throw new HttpException(404, "Item tidak ditemukan dalam keranjang");
    //     }

    //     try {
    //         $cart->delete();
    //         $sumtotal = SupplierOrderCart::find()->where(['user_id' => $user->id])->sum('subtotal');
    //         return [
    //             "success" => true,
    //             "message" => "Item berhasil dihapus dari keranjang",
    //             "data" => [
    //                 "sumtotal" => \app\components\Angka::toReadableHarga($sumtotal, false),
    //             ]
    //         ];
    //     } catch (\Throwable $th) {
    //         throw new HttpException($th->statusCode ? $th->statusCode : 500, $th->getMessage() ? $th->getMessage() : "Telah terjadi kesalahan");
    //     }
    // }
}
