<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProyekKeuanganKeluarBayar;

/**
 * ProyekKeuanganKeluarBayarSearch represents the model behind the search form about `app\models\ProyekKeuanganKeluarBayar`.
 * Modified By Defri Indras
 */
class ProyekKeuanganKeluarBayarSearch extends ProyekKeuanganKeluarBayar{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'id_proyek', 'id_keuangan_keluar', 'dibayar', 'created_by', 'updated_by', 'deleted_by', 'flag'], 'integer'],
            [['tanggal', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        $query = ProyekKeuanganKeluarBayar::find()->where(['flag' => 1]);

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
            'id_keuangan_keluar' => $this->id_keuangan_keluar,
            'tanggal' => $this->tanggal,
            'dibayar' => $this->dibayar,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'flag' => $this->flag
        ]);

        return $dataProvider;
    }
}