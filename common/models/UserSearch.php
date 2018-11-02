<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id',  'status', 'created_by', 'modified_by', 'created_at', 'updated_at'], 'integer'],
            [['first_name', 'last_name', 'username',  'auth_key', 'password_hash', 'password_reset_token', 'email', 'profile_photo', 'role', 'user_last_login', 'created_date', 'modified_date','total_questions','total_referral_user'], 'safe'],
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
    public function search($params,$role)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => PAGE_SIZE),
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->created_date)) {
            $query->andFilterWhere(['=','SUBSTR(created_date,1,10)',date('Y-m-d', strtotime($this->created_date))]);
            $this->created_date = $this->created_date ;
        }
        
        $filter_expertise_by = $this->id;
        if(isset($_REQUEST['UserSearch']['subject_ids']) && $_REQUEST['UserSearch']['subject_ids'] != ''){
            $query_user = StudentTutorSubject::find()->where(['subject_id'=>$_REQUEST['UserSearch']['subject_ids']])->all();
            $j = 0;
            foreach ($query_user as $value_user) {
                    $user_array_by[$j] = $value_user['user_id'];
                    $j++;
                } 
            if(empty($user_array_by)){$user_array_by = 0;}
            $filter_expertise_by = $user_array_by;
        }
        
        $query->andFilterWhere(['!=', 'status', 2]);
        $query->andFilterWhere(['=', 'role', $role]);
        $query->andFilterWhere([
            'id' => $filter_expertise_by,
            'status' => $this->status,
            'created_by' => $this->created_by,
//            'created_date' => $this->created_date,
            'modified_by' => $this->modified_by,
            'modified_date' => $this->modified_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'total_questions' => $this->total_questions,
            'total_referral_user' => $this->total_referral_user,
        ]);

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'profile_photo', $this->profile_photo])
            ->andFilterWhere(['like', 'role', $this->role])
            ->andFilterWhere(['like', 'user_last_login', $this->user_last_login]);

        return $dataProvider;
    }
}
