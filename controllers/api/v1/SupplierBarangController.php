<?php

namespace app\controllers\api\v1;

/**
 * This is the class for REST controller "SupplierBarangController".
 * Modified by Defri Indra
 */

use app\components\Constant;
use app\models\SupplierMaterial;
use app\models\SupplierOrderCart;
use app\models\SupplierSubMaterial;
use Yii;
use yii\web\HttpException;

class SupplierBarangController extends \app\controllers\api\BaseController
{
    use \app\components\UploadFile;

    public $modelClass = 'app\models\SupplierBarang';

    public function behaviors()
    {
        $parent = parent::behaviors();
        if (isset($parent['authentication']['except'])) {
            $parent['authentication']['except'][] = 'sering-dibeli';
        }

        return $parent;
    }

    public function actionFilter()
    {
        return [
            "material" => SupplierMaterial::find()->select('id,nama')->all(),
            "sort" => [
                [
                    "id" => 0,
                    "nama" => "Default"
                ],
                [
                    "id" => 1,
                    "nama" => "Sortir dari yang termurah",
                ],
                [
                    "id" => 2,
                    "nama" => "Sortir dari yang termahal",
                ],
                [
                    "id" => 3,
                    "nama" => "Sortir dari yang terlama",
                ],
            ]
        ];
    }

    public function actionFilterSubMaterial($id)
    {
        $model = SupplierSubMaterial::find()
            ->where(["material_id" => $id])
            ->select(['id', 'nama'])
            ->all();
        return $model;
    }

    public function actionSeringDibeli()
    {
        // $query = $this->modelClass::find()
        //     ->innerJoin('t_supplier_order_detail', 't_supplier_order_detail.supplier_barang_id = t_supplier_barang.id')
        //     ->select([
        //         't_supplier_barang.id',
        //         't_supplier_barang.nama_barang',
        //         't_supplier_barang.supplier_id',
        //         't_supplier_barang.stok',
        //         't_supplier_barang.harga_ritel',
        //         't_supplier_barang.harga_proyek',
        //         't_supplier_barang.minimal_beli_satuan',
        //         't_supplier_barang.minimal_beli_volume',
        //         't_supplier_barang.gambar',
        //         'sum(t_supplier_order_detail.jumlah) as jumlah_terjual',
        //     ])
        //     ->andWhere([
        //         '>',
        //         't_supplier_order_detail.created_at',
        //         date('Y-m-d', strtotime('-1 month'))
        //     ])
        //     ->groupBy('t_supplier_barang.id')
        //     ->orderBy(['jumlah_terjual' => SORT_DESC])
        //     ->limit(20);
        $query = $this->modelClass::find()
            ->leftJoin('t_supplier_order_detail', 't_supplier_order_detail.supplier_barang_id = t_supplier_barang.id')
            ->select([
                't_supplier_barang.id',
                't_supplier_barang.nama_barang',
                't_supplier_barang.supplier_id',
                't_supplier_barang.stok',
                't_supplier_barang.harga_ritel',
                't_supplier_barang.harga_proyek',
                't_supplier_barang.minimal_beli_satuan',
                't_supplier_barang.minimal_beli_volume',
                't_supplier_barang.gambar',
                'sum(t_supplier_order_detail.jumlah) as jumlah_terjual',
            ])
            // ->andWhere([
            //     '>',
            //     't_supplier_order_detail.created_at',
            //     date('Y-m-d', strtotime('-1 month'))
            // ])
            ->groupBy('t_supplier_barang.id')
            // ->orderBy(new \yii\db\Expression('rand()'))
            ->orderBy(['jumlah_terjual' => SORT_DESC])
            ->limit(20);

        return $this->dataProvider($query);
    }


    public function actionIndex()
    {
        $query = $this->modelClass::find()->select([
            'id',
            'nama_barang',
            'supplier_id',
            'stok',
            'harga_ritel',
            'harga_proyek',
            'minimal_beli_satuan',
            'minimal_beli_volume',
            'gambar'
        ]);

        if ($search = Yii::$app->request->get('q')) {
            $query->andWhere(
                [
                    'like',
                    'nama_barang',
                    $search
                ]
            );
        }

        if ($material_id = Yii::$app->request->get('material_filter')) {
            $material_id = intval($material_id);
            $query->andWhere(['material_id' => $material_id]);
        }

        if ($submaterial_id = Yii::$app->request->get('submaterial_filter')) {
            $submaterial_id = intval($submaterial_id);
            $query->andWhere(['submaterial_id' => $submaterial_id]);
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
            } else if ($sort_id == 4) {
                $query->orderBy([
                    'created_at' => SORT_DESC,
                ]);
            } else {
                $query->orderBy(new \yii\db\Expression("rand()"));
            }
        } else {
            $query->orderBy(new \yii\db\Expression("rand()"));
        }

