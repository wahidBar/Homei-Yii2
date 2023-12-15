<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProfileTukang;

/**
 * ProfileTukangSearch represents the model behind the search form about `app\models\ProfileTukang`.
 * Modified By Defri Indras
 */
class ProfileTukangSearch extends ProfileTukang{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'id_user', 'id_layanan', 'flag'], 'integer'],
            [['nama', 'foto_ktp', 'keahlian', 'alamat'], 'safe'],
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
        $query = ProfileTukang::find();

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
            'id_user' => $this->id_user,
            'id_layanan' => $this->id_layanan,
            'flag' => $this->flag,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'foto_ktp', $this->foto_ktp])
            ->andFilterWhere(['like', 'keahlian', $this->keahlian])
            ->andFilterWhere(['like', 'alamat', $this->alamat]);

        return $dataProvider;
    }
}