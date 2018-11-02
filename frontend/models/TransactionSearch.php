<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Transaction;

/**
 * TransactionSearch represents the model behind the search form about `common\models\Transaction`.
 */
class TransactionSearch extends Transaction
{
    
    public $subject_ids,$dateorder,$from_date,$to_date;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transaction_id', 'user_id', 'question_id', 'created_by', 'status'], 'integer'],
            [['studypad_txn_id', 'stripe_charge_id', 'stripe_card_id', 'balance_transaction', 'captured', 'stripe_customer_id', 'failure_code', 'failure_message', 'paid', 'created_date', 'modified_date','to_date','from_date','dateorder'], 'safe'],
            [['amount', 'amount_refunded'], 'number'],
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
        $query = Transaction::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => PAGE_SIZE),
            'sort'=> ['defaultOrder' => ['transaction_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['!=', 'status', 2]);
                $query->andFilterWhere([
            'transaction_id' => $this->transaction_id,
            'user_id' => $this->user_id,
            'question_id' => $this->question_id,
            'amount' => $this->amount,
            'amount_refunded' => $this->amount_refunded,
            'created_by' => $this->created_by,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'studypad_txn_id', $this->studypad_txn_id])
            ->andFilterWhere(['like', 'stripe_charge_id', $this->stripe_charge_id])
            ->andFilterWhere(['like', 'stripe_card_id', $this->stripe_card_id])
            ->andFilterWhere(['like', 'balance_transaction', $this->balance_transaction])
            ->andFilterWhere(['like', 'captured', $this->captured])
            ->andFilterWhere(['like', 'stripe_customer_id', $this->stripe_customer_id])
            ->andFilterWhere(['like', 'failure_code', $this->failure_code])
            ->andFilterWhere(['like', 'failure_message', $this->failure_message])
            ->andFilterWhere(['like', 'paid', $this->paid]);

        return $dataProvider;
    }
    
    
    
    
    public function searchPayment($params)
    {
        $query = Transaction::find();
        
        
        if(@$_REQUEST['TransactionSearch']['dateorder'] == 1)
        {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => array('pageSize' => PAGE_SIZE),
                'sort'=> ['defaultOrder' => ['created_date'=>SORT_ASC]]
            ]);
        }   
        else if(@$_REQUEST['TransactionSearch']['dateorder'] == 2)
        {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => array('pageSize' => PAGE_SIZE),
                'sort'=> ['defaultOrder' => ['created_date'=>SORT_DESC]]
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
        
        $query->andFilterWhere(['=', 'user_id', \Yii::$app->user->id]);
        
            
                 
                if(@$_REQUEST['range_min_max'])
                {
                    $range_array = explode(',', trim($_REQUEST['range_min_max']));
                    $query->andWhere("(amount BETWEEN $range_array[0] AND $range_array[1] )");
                }    
                 
             
             
             if($this->from_date AND $this->to_date)
             {    
                $fromdate = date('Y-m-d',strtotime($this->from_date));
                $todate = date('Y-m-d',strtotime($this->to_date));
                
                $query->andFilterWhere(['between', 'created_date', $fromdate, $todate]);
             }
            
            
           
        
        return $dataProvider;
    }
    
    
}
