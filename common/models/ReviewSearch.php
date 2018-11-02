<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Review;

/**
 * ReviewSearch represents the model behind the search form about `common\models\Review`.
 */
class ReviewSearch extends Review
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['review_id', 'review_opt', 'rating', 'question_id', 'posted_by', 'posted_for', 'status'], 'integer'],
            [['comment', 'created_date', 'posted_by_role', 'posted_for_role'], 'safe'],
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
        $query = Review::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => PAGE_SIZE),
            'sort'=> ['defaultOrder' => ['review_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['!=', 'status', 2]);
                $query->andFilterWhere([
            'review_id' => $this->review_id,
            'review_opt' => $this->review_opt,
            'rating' => $this->rating,
            'created_date' => $this->created_date,
            'question_id' => $this->question_id,
            'posted_by' => $this->posted_by,
            'posted_for' => $this->posted_for,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'posted_by_role', $this->posted_by_role])
            ->andFilterWhere(['like', 'posted_for_role', $this->posted_for_role]);

        return $dataProvider;
    }
    public function searchTutor($params)
    {
        $query = Review::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => PAGE_SIZE),
            'sort'=> ['defaultOrder' => ['review_id'=>SORT_DESC]]
        ]);

        $this->load($params);
        
        $filter_user_id_for = $this->posted_for;
            if(isset($_REQUEST['Review']['posted_for']) ){
            $query_user = User::find()->where(['like', 'first_name', $_REQUEST['Review']['posted_for']])
                                      ->orWhere(['like', 'last_name', $_REQUEST['Review']['posted_for']])->all();
            $j = 0;
            foreach ($query_user as $value_user) {
                    $user_array_for[$j] = $value_user['id'];
                    $j++;
                } 
            if(empty($user_array_for)){$user_array_for = 0;}
            $filter_user_id_for = $user_array_for;
        }
        $filter_user_id_by = $this->posted_by;
        if(isset($_REQUEST['Review']['posted_by']) ){
            $query_user = User::find()->where(['like', 'first_name', $_REQUEST['Review']['posted_by']])
                                      ->orWhere(['like', 'last_name', $_REQUEST['Review']['posted_by']])->all();
            $j = 0;
            foreach ($query_user as $value_user) {
                    $user_array_by[$j] = $value_user['id'];
                    $j++;
                } 
            if(empty($user_array_by)){$user_array_by = 0;}
            $filter_user_id_by = $user_array_by;
        }
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['!=', 'status', 2]);
                $query->andFilterWhere([
            'review_id' => $this->review_id,
            'review_opt' => $this->review_opt,
            'rating' => $this->rating,
            'created_date' => $this->created_date,
            'question_id' => $this->question_id,
            'posted_by' => $filter_user_id_by,
            'posted_for' => $filter_user_id_for,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'posted_by_role', $this->posted_by_role])
            ->andFilterWhere(['like', 'posted_for_role', 'tutor']);

        return $dataProvider;
    }
    public function searchStudent($params)
    {
        $query = Review::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => PAGE_SIZE),
            'sort'=> ['defaultOrder' => ['review_id'=>SORT_DESC]]
        ]);

        $this->load($params);
        
        $filter_user_id_for = $this->posted_for;
            if(isset($_REQUEST['Review']['posted_for']) ){
            $query_user = User::find()->where(['like', 'first_name', $_REQUEST['Review']['posted_for']])
                                      ->orWhere(['like', 'last_name', $_REQUEST['Review']['posted_for']])->all();
            $j = 0;
            foreach ($query_user as $value_user) {
                    $user_array_for[$j] = $value_user['id'];
                    $j++;
                } 
            if(empty($user_array_for)){$user_array_for = 0;}
            $filter_user_id_for = $user_array_for;
        }
        $filter_user_id_by = $this->posted_by;
        if(isset($_REQUEST['Review']['posted_by']) ){
            $query_user = User::find()->where(['like', 'first_name', $_REQUEST['Review']['posted_by']])
                                      ->orWhere(['like', 'last_name', $_REQUEST['Review']['posted_by']])->all();
            $j = 0;
            foreach ($query_user as $value_user) {
                    $user_array_by[$j] = $value_user['id'];
                    $j++;
                } 
            if(empty($user_array_by)){$user_array_by = 0;}
            $filter_user_id_by = $user_array_by;
        }
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['!=', 'status', 2]);
                $query->andFilterWhere([
            'review_id' => $this->review_id,
            'review_opt' => $this->review_opt,
            'rating' => $this->rating,
            'created_date' => $this->created_date,
            'question_id' => $this->question_id,
            'posted_by' => $filter_user_id_by,
            'posted_for' => $filter_user_id_for,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'posted_by_role', $this->posted_by_role])
            ->andFilterWhere(['like', 'posted_for_role','student']);

        return $dataProvider;
    }
}
