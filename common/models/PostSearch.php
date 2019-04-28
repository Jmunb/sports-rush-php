<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Post;

/**
 * PostSearch represents the model behind the search form of `app\models\Post`.
 */
class PostSearch extends Post
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'width', 'height', 'tag_id', 'created_time', 'user_id', 'status', 'created_at'], 'integer'],
            [['external_id', 'url', 'link', 'full_name', 'username', 'profile_picture'], 'safe'],
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
        $query = Post::find()->with(['images']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder'=> ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'width' => $this->width,
            'height' => $this->height,
            'created_time' => $this->created_time,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'tag_id' => $this->tag_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'external_id', $this->external_id])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'profile_picture', $this->profile_picture]);

        return $dataProvider;
    }
}