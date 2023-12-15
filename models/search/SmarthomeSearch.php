<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Smarthome;

/**
 * SmarthomeSearch represents the model behind the search form about `app\models\Smarthome`.
 * Modified By Defri Indras
 */
class SmarthomeSearch extends Smarthome
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['id_user', 'nama', 'token'], 'safe'],
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
        $query = Smarthome::find()->joinWith('user')->andWhere(['t_smarthome.flag' => 1])
            ->orderBy(['t_smarthome.id' => SORT_DESC]);

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
            't_smarthome.id' => $this->id,
        ]);

        $query
            ->andFilterWhere(['like', 't_smarthome.token', $this->token])
            ->andFilterWhere(['like', 't_smarthome.nama', $this->nama]);

        if ($this->id_user) {
            $query->andWhere([
                "OR",
                ["like", "user.name", $this->id_user],
                ["like", "user.email", $this->id_user],
                ["like", "user.no_hp", $this->id_user],
            ]);
        }

        return $dataProvider;
    }
}
