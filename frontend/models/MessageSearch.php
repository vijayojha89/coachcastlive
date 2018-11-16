<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Message;

/**
 * MessageSearch represents the model behind the search form about `frontend\models\Message`.
 */
class MessageSearch extends Message
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message_id', 'from_id', 'to_id', 'reply', 'is_read', 'status'], 'integer'],
            [['message_text', 'created_date'], 'safe'],
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
        
        
        $query = Message::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
             'pagination' => array('pageSize' => PAGE_SIZE),
            'sort'=> ['defaultOrder' => ['message_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        if(YII::$app->controller->action->id == "index")
        {    
            $query->andFilterWhere([
               'to_id' => YII::$app->user->identity->id
           ]);
        }
        
        if(YII::$app->controller->action->id == "sent")
        {    
            $query->andFilterWhere([
               'from_id' => YII::$app->user->identity->id
           ]);
        }
        
         
        // grid filtering conditions
        $query->andFilterWhere([
            'message_id' => $this->message_id,
            'from_id' => $this->from_id,
            'to_id' => $this->to_id,
            'reply' => $this->reply,
            'is_read' => $this->is_read,
            'status' => $this->status,
            'created_date' => $this->created_date,
        ]);

        $query->andFilterWhere(['like', 'message_text', $this->message_text]);

        return $dataProvider;
    }
}
