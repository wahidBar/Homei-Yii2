<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\HargaMaterial;

/**
 * HargaMaterialSearch represents the model behind the search form about `app\models\HargaMaterial`.
 * Modified By Defri Indras
 */
class HargaMaterialSearch extends HargaMaterial{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'id_material', 'id_supplier', 'harga'], 'integer'],
            [['id_provinsi', 'id_kota'], 'safe'],
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
        $query = HargaMaterial::find();

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
            'id_material' => $this->id_material,
            'id_supplier' => $this->id_supplier,
            'harga' => $this->harga,
        ]);

        $query->andFilterWhere(['like', 'id_provinsi', $this->id_provinsi])
            ->andFilterWhere(['like', 'id_kota', $this->id_kota]);

        return $dataProvider;
    }
}