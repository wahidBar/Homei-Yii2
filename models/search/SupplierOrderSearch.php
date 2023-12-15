<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SupplierOrder;

/**
 * SupplierOrderSearch represents the model behind the search form about `app\models\SupplierOrder`.
 * Modified By Defri Indras
 */
class SupplierOrderSearch extends SupplierOrder
{

    public $search;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status', 'created_by', 'updated_by', 'deleted_by', 'flag'], 'integer'],
            [['kode_unik', 'no_nota', 'created_at', 'updated_at', 'deleted_at', 'search'], 'safe'],
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
        $query = SupplierOrder::find();

        $user = \Yii::$app->user->identity;
        if ($user->role_id == \app\components\Constant::ROLES["supplier"]) {
            $query->andWhere(['created_by' => $user->id]);
        }
        if ($user->role_id == \app\components\Constant::ROLES["user"]) {
            $query->andWhere(['user_id' => $user->id]);
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
            'user_id' => $this->user_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'flag' => $this->flag,
        ]);

        $query->andFilterWhere([
            'or',
            ['like', 'id', $this->search],
            ['like', 'user_id', $this->search],
            ['like', 'status', $this->search],
            // ['like', 'created_at', $this->search],
            // ['like', 'updated_at', $this->search],
            // ['like', 'deleted_at', $this->search],
            // ['like', 'created_by', $this->search],
            // ['like', 'updated_by', $this->search],
            // ['like', 'deleted_by', $this->search],
            // ['like', 'flag', $this->search],
            ['like', 'kode_unik', $this->search],
            ['like', 'no_nota', $this->search],
        ]);

        $query->andFilterWhere(['like', 'kode_unik', $this->kode_unik])
            ->andFilterWhere(['like', 'no_nota', $this->no_nota])
            ->orderBy(['created_at' => SORT_DESC]);

        return $dataProvider;
    }
}
