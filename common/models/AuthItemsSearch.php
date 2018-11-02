<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AuthItems;

/**
 * AuthItemsSearch represents the model behind the search form about `common\models\AuthItems`.
 */
class AuthItemsSearch extends AuthItems
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auth_items_id', 'auth_items_name', 'auth_items_type', 'auth_items_action', 'status', 'created_by', 'modified_by'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
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
        $query = AuthItems::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => PAGE_SIZE),
            'sort'=> ['defaultOrder' => ['auth_items_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['!=', 'status', 2]);
        $query->andFilterWhere([
            'auth_items_id' => $this->auth_items_id,
            'auth_items_name' => $this->auth_items_name,
            'auth_items_type' => $this->auth_items_type,
            'auth_items_action' => $this->auth_items_action,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_date' => $this->created_date,
            'modified_by' => $this->modified_by,
            'modified_date' => $this->modified_date,
        ]);

        return $dataProvider;
    }
}
