<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SiteSetting as SiteSettingModel;

/**
 * SiteSetting represents the model behind the search form about `app\models\SiteSetting`.
 * Modified By Defri Indras
 */
class SiteSetting extends SiteSettingModel{
    /**
    * @inheritdoc
    */
    public function rules()
    {
    return [
        [['id'], 'integer'],
            [['judul', 'logo', 'tentang_web', 'alamat', 'no_telp', 'email', 'facebook', 'twitter', 'instagram', 'youtube', 'tiktok'], 'safe'],
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
        $query = SiteSettingModel::find();

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
        ]);

        $query->andFilterWhere(['like', 'judul', $this->judul])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'tentang_web', $this->tentang_web])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'no_telp', $this->no_telp])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'facebook', $this->facebook])
            ->andFilterWhere(['like', 'twitter', $this->twitter])
            ->andFilterWhere(['like', 'instagram', $this->instagram])
            ->andFilterWhere(['like', 'youtube', $this->youtube])
            ->andFilterWhere(['like', 'tiktok', $this->tiktok]);

        return $dataProvider;
    }
}