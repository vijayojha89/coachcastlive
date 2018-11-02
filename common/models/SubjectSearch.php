<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Subject;

/**
 * SubjectSearch represents the model behind the search form about `common\models\Subject`.
 */
class SubjectSearch extends Subject
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject_id', 'created_by', 'status'], 'integer'],
            [['name', 'created_date', 'modified_date','request_status','request_type'], 'safe'],
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
        $query = Subject::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => PAGE_SIZE),
            'sort'=> ['defaultOrder' => ['subject_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['!=', 'status', 2]);
                $query->andFilterWhere([
            'subject_id' => $this->subject_id,
            'created_by' => $this->created_by,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
            'status' => $this->status,
            'request_status' => $this->request_status,
            'request_type' => $this->request_type,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
    public function searchbystatus($params)
    {
        $query = Subject::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => PAGE_SIZE),
            'sort'=> ['defaultOrder' => ['subject_id'=>SORT_DESC]]
        ]);

        $filter_user_id_by = $this->created_by;
        if(isset($_REQUEST['Subject']['created_by']) ){
            $query_user = User::find()->where(['like', 'first_name', $_REQUEST['Subject']['created_by']])
                                      ->orWhere(['like', 'last_name', $_REQUEST['Subject']['created_by']])->all();
            $j = 0;
            foreach ($query_user as $value_user) {
                    $user_array_by[$j] = $value_user['id'];
                    $j++;
                } 
            if(empty($user_array_by)){$user_array_by = 0;}
            $filter_user_id_by = $user_array_by;
        }
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['=', 'status', 2]);
                $query->andFilterWhere([
            'subject_id' => $this->subject_id,
            'created_by' => $filter_user_id_by,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
            'status' => $this->status,
            'request_status' => $this->request_status,
            'request_type' => 1,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
