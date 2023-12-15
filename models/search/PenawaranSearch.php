<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Penawaran;

/**
 * PenawaranSearch represents the model behind the search form about `app\models\Penawaran`.
 * Modified By Defri Indras
 */
class PenawaranSearch extends Penawaran{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'id_isian_lanjutan', 'estimasi_waktu', 'harga_penawaran'], 'integer'],
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
        $query = Penawaran::find();

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
            'id_isian_lanjutan' => $this->id_isian_lanjutan,
            'estimasi_waktu' => $this->estimasi_waktu,
            'harga_penawaran' => $this->harga_penawaran,
        ])
        ->orderBy(['created_at'=> SORT_DESC]);

        return $dataProvider;
    }
}