<?php

namespace app\models\search;

use app\components\Constant;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Proyek;

/**
 * ProyekSearch represents the model behind the search form about `app\models\Proyek`.
 * Modified By Defri Indras
 */
class ProyekSearch extends Proyek
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'nilai_kontrak', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['nama_proyek', 'deskripsi_proyek', 'tanggal_awal_kontrak', 'tanggal_akhir_kontrak', 'latitude_proyek', 'longitude_proyek', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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

        $query = Proyek::find()->where(['flag' => 1]);
        if ($user->role_id != Constant::ROLES['sa']) {
            $query->innerJoin('t_proyek_anggota', 't_proyek_anggota.id_proyek = t_proyek.id')
                ->andWhere(['t_proyek_anggota.id_user' => $user->id]);
        }

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
            'nilai_kontrak' => $this->nilai_kontrak,
            'tanggal_awal_kontrak' => $this->tanggal_awal_kontrak,
            'tanggal_akhir_kontrak' => $this->tanggal_akhir_kontrak,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'nama_proyek', $this->nama_proyek])
            ->andFilterWhere(['like', 'deskripsi_proyek', $this->deskripsi_proyek])
            ->andFilterWhere(['like', 'latitude_proyek', $this->latitude_proyek])
            ->andFilterWhere(['like', 'longitude_proyek', $this->longitude_proyek])
            ->orderBy(['created_at' => SORT_DESC]);

        return $dataProvider;
    }
}
