<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProyekKeuanganKeluarDetail;

/**
 * ProyekKeuanganKeluarDetailSearch represents the model behind the search form about `app\models\ProyekKeuanganKeluarDetail`.
 * Modified By Defri Indras
 */
class ProyekKeuanganKeluarDetailSearch extends ProyekKeuanganKeluarDetail{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'kuantitas', 'harga_satuan', 'jumlah'], 'integer'],
            [['item', 'satuan', 'deskripsi'], 'safe'],
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
        $query = ProyekKeuanganKeluarDetail::find();

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
            'kuantitas' => $this->kuantitas,
            'harga_satuan' => $this->harga_satuan,
            'jumlah' => $this->jumlah,
        ]);

        $query->andFilterWhere(['like', 'item', $this->item])
            ->andFilterWhere(['like', 'satuan', $this->satuan])
            ->andFilterWhere(['like', 'deskripsi', $this->deskripsi]);

        return $dataProvider;
    }
}