        return $this->dataProvider($query);
    }

    public function actionView($id)
    {
        $data = $this->findModel($id);
        return [
            "success" => true,
            "data" => $data
        ];
    }

    public function actionListKeranjang()
    {
        $user = Constant::getUser();
        return SupplierOrderCart::find()->where([
            'user_id' => $user->id,
        ])->all();
    }

    public function actionTambahKeranjang($id)
    {
        $user = Constant::getUser();
        $product = $this->findModel($id);
        $cart = SupplierOrderCart::findOne([
            'user_id' => $user->id,
            'supplier_barang_id' => $product->id
        ]);

        $jumlah = floatval(Yii::$app->request->post('jumlah'));
        $jumlah = ($jumlah == 0) ? 1 : $jumlah;

        try {
            if (!$cart) {
                if ($product->stok == 0) {
                    throw new HttpException(400, "Stok item tidak tersedia");
                }
                $new = new SupplierOrderCart();
                $new->kode_unik = Yii::$app->security->generateRandomString(30);
                $new->user_id = $user->id;
                $new->material_id = $product->material_id;
                $new->submaterial_id = $product->submaterial_id;
                $new->supplier_id = $product->supplier_id;
                $new->supplier_barang_id = $product->id;
                $new->jumlah = $jumlah;
                $new->harga_satuan = $product->harga_ritel;
                $new->subtotal = $new->jumlah * $product->harga_ritel;
                $new->valid_spk = 0; // bypass bonus
                if ($new->validate() == false) {
                    throw new HttpException(400, Constant::flattenError($new->getErrors()));
                }
                $new->save();
                return [
                    "success" => true,
                    "message" => Yii::t("cruds", "Berhasil menambahkan ke keranjang")
                ];
            } else {
                throw new HttpException(400, Yii::t("cruds", "Item ini telah ditambahkan sebelumnya"));
            }
        } catch (\Throwable $th) {
            throw new HttpException($th->statusCode ?? 500, $th->getMessage() ?? "Telah terjadi kesalahan");
        }
    }

    public function actionUpdateKeranjang($uniq, $type)
    {
        $user = Constant::getUser();
        $cart = $this->findModelKeranjang([
            'user_id' => $user->id,
            'kode_unik' => $uniq
        ]);

        if (Constant::isMethod(['post']) == false) {
            throw new HttpException(405, "Method tidak di izinkan");
        }

        try {
            if ($type == "tambah") {
                $cart->jumlah += 1;
            } else if ($type == "kurang") {
                $cart->jumlah -= 1;
            } else if ($type == "ubah") {
                $jumlah = floatval(Yii::$app->request->post('jumlah'));
                $jumlah = ($jumlah == 0) ? 1 : $jumlah;
                $cart->jumlah = $jumlah;
            } else {
                throw new HttpException(400, "Tipe operasi tidak tersedia");
            }

            if ($cart->jumlah == 0) {
                $cart->delete();
                return ["success" => 200, "message" => "Item berhasil dihapus dari keranjang"];
            }

            if ($cart->jumlah >= $cart->supplierBarang->minimal_beli_satuan && $cart->valid_spk == 1) $harga = $cart->supplierBarang->harga_proyek;
            else  $harga = $cart->supplierBarang->harga_ritel;

            $cart->harga_satuan = $harga;
            $cart->subtotal = $cart->jumlah * $harga;
            if ($cart->validate() == false) {
                throw new HttpException(400, Constant::flattenError($cart->getErrors()));
            }

            $cart->save();
            return [
                "success" => true,
                "data" => $cart,
                "message" => Yii::t("cruds", "Berhasil ubah ke keranjang")
            ];
        } catch (\Throwable $th) {
            throw new HttpException($th->statusCode ?? 500, $th->getMessage() ?? "Telah terjadi kesalahan");
        }
    }

    public function actionHapusKeranjang($uniq)
    {
        $user = Constant::getUser();
        $cart = $this->findModelKeranjang([
            'user_id' => $user->id,
            'kode_unik' => $uniq
        ]);

        if (Constant::isMethod(['delete']) == false) {
            throw new HttpException(405, "Method tidak di izinkan");
        }

        try {
            $cart->delete();
        } catch (\Throwable $th) {
            throw new HttpException($th->statusCode ?? 500, $th->getMessage() ?? "Telah terjadi kesalahan");
        }
    }

    public function actionInsertSpk($uniq)
    {
        if (Constant::isMethod(['post']) == false) {
            throw new HttpException(405, "Method tidak di izinkan");
        }
        $user = Constant::getUser();
        $cart = $this->findModelKeranjang([
            'user_id' => $user->id,
            'kode_unik' => $uniq
        ]);

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

                $cart->harga_satuan = $cart->supplierBarang->harga_proyek;
                $cart->subtotal = $cart->jumlah * $cart->supplierBarang->harga_proyek;
                $cart->save();
                return [
                    "success" => true,
                    "message" => "No SPK berhasil ditambahkan, selamat anda mendapatkan harga spesial untuk item ini"
                ];
            } catch (\Throwable $th) {
                throw new HttpException($th->statusCode ?? 500, $th->getMessage() ?? "Telah terjadi kesalahan");
            }
        } else {
            throw new HttpException(405, "Data tidak boleh kosong");
        }
    }

    public function actionCheckout()
    {
        $user = Constant::getUser();
        $list_product =  SupplierOrderCart::find()->where([
            'user_id' => $user->id,
        ])->all();

        if (\app\components\Constant::isMethod(['POST']) == false) throw new HttpException(405, "Method not allowed");
        if ($list_product == []) throw new HttpException(400, "Tidak ada item didalam keranjang");

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $sumtotal = SupplierOrderCart::find()->where([
                'user_id' => $user->id,
            ])->sum('subtotal');

            $order = new \app\models\SupplierOrder();
            $order->scenario = $order::SCENARIO_CREATE;

            $order->load($_POST, '');
            $order->user_id = \Yii::$app->user->identity->id;
            $order->kode_unik = Yii::$app->security->generateRandomString(30);
            $order->no_nota = 'INVHOMEI' . date('YmdH') . Yii::$app->security->generateRandomString(5);
            $order->status = 0;
            $order->deadline_bayar = date("Y-m-d H:i:s", strtotime('+1 day'));
            $order->nama_penerima = $order->nama_penerima ? $order->nama_penerima : $user->username;
            // $order->alamat = $_POST['alamat'];
            // $order->latitude = $_POST['latitude'];
            // $order->longitude = $_POST['longitude'];
            // $order->catatan = $_POST['catatan'];
            $order->total_harga = $sumtotal;

            if ($order->validate() == false) throw new HttpException(400, \app\components\Constant::flattenError($order->getErrors()));
            $order->save();

            foreach ($list_product as $item) :
                $barang = $item->supplierBarang;

                // pengurangan jumlah stok
                if ($item->jumlah > $barang->stok) {
                    $kekurangan = $item->jumlah - $barang->stok;
                    throw new HttpException(400, "Stok untuk " . $barang->nama_barang . " tidak mencukupi, stok kurang " . $kekurangan . " " . $barang->satuan->nama);
                }
                $barang->stok -= $item->jumlah;
                $barang->save();

                $detail = new \app\models\SupplierOrderDetail();
                $detail->scenario = $detail::SCENARIO_CREATE;

                $detail->kode_unik = Yii::$app->security->generateRandomString(30);
                $detail->supplier_order_id = $order->id;
                $detail->kode_order = $order->kode_unik;
                $detail->supplier_barang_id = $item->supplier_barang_id;
                $detail->jumlah = $item->jumlah;
                $detail->volume = $item->volume;
                $detail->catatan = null;
                $detail->total_ppn = 0;
                $detail->voucher = null;
                $detail->no_spk = $item->no_spk;
                $detail->keterangan_proyek = $item->keterangan_proyek;
                $detail->valid_spk = $item->valid_spk;
                $detail->harga_satuan = $item->harga_satuan;
                $detail->subtotal = $item->subtotal;

                if ($detail->validate() == false) throw new HttpException(400, \app\components\Constant::flattenError($detail->getErrors()));
                $detail->save();
                $item->delete();
            endforeach;

            $transaction->commit();

            \app\components\Notif::log(
                null,
                "Terdapat Pesanan Material Baru {$order->user->name} : " . $order->no_nota,
                "Pesanan " . $order->no_nota . " telah dibuat, silahkan cek di menu pesanan",
                [
                    "controller" => "supplier-order/view",
                    "android_route" => null,
                    "params" => [
                        "id" => $order->id
                    ]
                ]
            );

            return [
                "success" => true,
                "message" => "Checkout berhasil",
                "data" => $order
            ];
        } catch (\Throwable $th) {
            if ($transaction->getIsActive()) $transaction->rollBack();
            throw new HttpException($th->getStatusCode ?? 500, $th->getMessage() ?? "Telah terjadi kesalahan server");
        }
    }

    public function actionPembayaran($uniq)
    {
        if (Constant::isMethod(['post']) == false) {
            throw new HttpException(405, "Method tidak di izinkan");
        }
        $user = Constant::getUser();
        $model = \app\models\SupplierOrder::find()->where([
            'user_id' => $user->id,
            'kode_unik' => $uniq
        ])->one();

        if (in_array($model->status, [0, 3]) == false) throw new HttpException(400, "Pembayaran tidak dapat dilakukan. Menunggu konfirmasi dari admin atau sudah membayar");

        if ($model->status == $model::STATUS_BELUM_BAYAR) $model->scenario = $model::SCENARIO_BAYAR;
        else $model->scenario = $model::SCENARIO_BAYARULANG;

        if ($data = Yii::$app->request->post()) {
            try {
                $file = \yii\web\UploadedFile::getInstanceByName("bukti_bayar");
                $response = $this->uploadImage($file, $model->relativeUploadPath());
                if ($response->success == false)  throw new HttpException(400, "Gagal mengunggah gambar");
                $model->bukti_bayar = $response->filename;
                $model->alasan_tolak = null;
                $model->status = 1;
                $model->tanggal_bayar = date("Y-m-d H:i:s");
                $model->keterangan_baygggggar = $data['keterangan_bayar'] ? $data['keterangan_bayar'] : "";
                if ($model->validate() == false) {
                    throw new HttpException(400, Constant::flattenError($model->getErrors()));
                }
                $model->save();

                \app\components\Notif::log(
                    null,
                    "Pembayaran pesanan " . $model->no_nota,
                    "Pesanan " . $model->no_nota . " telah mengupload bukti pembayaran. Menunggu verifikasi anda",
                    [
                        "controller" => "supplier-order/view",
                        "android_route" => null,
                        "params" => [
                            "id" => $model->id
                        ]
                    ]
                );

                return [
                    "success" => true,
                    "message" => "Upload bukti pembayaran selesai, menunggu dikonfirmasi oleh admin",
                    "data" => $model
                ];
            } catch (\Throwable $th) {
                throw new HttpException($th->statusCode ?? 500, $th->getMessage() ?? "Telah terjadi kesalahan");
            }
        } else {
            throw new HttpException(405, "Data tidak boleh kosong");
        }
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
        $model = \app\models\SupplierBarang::findOne(["id" => $id]);
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

    private function findModelKeranjang($query)
    {
        $cart = SupplierOrderCart::findOne($query);
        if (!$cart) {
            throw new HttpException(400, Yii::t("cruds", "Item tidak tersedia di keranjang"));
        }
        return $cart;
    }


    public function actionCetakInvoice($uniq)
    {
        $model = \app\models\SupplierOrder::find()->where(['kode_unik' => $uniq])->andWhere(['user_id' => \Yii::$app->user->identity->id])->one();
        if ($model == null) throw new HttpException(404, "Data tidak ditemukan");

        $orders = \app\models\SupplierOrderDetail::find()->where(['supplier_order_id' => $model->id])->all();
        $user = \app\models\User::findOne($model->user_id);
        $setting = \app\models\SiteSetting::find()->one();


        $content = $this->renderPartial('/home/bahan-material/pdf/_reportView', [
            'model' => $model,
            'orders' => $orders,
            'setting' => $setting,
            'user' => $user
        ]);

        $pdf = new \kartik\mpdf\Pdf([
            'mode' => \kartik\mpdf\Pdf::MODE_UTF8,
            'format' => \kartik\mpdf\Pdf::FORMAT_A4,
            'orientation' => \kartik\mpdf\Pdf::ORIENT_PORTRAIT,
            'destination' => \kartik\mpdf\Pdf::DEST_BROWSER,
            'content' => $content,
            'marginHeader' => 0,
            'marginFooter' => 1,
            'marginTop' => 5,
            'marginBottom' => 5,
            'marginLeft' => 5,
            'marginRight' => 3,
            'cssFile' => [
                'homepage/css/invoice.css',
                'homepage/vendor/bootstrap4/bootstrap.min.css',
                'homepage/css/style.css',
                'homepage/css/style-print.css',
            ],
            'options' => [
                'defaultheaderline' => 0,
                'defaultfooterline' => 0,
            ],
        ]);
        $pdfbase64 = base64_encode($pdf->render());
        return $pdfbase64;
    }
}
