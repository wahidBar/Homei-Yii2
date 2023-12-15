<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SupplierPengiriman;

/**
 * SupplierPengirimanSearch represents the model behind the search form about `app\models\SupplierPengiriman`.
 * Modified By Defri Indras
 */
class SupplierPengirimanSearch extends SupplierPengiriman{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'supplier_order_id', 'created_by', 'updated_by'], 'integer'],
            [['keterangan', 'tanggal', 'created_at', 'updated_at'], 'safe'],
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
        $query = SupplierPengiriman::find();

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
            'tanggal' => $this->tanggal,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'keterangan', $this->keterangan])->orderBy(['created_at'=> SORT_DESC]);;

        return $dataProvider;
    }
}