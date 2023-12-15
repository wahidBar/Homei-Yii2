<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProyekAnggota;

/**
 * ProyekAnggotaSearch represents the model behind the search form about `app\models\ProyekAnggota`.
 * Modified By Defri Indras
 */
class ProyekAnggotaSearch extends ProyekAnggota{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'id_proyek', 'id_user', 'id_role'], 'integer'],
            [['keterangan', 'created_at'], 'safe'],
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
        $query = ProyekAnggota::find();

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
            'id_proyek' => $this->id_proyek,
            'id_user' => $this->id_user,
            'id_role' => $this->id_role,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}