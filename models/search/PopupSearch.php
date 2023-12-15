<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Popup;

/**
 * PopupSearch represents the model behind the search form about `app\models\Popup`.
 * Modified By Defri Indras
 */
class PopupSearch extends Popup{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id', 'type', 'android_redirect_type', 'android_show', 'web_show'], 'integer'],
            [['image', 'android_route', 'android_params', 'web_link'], 'safe'],
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
        $query = Popup::find();

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
            'type' => $this->type,
            'android_redirect_type' => $this->android_redirect_type,
            'android_show' => $this->android_show,
            'web_show' => $this->web_show,
        ]);

        $query->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'android_route', $this->android_route])
            ->andFilterWhere(['like', 'android_params', $this->android_params])
            ->andFilterWhere(['like', 'web_link', $this->web_link]);

        return $dataProvider;
    }
}