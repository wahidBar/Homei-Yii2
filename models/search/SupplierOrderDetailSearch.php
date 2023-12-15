<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SupplierOrderDetail;

/**
 * SupplierOrderDetailSearch represents the model behind the search form about `app\models\SupplierOrderDetail`.
 * Modified By Defri Indras
 */
class SupplierOrderDetailSearch extends SupplierOrderDetail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'supplier_order_id', 'supplier_barang_id', 'jumlah', 'total_ppn', 'created_by', 'updated_by'], 'integer'],
            [['kode_unik', 'kode_order', 'catatan', 'voucher', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SupplierOrderDetail::find();

        $user = \Yii::$app->user->identity;
        if ($user->role_id == \app\components\Constant::ROLES["supplier"]) {
            $query->andWhere(['created_by' => $user->id]);
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'supplier_order_id' => $this->supplier_order_id,
            'supplier_barang_id' => $this->supplier_barang_id,
            'jumlah' => $this->jumlah,
            'total_ppn' => $this->total_ppn,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'kode_unik', $this->kode_unik])
            ->andFilterWhere(['like', 'kode_order', $this->kode_order])
            ->andFilterWhere(['like', 'catatan', $this->catatan])
            ->andFilterWhere(['like', 'voucher', $this->voucher]);

        return $dataProvider;
    }
}
