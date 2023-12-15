<?php

namespace app\models\search;

use app\components\Constant;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\IsianLanjutan;

/**
 * IsianLanjutanSearch represents the model behind the search form about `app\models\IsianLanjutan`.
 * Modified By Defri Indras
 */
class IsianLanjutanSearch extends IsianLanjutan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_satuan', 'id_konsep_design', 'id_penawaran', 'id_user', 'id_lantai', 'created_by', 'updated_by', 'deleted_by', 'flag', 'status'], 'integer'],
            [['id_wilayah_provinsi', 'id_wilayah_kota', 'id_wilayah_kecamatan', 'id_wilayah_desa', 'nama_awal', 'nama_akhir', 'no_hp', 'label', 'panjang', 'lebar', 'budget', 'luas_tanah', 'rencana_pembangunan', 'rencana_survey', 'keterangan', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        $user = \app\components\Constant::getUser();
        if ($user->role_id == Constant::ROLE_KONSULTAN) {
            $query = IsianLanjutan::find()
                ->innerJoin('t_konsultasi', 't_konsultasi.id_isian_lanjutan = t_isian_lanjutan.id')
                ->where(['t_konsultasi.id_konsultan' => $user->id]);
        } else {
            $query = IsianLanjutan::find();
        }

        $query->andWhere(['t_isian_lanjutan.flag' => 1]);

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
            't_isian_lanjutan.id' => $this->id,
            't_isian_lanjutan.id_satuan' => $this->id_satuan,
            't_isian_lanjutan.id_konsep_design' => $this->id_konsep_design,
            't_isian_lanjutan.id_penawaran' => $this->id_penawaran,
            't_isian_lanjutan.id_user' => $this->id_user,
            't_isian_lanjutan.id_lantai' => $this->id_lantai,
            't_isian_lanjutan.rencana_pembangunan' => $this->rencana_pembangunan,
            't_isian_lanjutan.rencana_survey' => $this->rencana_survey,
            't_isian_lanjutan.created_at' => $this->created_at,
            't_isian_lanjutan.updated_at' => $this->updated_at,
            't_isian_lanjutan.deleted_at' => $this->deleted_at,
            't_isian_lanjutan.created_by' => $this->created_by,
            't_isian_lanjutan.updated_by' => $this->updated_by,
            't_isian_lanjutan.deleted_by' => $this->deleted_by,
            't_isian_lanjutan.flag' => $this->flag,
            't_isian_lanjutan.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 't_isian_lanjutan.id_wilayah_provinsi', $this->id_wilayah_provinsi])
            ->andFilterWhere(['like', 't_isian_lanjutan.id_wilayah_kota', $this->id_wilayah_kota])
            ->andFilterWhere(['like', 't_isian_lanjutan.id_wilayah_kecamatan', $this->id_wilayah_kecamatan])
            ->andFilterWhere(['like', 't_isian_lanjutan.id_wilayah_desa', $this->id_wilayah_desa])
            ->andFilterWhere(['like', 't_isian_lanjutan.nama_awal', $this->nama_awal])
            ->andFilterWhere(['like', 't_isian_lanjutan.nama_akhir', $this->nama_akhir])
            ->andFilterWhere(['like', 't_isian_lanjutan.no_hp', $this->no_hp])
            ->andFilterWhere(['like', 't_isian_lanjutan.label', $this->label])
            ->andFilterWhere(['like', 't_isian_lanjutan.panjang', $this->panjang])
            ->andFilterWhere(['like', 't_isian_lanjutan.lebar', $this->lebar])
            ->andFilterWhere(['like', 't_isian_lanjutan.budget', $this->budget])
            ->andFilterWhere(['like', 't_isian_lanjutan.luas_tanah', $this->luas_tanah])
            ->andFilterWhere(['like', 't_isian_lanjutan.keterangan', $this->keterangan])
            ->orderBy(['t_isian_lanjutan.created_at' => SORT_DESC]);

        return $dataProvider;
    }
}
