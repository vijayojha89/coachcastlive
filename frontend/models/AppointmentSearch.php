<?php
namespace frontend\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Appointment;
use yii\data\SqlDataProvider;

/**
 * QuestionSearch represents the model behind the search form about `frontend\models\Question`.
 */
class AppointmentSearch extends Appointment
{
    /**
     * @inheritdoc
     */
    
    public $dateorder,$from_date,$to_date;
    
    public function rules()
    {
        return [
            [['appointment_id', 'created_by', 'status', 'appointment_status','trainer_id'], 'integer'],
            [['title', 'reason', 'appointment_date', 'created_date', 'modified_date', 'dateorder','from_date','to_date'], 'safe'],
            [['price'], 'number'],
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
        $query = Appointment::find();
        $query->joinWith(['user']);
           
        $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => array('pageSize' => PAGE_SIZE),
                'sort'=> ['defaultOrder' => ['appointment_date'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
      
             if(@$_REQUEST['astatus'] == 'completed')
             {   
                 $query->andFilterWhere(['=', 'appointment.status', 3]);
             }
             else if(@$_REQUEST['astatus'] == 'cancelled')
             {    
                 $query->andFilterWhere(['=', 'appointment.status', 2]);
             }
             else
             {    
                 $query->andFilterWhere(['=', 'appointment.status', 1]);
             }
             
             //$query->andFilterWhere(['=', 'appointment.created_by', Yii::$app->user->id]);
            
            
        return $dataProvider;
    }
    
    public function trainersearch($params)
    {
        $query = Appointment::find();
        $query->joinWith(['trainer']);
           
        $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => array('pageSize' => PAGE_SIZE),
                'sort'=> ['defaultOrder' => ['appointment_date'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
      
             if(@$_REQUEST['astatus'] == 'completed')
             {   
                 $query->andFilterWhere(['=', 'appointment.status', 2]);
             }
             else if(@$_REQUEST['astatus'] == 'cancelled')
             {    
                 $query->andFilterWhere(['=', 'appointment.status', 3]);
             }
             else
             {    
                 $query->andFilterWhere(['=', 'appointment.status', 1]);
             }
             
             $query->andFilterWhere(['=', 'appointment.trainer_id', Yii::$app->user->id]);
            
            
        return $dataProvider;
    }
    
    
    
    public function searchFinancial($params)
    {
        $query = Question::find();
        
        
        if(@$_REQUEST['QuestionSearch']['dateorder'] == 1)
        {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => array('pageSize' => PAGE_SIZE),
                'sort'=> ['defaultOrder' => ['asked_date'=>SORT_ASC]]
            ]);
        }   
        else if(@$_REQUEST['QuestionSearch']['dateorder'] == 2)
        {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => array('pageSize' => PAGE_SIZE),
                'sort'=> ['defaultOrder' => ['asked_date'=>SORT_DESC]]
            ]);
        }    
        else
        {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => array('pageSize' => PAGE_SIZE),
                'sort'=> ['defaultOrder' => ['question_id'=>SORT_DESC]]
            ]);
        }    
        

        $this->load($params);

        if (!$this->validate()) {
           
            return $dataProvider;
        }
        
        $query->andFilterWhere(['!=', 'status', 2]);
        
             if($this->price_type)
             {
                 $query->andFilterWhere(['=', 'price_type',$this->price_type]);
                 
                 if($this->price_type == 2)
                 {
                     if(@$_REQUEST['range_min_max'])
                     {
                         $range_array = explode(',', trim($_REQUEST['range_min_max']));
                         
                         $query->andWhere("(confirm_bid BETWEEN $range_array[0] AND $range_array[1] )");
                         
                     }    
                     
                 }    
                 
             }    
             
             
             if($this->from_date AND $this->to_date)
             {    
                $fromdate = date('Y-m-d',strtotime($this->from_date));
                $todate = date('Y-m-d',strtotime($this->to_date));
                
                $query->andFilterWhere(['between', 'asked_date', $fromdate, $todate]);
             }
             
             
            
             $query->andFilterWhere(['=', 'confirm_select_tutor', Yii::$app->user->id]);
            
           
        
        return $dataProvider;
    }
    
    
    public function searchTutorQuestions($params)
    {
        $query = Question::find();

//        echo '<pre>';print_r($_REQUEST);exit;
        $tnl =  new TutorComponent();  
        if($_REQUEST['qstatus'] == 'completed'){$question_type = 2;}else{$question_type = 1;}
        $filter = [
                'price_type'=>(isset($_REQUEST['QuestionSearch']['price_type'])&& $_REQUEST['QuestionSearch']['price_type']!='')? $_REQUEST['QuestionSearch']['price_type']:'',
                'budget_range'=>(isset($_REQUEST['range_min_max'])&& $_REQUEST['range_min_max']!='' && $_REQUEST['QuestionSearch']['price_type'] == 2)?$_REQUEST['range_min_max']:'',
                'qualification_id'=>(isset($_REQUEST['QuestionSearch']['qualification_id'])&& $_REQUEST['QuestionSearch']['qualification_id']!='')?$_REQUEST['QuestionSearch']['qualification_id']:'',
                'subject_ids'=>(isset($_REQUEST['QuestionSearch']['subject_ids'])&& $_REQUEST['QuestionSearch']['subject_ids']!='')?implode(', ', $_REQUEST['QuestionSearch']['subject_ids']):'',
                'is_priority_set'=>(isset($_REQUEST['QuestionSearch']['is_priority_set'])&& $_REQUEST['QuestionSearch']['is_priority_set']!='')?$_REQUEST['QuestionSearch']['is_priority_set']:0,
                'confirm_select_tutor'=>(isset($_REQUEST['QuestionSearch']['confirm_select_tutor'])&& $_REQUEST['QuestionSearch']['confirm_select_tutor']!='')?$_REQUEST['QuestionSearch']['confirm_select_tutor']:0,
                'dateorder'=>(isset($_REQUEST['QuestionSearch']['dateorder'])&& $_REQUEST['QuestionSearch']['dateorder']!='')? $_REQUEST['QuestionSearch']['dateorder']:2,
                'qid'=>(isset($_REQUEST['qid'])&& $_REQUEST['qid']!='')? $_REQUEST['qid']:'',
                
            ];
        $data_question = $tnl->tutor_questions(\Yii::$app->user->identity->id, 0, $question_type, 0, 0, $filter, 2);
        
        $dataProvider = new SqlDataProvider([
            'sql' => $data_question['query'],
            'params' => [],
            'totalCount' => $data_question['count'],
            'sort' => [],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }
}
