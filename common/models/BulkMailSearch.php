<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BulkMail;

/**
 * BulkMailSearch represents the model behind the search form about `common\models\BulkMail`.
 */
class BulkMailSearch extends BulkMail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bulk_mail_id', 'user_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['type', 'subject', 'body', 'created_date', 'modified_date'], 'safe'],
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
        $query = BulkMail::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => PAGE_SIZE),
            'sort'=> ['defaultOrder' => ['bulk_mail_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['!=', 'status', 2]);
                $query->andFilterWhere([
            'bulk_mail_id' => $this->bulk_mail_id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_date' => $this->created_date,
            'modified_by' => $this->modified_by,
            'modified_date' => $this->modified_date,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'body', $this->body]);

        return $dataProvider;
    }
}
