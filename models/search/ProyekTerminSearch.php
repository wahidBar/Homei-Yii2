<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProyekTermin;

/**
 * ProyekTerminSearch represents the model behind the search form about `app\models\ProyekTermin`.
 * Modified By Defri Indras
 */
class ProyekTerminSearch extends ProyekTermin{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'proyek_id', 'user_id', 'penyelesaian_pekerjaan', 'nilai_pembayaran', 'status', 'created_by', 'updated_by', 'flag'], 'integer'],
            [['kode_unik', 'termin', 'created_at', 'updated_at'], 'safe'],
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
        $query = ProyekTermin::find()->where(['flag' => 1]);

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
            'proyek_id' => $this->proyek_id,
            'user_id' => $this->user_id,
            'penyelesaian_pekerjaan' => $this->penyelesaian_pekerjaan,
            'nilai_pembayaran' => $this->nilai_pembayaran,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'flag' => $this->flag,
        ]);

        $query->andFilterWhere(['like', 'kode_unik', $this->kode_unik])
            ->andFilterWhere(['like', 'termin', $this->termin]);

        return $dataProvider;
    }
}