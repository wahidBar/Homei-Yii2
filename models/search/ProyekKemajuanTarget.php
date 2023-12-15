<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProyekKemajuanTarget as ProyekKemajuanTargetModel;

/**
 * ProyekKemajuanTarget represents the model behind the search form about `app\models\ProyekKemajuanTarget`.
 * Modified By Defri Indras
 */
class ProyekKemajuanTarget extends ProyekKemajuanTargetModel{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'id_proyek', 'nilai_target', 'jumlah_target', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['kode_proyek', 'nama_target', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        $query = ProyekKemajuanTargetModel::find();

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
            'nilai_target' => $this->nilai_target,
            'jumlah_target' => $this->jumlah_target,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'kode_proyek', $this->kode_proyek])
            ->andFilterWhere(['like', 'nama_target', $this->nama_target]);

        return $dataProvider;
    }
}