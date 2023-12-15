<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LogHargaMaterial;

/**
 * LogHargaMaterialSearch represents the model behind the search form about `app\models\LogHargaMaterial`.
 * Modified By Defri Indras
 */
class LogHargaMaterialSearch extends LogHargaMaterial{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id'], 'integer'],
            [['nama_material', 'harga_material', 'provinsi', 'kota', 'nama_supplier', 'created_at'], 'safe'],
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
        $query = LogHargaMaterial::find()->orderBy(['id'=>SORT_DESC]);

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
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'nama_material', $this->nama_material])
            ->andFilterWhere(['like', 'harga_material', $this->harga_material])
            ->andFilterWhere(['like', 'provinsi', $this->provinsi])
            ->andFilterWhere(['like', 'kota', $this->kota])
            ->andFilterWhere(['like', 'nama_supplier', $this->nama_supplier]);

        return $dataProvider;
    }
}