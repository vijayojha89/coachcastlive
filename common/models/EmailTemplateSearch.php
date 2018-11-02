<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\EmailTemplate;

/**
 * EmailTemplateSearch represents the model behind the search form about `common\models\EmailTemplate`.
 */
class EmailTemplateSearch extends EmailTemplate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['emailtemplate_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['emailtemplate_name', 'emailtemplate_subject', 'emailtemplate_body', 'created_date', 'modified_date'], 'safe'],
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
        $query = EmailTemplate::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => PAGE_SIZE),
            'sort'=> ['defaultOrder' => ['emailtemplate_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['!=', 'status', 2]);
        $query->andFilterWhere([
            'emailtemplate_id' => $this->emailtemplate_id,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_date' => $this->created_date,
            'modified_by' => $this->modified_by,
            'modified_date' => $this->modified_date,
        ]);

        $query->andFilterWhere(['like', 'emailtemplate_name', $this->emailtemplate_name])
            ->andFilterWhere(['like', 'emailtemplate_subject', $this->emailtemplate_subject])
            ->andFilterWhere(['like', 'emailtemplate_body', $this->emailtemplate_body]);

        return $dataProvider;
    }
}
