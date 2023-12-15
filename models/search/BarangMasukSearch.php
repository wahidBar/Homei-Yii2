<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BarangMasuk;

/**
 * BarangMasukSearch represents the model behind the search form about `app\models\BarangMasuk`.
 * Modified By Defri Indras
 */
class BarangMasukSearch extends BarangMasuk{
    public $filterDate;

    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'id_master_gudang', 'id_supplier_barang', 'jumlah', 'created_by', 'updated_by', 'deleted_by', 'flag'], 'integer'],
            [['no_po', 'keterangan', 'created_at', 'updated_at', 'deleted_at', 'filterDate'], 'safe'],
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
        $query = BarangMasuk::find();

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
            'id_master_gudang' => $this->id_master_gudang,
            'id_supplier_barang' => $this->id_supplier_barang,
            'jumlah' => $this->jumlah,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'flag' => $this->flag,
        ]);

        if (!is_null($this->filterDate) && 
            strpos($this->filterDate, ' - ') !== false ) {
            list($start_date, $end_date) = explode(' - ', $this->filterDate);
            $query->andFilterWhere(['between', 'date(created_at)', $start_date, $end_date]);
        }

        $query->andFilterWhere(['like', 'no_po', $this->no_po])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}