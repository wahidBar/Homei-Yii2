<?php

namespace app\models\search;

use app\components\Constant;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PekerjaanSameday;

/**
 * PekerjaanSamedaySearch represents the model behind the search form about `app\models\PekerjaanSameday`.
 * Modified By Defri Indras
 */
class PekerjaanSamedaySearch extends PekerjaanSameday
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_pelanggan', 'biaya', 'status', 'flag'], 'integer'],
            [['id_kategori', 'nama_pelanggan', 'alamat_pelanggan', 'foto_lokasi', 'uraian_pekerjaan', 'tanggal_survey', 'layanan_yang_diberikan', 'catatan_revisi', 'created_at', 'updated_at'], 'safe'],
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
        $user = Constant::getUser();
        if ($user->role_id == 1) {
            $query = PekerjaanSameday::find();
        } else if ($user->role_id == 8) { // tukang sameday
            $query = PekerjaanSameday::find()->andWhere([
                'id_tukang' => $user->id
            ]);
        } else {
            $query = PekerjaanSameday::find();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy('id DESC'),
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_pelanggan' => $this->id_pelanggan,
            'tanggal_survey' => $this->tanggal_survey,
            'biaya' => $this->biaya,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'flag' => $this->flag,
        ]);

        $query->andFilterWhere(['like', 'id_kategori', $this->id_kategori])
            ->andFilterWhere(['like', 'nama_pelanggan', $this->nama_pelanggan])
            ->andFilterWhere(['like', 'alamat_pelanggan', $this->alamat_pelanggan])
            ->andFilterWhere(['like', 'foto_lokasi', $this->foto_lokasi])
            ->andFilterWhere(['like', 'uraian_pekerjaan', $this->uraian_pekerjaan])
            ->andFilterWhere(['like', 'layanan_yang_diberikan', $this->layanan_yang_diberikan])
            ->andFilterWhere(['like', 'catatan_revisi', $this->catatan_revisi])
            ->orderBy(['created_at'=> SORT_DESC]);

        return $dataProvider;
    }
}
