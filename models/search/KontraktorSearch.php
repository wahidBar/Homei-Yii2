<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Kontraktor;

/**
 * KontraktorSearch represents the model behind the search form about `app\models\Kontraktor`.
 * Modified By Defri Indras
 */
class KontraktorSearch extends Kontraktor{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'created_by', 'updated_by', 'deleted_by', 'flag'], 'integer'],
            [['nama_kontraktor', 'alamat', 'telepon', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        $query = Kontraktor::find()->where(['flag' => 1]);

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
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'flag' => $this->flag,
        ]);

        $query->andFilterWhere(['like', 'nama_kontraktor', $this->nama_kontraktor])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'telepon', $this->telepon]);

        return $dataProvider;
    }
}