<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ReviewOption;

/**
 * ReviewOptionSearch represents the model behind the search form about `common\models\ReviewOption`.
 */
class ReviewOptionSearch extends ReviewOption
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['review_option_id', 'status'], 'integer'],
            [['option', 'role'], 'safe'],
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
        $query = ReviewOption::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => PAGE_SIZE),
            'sort'=> ['defaultOrder' => ['role'=>SORT_DESC,'review_option_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['!=', 'status', 2]);
                $query->andFilterWhere([
            'review_option_id' => $this->review_option_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'option', $this->option])
            ->andFilterWhere(['like', 'role', $this->role]);

        return $dataProvider;
    }
}
