<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProyekKeuanganMasuk;

/**
 * ProyekKeuanganMasukSearch represents the model behind the search form about `app\models\ProyekKeuanganMasuk`.
 * Modified By Defri Indras
 */
class ProyekKeuanganMasukSearch extends ProyekKeuanganMasuk
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_proyek', 'id_kategori', 'created_by', 'deleted_by', 'flag'], 'integer'],
            [['item', 'tanggal', 'jumlah', 'keterangan', 'created_at', 'deleted_at'], 'safe'],
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
        $query = ProyekKeuanganMasuk::find()->where(['flag' => 1]);

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
            'id_kategori' => $this->id_kategori,
            'tanggal' => $this->tanggal,
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'deleted_by' => $this->deleted_by,
            'flag' => $this->flag,
        ]);

        $query->andFilterWhere(['like', 'item', $this->item])
            ->andFilterWhere(['like', 'jumlah', $this->jumlah])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}
