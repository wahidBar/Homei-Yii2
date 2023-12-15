<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProyekKeuanganKeluar;

/**
 * ProyekKeuanganKeluarSearch represents the model behind the search form about `app\models\ProyekKeuanganKeluar`.
 * Modified By Defri Indras
 */
class ProyekKeuanganKeluarSearch extends ProyekKeuanganKeluar{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'id_proyek', 'total_jumlah', 'tipe', 'status', 'created_by', 'updated_by', 'deleted_by', 'flag'], 'integer'],
            [['no_po', 'dokumen_po', 'no_invoice', 'keterangan', 'tanggal', 'vendor', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        $query = ProyekKeuanganKeluar::find()->where(['flag' => 1]);

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
            'tanggal' => $this->tanggal,
            'total_jumlah' => $this->total_jumlah,
            'tipe' => $this->tipe,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
            'flag' => $this->flag,
        ]);

        $query->andFilterWhere(['like', 'no_po', $this->no_po])
            ->andFilterWhere(['like', 'dokumen_po', $this->dokumen_po])
            ->andFilterWhere(['like', 'no_invoice', $this->no_invoice])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan])
            ->andFilterWhere(['like', 'vendor', $this->vendor]);

        return $dataProvider;
    }
}