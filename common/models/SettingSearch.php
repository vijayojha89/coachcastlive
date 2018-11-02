<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Setting;

/**
 * SettingSearch represents the model behind the search form about `app\models\Setting`.
 */
class SettingSearch extends Setting
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['setting_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [[ 'setting_google', 'setting_facebook', 'setting_linkedin', 'setting_yahoo', 'setting_logo_image', 'setting_favicon_image', 'created_date', 'modified_date'], 'safe'],
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
        $query = Setting::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => PAGE_SIZE),
            'sort'=> ['defaultOrder' => ['setting_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['!=', 'status', 2]);
        $query->andFilterWhere([
            'setting_id' => $this->setting_id,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_date' => $this->created_date,
            'modified_by' => $this->modified_by,
            'modified_date' => $this->modified_date,
        ]);

        $query
            ->andFilterWhere(['like', 'setting_google', $this->setting_google])
            ->andFilterWhere(['like', 'setting_facebook', $this->setting_facebook])
            ->andFilterWhere(['like', 'setting_linkedin', $this->setting_linkedin])
            ->andFilterWhere(['like', 'setting_yahoo', $this->setting_yahoo])
            ->andFilterWhere(['like', 'setting_logo_image', $this->setting_logo_image])
            ->andFilterWhere(['like', 'setting_favicon_image', $this->setting_favicon_image]);

        return $dataProvider;
    }
}
