<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ApprovalSebelumPekerjaan;

/**
 * ApprovalSebelumPekerjaanSearch represents the model behind the search form about `app\models\ApprovalSebelumPekerjaan`.
 * Modified By Defri Indras
 */
class ApprovalSebelumPekerjaanSearch extends ApprovalSebelumPekerjaan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_proyek', 'id_progress', 'status'], 'integer'],
            [['nama_progress', 'foto_material', 'keterangan', 'revisi', 'created_at', 'updated_at'], 'safe'],
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
        $query = ApprovalSebelumPekerjaan::find()->andWhere(['flag' => 1]);

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
            'id_progress' => $this->id_progress,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'nama_progress', $this->nama_progress])
            ->andFilterWhere(['like', 'foto_material', $this->foto_material])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan])
            ->andFilterWhere(['like', 'revisi', $this->revisi]);

        return $dataProvider;
    }
}
