<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PenawaranDetail;

/**
 * PenawaranDetailSearch represents the model behind the search form about `app\models\PenawaranDetail`.
 * Modified By Defri Indras
 */
class PenawaranDetailSearch extends PenawaranDetail{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'id_penawaran', 'id_material', 'kisaran_harga', 'jumlah', 'sub_harga'], 'integer'],
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
        $query = PenawaranDetail::find();

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
            'id_penawaran' => $this->id_penawaran,
            'id_material' => $this->id_material,
            'kisaran_harga' => $this->kisaran_harga,
            'jumlah' => $this->jumlah,
            'sub_harga' => $this->sub_harga,
        ]);

        return $dataProvider;
    }
}