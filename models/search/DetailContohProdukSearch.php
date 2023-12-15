<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DetailContohProduk;

/**
 * DetailContohProdukSearch represents the model behind the search form about `app\models\DetailContohProduk`.
 * Modified By Defri Indras
 */
class DetailContohProdukSearch extends DetailContohProduk{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'id_contoh_produk', 'x_pos', 'y_pos'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
        $query = DetailContohProduk::find();

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
            'id_contoh_produk' => $this->id_contoh_produk,
            'x_pos' => $this->x_pos,
            'y_pos' => $this->y_pos,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}