<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Question;

/**
 * QuestionSearch represents the model behind the search form about `common\models\Question`.
 */
class QuestionSearch extends Question
{
    /**
     * @inheritdoc
     */
    public $dateorder;
    
    public function rules()
    {
        return [
            [['question_id', 'time_limit', 'is_priority_set', 'qualification_id', 'subject_id', 'price_type', 'confirm_bid', 'bid_status', 'created_by', 'status'], 'integer'],
            [['title', 'description', 'asked_date', 'created_date', 'modified_date','dateorder','question_status','admin_commission'], 'safe'],
            [['price', 'min_budget', 'max_budget'], 'number'],
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
        $query = Question::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => PAGE_SIZE),
            'sort'=> ['defaultOrder' => ['question_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->asked_date)) {
            $query->andFilterWhere(['=','SUBSTR(asked_date,1,10)',date('Y-m-d', strtotime($this->asked_date))]);
            $this->asked_date = $this->asked_date ;
        }
        if ($this->question_status == 4) {
            $query->andFilterWhere(['question_status'=>[4,5,6,7]]);
        }
        else if ($this->question_status == 1 || $this->question_status == 2 ||$this->question_status == 3){
            $query->andFilterWhere(['question_status' => $this->question_status,]);
        }
        $filter_user_id_by = $this->created_by;
        if(isset($_REQUEST['Question']['created_by']) ){
            $query_user = User::find()->where(['like', 'first_name', $_REQUEST['Question']['created_by']])
                                      ->orWhere(['like', 'last_name', $_REQUEST['Question']['created_by']])->all();
            $j = 0;
            foreach ($query_user as $value_user) {
                    $user_array_by[$j] = $value_user['id'];
                    $j++;
                } 
            if(empty($user_array_by)){$user_array_by = 0;}
            $filter_user_id_by = $user_array_by;
        }
        $query->andFilterWhere(['!=', 'status', 2]);
                $query->andFilterWhere([
            'question_id' => $this->question_id,
            'time_limit' => $this->time_limit,
            'is_priority_set' => $this->is_priority_set,
            'qualification_id' => $this->qualification_id,
            'subject_id' => $this->subject_id,
            'price_type' => $this->price_type,
            'price' => $this->price,
            'min_budget' => $this->min_budget,
            'max_budget' => $this->max_budget,
            'confirm_bid' => $this->confirm_bid,
            'bid_status' => $this->bid_status,
            'created_by' => $filter_user_id_by,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
    
    public function reconciliationsearch($params)
    {
        $query = Question::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => PAGE_SIZE),
            'sort'=> ['defaultOrder' => ['question_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->asked_date)) {
            $query->andFilterWhere(['=','SUBSTR(asked_date,1,10)',date('Y-m-d', strtotime($this->asked_date))]);
            $this->asked_date = $this->asked_date ;
        }
        
        if ($this->question_status == 2) {
            $query->andFilterWhere(['question_status'=>$this->question_status]);
        }
        else if ($this->question_status == 4) {
            $query->andFilterWhere(['question_status'=>[4,5,6,7]]);
        }
        else {
             $query->andFilterWhere(['question_status'=>[2,4,5,6,7]]);;
        }
        $filter_user_id_by = $this->created_by;
        if(isset($_REQUEST['Question']['created_by']) && $_REQUEST['Question']['created_by'] != ''){
            $query_user = User::find()->where(['like', 'first_name', $_REQUEST['Question']['created_by']])
                                      ->orWhere(['like', 'last_name', $_REQUEST['Question']['created_by']])->all();
            $j = 0;
            foreach ($query_user as $value_user) {
                    $user_array_by[$j] = $value_user['id'];
                    $j++;
                } 
            if(empty($user_array_by)){$user_array_by = 0;}
            $filter_user_id_by = $user_array_by;
        }
        
        $filter_user_tutor = $this->confirm_select_tutor;
        if(isset($_REQUEST['Question']['confirm_select_tutor']) && $_REQUEST['Question']['confirm_select_tutor'] != ''){
            $query_user = User::find()->where(['like', 'first_name', $_REQUEST['Question']['confirm_select_tutor']])
                                      ->orWhere(['like', 'last_name', $_REQUEST['Question']['confirm_select_tutor']])->all();
            $j = 0;
            foreach ($query_user as $value_user) {
                    $user_array_by[$j] = $value_user['id'];
                    $j++;
                } 
            if(empty($user_array_by)){$user_array_by = 0;}
            $filter_user_tutor = $user_array_by;
        }
        
//        echo '<pre>';print_r($_REQUEST);echo '</pre>';
        
        if(isset($_REQUEST['Question']['completed_date']) && $_REQUEST['Question']['completed_date'] != ''){
         $query->andFilterWhere(['=','DATE_FORMAT(completed_date, "%M %Y")',$_REQUEST['Question']['completed_date']]);   
        }
        else{
         $query->andFilterWhere(['=','DATE_FORMAT(completed_date, "%M %Y")',date("M Y")]);   
        }
        
        $query->andFilterWhere(['!=', 'status', 2]);
                $query->andFilterWhere([
            'question_id' => $this->question_id,
            'price_type' => $this->price_type,
            'price' => $this->price,
            'min_budget' => $this->min_budget,
            'max_budget' => $this->max_budget,
            'confirm_bid' => $this->confirm_bid,
            'bid_status' => $this->bid_status,
            'created_by' => $filter_user_id_by,
            'confirm_select_tutor' => $filter_user_tutor,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'admin_commission', $this->admin_commission])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
