<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Konsultasi;

/**
 * KonsultasiSearch represents the model behind the search form about `app\models\Konsultasi`.
 * Modified By Defri Indras
 */
class KonsultasiSearch extends Konsultasi{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'id_isian_lanjutan', 'id_user', 'id_konsultan'], 'integer'],
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
        $query = Konsultasi::find();

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
            'id_user' => $this->id_user,
            'id_konsultan' => $this->id_konsultan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}