<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\TrainerClass;

/**
 * TrainerClassSearch represents the model behind the search form about `frontend\models\TrainerClass`.
 */
class TrainerClassSearch extends TrainerClass
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trainer_class_id', 'workout_type_id', 'created_by', 'modified_by', 'status'], 'integer'],
            [['title', 'description', 'class_image', 'created_date', 'modified_date', 'start_date', 'end_date', 'time'], 'safe'],
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
        $query = TrainerClass::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => PAGE_SIZE),
            'sort'=> ['defaultOrder' => ['trainer_class_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['!=', 'status', 2]);
                $query->andFilterWhere([
            'trainer_class_id' => $this->trainer_class_id,
            'workout_type_id' => $this->workout_type_id,
            'price' => $this->price,
            'created_by' => $this->created_by,
            'created_date' => $this->created_date,
            'modified_by' => $this->modified_by,
            'modified_date' => $this->modified_date,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'time' => $this->time,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'class_image', $this->class_image]);

        return $dataProvider;
    }
}
