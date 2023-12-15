<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProyekKemajuan;

/**
 * ProyekKemajuanSearch represents the model behind the search form about `app\models\ProyekKemajuan`.
 * Modified By Defri Indras
 */
class ProyekKemajuanSearch extends ProyekKemajuan{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'id_proyek', 'id_satuan', 'status_verifikasi', 'created_by', 'updated_by', 'deleted_by', 'flag'], 'integer'],
            [['item', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['volume', 'bobot', 'volume_kemajuan', 'bobot_kemajuan'], 'number'],
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
        $query = ProyekKemajuan::find()->where(['flag' => 1]);

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
            'id_satuan' => $this->id_satuan,
            'volume' => $this->volume,
            'bobot' => $this->bobot,
            'volume_kemajuan' => $this->volume_kemajuan,
            'bobot_kemajuan' => $this->bobot_kemajuan,
            'status_verifikasi' => $this->status_verifikasi,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'flag' => $this->flag,
        ]);

        $query->andFilterWhere(['like', 'item', $this->item]);

        return $dataProvider;
    }
}