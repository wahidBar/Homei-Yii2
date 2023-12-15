<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DealPelanggan;

/**
 * DealPelangganSearch represents the model behind the search form about `app\models\DealPelanggan`.
 * Modified By Defri Indras
 */
class DealPelangganSearch extends DealPelanggan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_user', 'id_kontraktor', 'id_penawaran', 'id_isian_lanjutan', 'created_by', 'updated_by', 'deleted_by', 'flag'], 'integer'],
            [['nama_pelanggan', 'alamat_pelanggan', 'alamat_proyek', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        $query = DealPelanggan::find();

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
            'id_kontraktor' => $this->id_kontraktor,
            'id_penawaran' => $this->id_penawaran,
            'id_isian_lanjutan' => $this->id_isian_lanjutan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'flag' => $this->flag,
        ]);

        $query->andFilterWhere(['like', 'nama_pelanggan', $this->nama_pelanggan])
            ->andFilterWhere(['like', 'alamat_pelanggan', $this->alamat_pelanggan])
            ->andFilterWhere(['like', 'alamat_proyek', $this->alamat_proyek]);

        return $dataProvider;
    }
}
