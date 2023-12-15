<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProyekKemajuanHarian;

/**
 * ProyekKemajuanHarianSearch represents the model behind the search form about `app\models\ProyekKemajuanHarian`.
 * Modified By Defri Indras
 */
class ProyekKemajuanHarianSearch extends ProyekKemajuanHarian{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'id_proyek_kemajuan', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['tanggal', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['volume', 'bobot'], 'number'],
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
        $query = ProyekKemajuanHarian::find();

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
            'id_proyek_kemajuan' => $this->id_proyek_kemajuan,
            'tanggal' => $this->tanggal,
            'volume' => $this->volume,
            'bobot' => $this->bobot,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        return $dataProvider;
    }
}