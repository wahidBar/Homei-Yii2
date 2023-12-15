<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SmarthomeMasterProduk;

/**
 * SmarthomeMasterProdukSearch represents the model behind the search form about `app\models\SmarthomeMasterProduk`.
 * Modified By Defri Indras
 */
class SmarthomeMasterProdukSearch extends SmarthomeMasterProduk
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'digunakan', 'flag'], 'integer'],
            [['kode_produk', 'created_at'], 'safe'],
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
        $query = SmarthomeMasterProduk::find();

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
            'digunakan' => $this->digunakan,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'kode_produk', $this->kode_produk]);


        $query->active();
        return $dataProvider;
    }
}
