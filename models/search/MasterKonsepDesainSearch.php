<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MasterKonsepDesain;

/**
 * MasterKonsepDesainSearch represents the model behind the search form about `app\models\MasterKonsepDesain`.
 * Modified By Defri Indras
 */
class MasterKonsepDesainSearch extends MasterKonsepDesain{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'flag'], 'integer'],
            [['nama_konsep', 'gambar'], 'safe'],
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
        $query = MasterKonsepDesain::find();

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
            'flag' => $this->flag,
        ]);

        $query->andFilterWhere(['like', 'nama_konsep', $this->nama_konsep])
            ->andFilterWhere(['like', 'gambar', $this->gambar]);

        return $dataProvider;
    }
}