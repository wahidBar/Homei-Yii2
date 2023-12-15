<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DataDiri;

/**
 * DataDiriSearch represents the model behind the search form about `app\models\DataDiri`.
 * Modified By Defri Indras
 */
class DataDiriSearch extends DataDiri{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'user_id', 'biaya_projek', 'created_by', 'updated_by', 'deleted_by', 'flag'], 'integer'],
            [['kebutuhan_bisnis', 'jenis_properti', 'nama', 'nama_pic', 'email_pic', 'no_hp_pic', 'alamat_projek', 'wilayah_provinsi', 'wilayah_kota', 'wilayah_kecamatan', 'wilayah_desa', 'luas_area', 'created_at', 'updated_at'], 'safe'],
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
        $query = DataDiri::find();

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
            'user_id' => $this->user_id,
            'biaya_projek' => $this->biaya_projek,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'flag' => $this->flag,
        ]);

        $query->andFilterWhere(['like', 'kebutuhan_bisnis', $this->kebutuhan_bisnis])
            ->andFilterWhere(['like', 'jenis_properti', $this->jenis_properti])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'nama_pic', $this->nama_pic])
            ->andFilterWhere(['like', 'email_pic', $this->email_pic])
            ->andFilterWhere(['like', 'no_hp_pic', $this->no_hp_pic])
            ->andFilterWhere(['like', 'alamat_projek', $this->alamat_projek])
            ->andFilterWhere(['like', 'wilayah_provinsi', $this->wilayah_provinsi])
            ->andFilterWhere(['like', 'wilayah_kota', $this->wilayah_kota])
            ->andFilterWhere(['like', 'wilayah_kecamatan', $this->wilayah_kecamatan])
            ->andFilterWhere(['like', 'wilayah_desa', $this->wilayah_desa])
            ->andFilterWhere(['like', 'luas_area', $this->luas_area]);

        return $dataProvider;
    }
}