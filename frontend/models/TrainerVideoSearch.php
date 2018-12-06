<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\TrainerVideo;

/**
 * TrainerVideoSearch represents the model behind the search form about `app\models\TrainerVideo`.
 */
class TrainerVideoSearch extends TrainerVideo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trainer_video_id', 'workout_type_id', 'created_by', 'modified_by', 'status'], 'integer'],
            [['title', 'description', 'video_image', 'video_file', 'created_date', 'modified_date'], 'safe'],
            [['price','no_of_view'], 'number'],
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
        $query = TrainerVideo::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => 9),
            'sort'=> ['defaultOrder' => ['trainer_video_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(\Yii::$app->user->identity->role != 'user')
        {    
            $query->andFilterWhere([
               'created_by' => YII::$app->user->identity->id
           ]);
        }
        
        $query->andFilterWhere(['!=', 'status', 2]);
                $query->andFilterWhere([
            'trainer_video_id' => $this->trainer_video_id,
            'workout_type_id' => $this->workout_type_id,
            'price' => $this->price,
            'created_by' => $this->created_by,
            'created_date' => $this->created_date,
            'modified_by' => $this->modified_by,
            'modified_date' => $this->modified_date,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'video_image', $this->video_image])
            ->andFilterWhere(['like', 'video_file', $this->video_file]);

        return $dataProvider;
    }
}
