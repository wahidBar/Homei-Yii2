<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TutorialPemakaian;

/**
 * TutorialPemakaianSearch represents the model behind the search form about `\app\models\TutorialPemakaian`.
 * Modified By Defri Indras
 */
class TutorialPemakaianSearch extends TutorialPemakaian
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['judul', 'id_kategori', 'link_youtube', 'thumbnail', 'updated_at'], 'safe'],
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
        $query = TutorialPemakaian::find()
            ->leftJoin('t_tutorial_pemakaian_kategori', 't_tutorial_pemakaian.id_kategori = t_tutorial_pemakaian_kategori.id')
            ->andWhere(['t_tutorial_pemakaian_kategori.flag' => 1]);

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
            't_tutorial_pemakaian_kategori.id' => $this->id,
            't_tutorial_pemakaian_kategori.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 't_tutorial_pemakaian_kategori.judul', $this->judul])
            ->andFilterWhere(['like', 't_tutorial_pemakaian_kategori.nama_kategori', $this->id_kategori])
            ->andFilterWhere(['like', 't_tutorial_pemakaian_kategori.link_youtube', $this->link_youtube])
            ->andFilterWhere(['like', 't_tutorial_pemakaian_kategori.thumbnail', $this->thumbnail]);

        return $dataProvider;
    }
}
