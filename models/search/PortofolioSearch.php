<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Portofolio;

/**
 * PortofolioSearch represents the model behind the search form about `app\models\Portofolio`.
 * Modified By Defri Indras
 */
class PortofolioSearch extends Portofolio{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'user_id', 'kontraktor_id', 'konsep_desain_id', 'total_harga', 'ruangan'], 'integer'],
            [['judul', 'slug', 'wilayah_provinsi', 'luas', 'timeline_proyek', 'tentang_proyek', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_by'], 'safe'],
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
        $query = Portofolio::find()->where(['flag' => 1]);

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
            'user_id' => $this->user_id,
            'kontraktor_id' => $this->kontraktor_id,
            'konsep_desain_id' => $this->konsep_desain_id,
            'total_harga' => $this->total_harga,
            'ruangan' => $this->ruangan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'judul', $this->judul])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'wilayah_provinsi', $this->wilayah_provinsi])
            ->andFilterWhere(['like', 'luas', $this->luas])
            ->andFilterWhere(['like', 'timeline_proyek', $this->timeline_proyek])
            ->andFilterWhere(['like', 'tentang_proyek', $this->tentang_proyek])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by]);

        return $dataProvider;
    }
}