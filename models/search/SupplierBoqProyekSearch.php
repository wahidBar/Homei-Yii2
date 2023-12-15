<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SupplierBoqProyek;

/**
 * SupplierBoqProyekSearch represents the model behind the search form about `app\models\SupplierBoqProyek`.
 * Modified By Defri Indras
 */
class SupplierBoqProyekSearch extends SupplierBoqProyek{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'id_user', 'created_by', 'updated_by', 'deleted_by', 'flag'], 'integer'],
            [['boq_proyek', 'nomer_spk', 'informasi_proyek', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        $query = SupplierBoqProyek::find();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'flag' => $this->flag,
        ]);

        $query->andFilterWhere(['like', 'boq_proyek', $this->boq_proyek])
            ->andFilterWhere(['like', 'nomer_spk', $this->nomer_spk])
            ->andFilterWhere(['like', 'informasi_proyek', $this->informasi_proyek]);

        return $dataProvider;
    }
}