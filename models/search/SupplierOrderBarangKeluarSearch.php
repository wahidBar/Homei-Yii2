<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SupplierOrder;
use app\models\SupplierOrderDetail;

/**
 * SupplierOrderSearch represents the model behind the search form about `app\models\SupplierOrder`.
 * Modified By Defri Indras
 */
class SupplierOrderBarangKeluarSearch extends SupplierOrder
{
    public $filterDate;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status', 'created_by', 'updated_by', 'deleted_by', 'flag'], 'integer'],
            [['kode_unik', 'no_nota', 'created_at', 'updated_at', 'deleted_at', 'filterDate'], 'safe'],
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
        $query->joinWith(['supplierOrder']);
        $query->andWhere(['not', ['jumlah' => null]])
            ->andWhere(['t_supplier_order.status'=>[2,4]])
            ->all();

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
            'user_id' => $this->user_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'flag' => $this->flag,
        ]);

        if (
            !is_null($this->filterDate) &&
            strpos($this->filterDate, ' - ') !== false
        ) {
            list($start_date, $end_date) = explode(' - ', $this->filterDate);
            $query->andFilterWhere(['between', 'date(t_supplier_order.created_at)', $start_date, $end_date]);
        }

        $query->andFilterWhere(['like', 'kode_unik', $this->kode_unik])
            ->andFilterWhere(['like', 'no_nota', $this->no_nota])
            ->orderBy(['created_at' => SORT_DESC]);

        return $dataProvider;
    }
}
