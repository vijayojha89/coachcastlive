<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DynamicMenu;

/**
 * DynamicMenuSearch represents the model behind the search form about `common\models\DynamicMenu`.
 */
class DynamicMenuSearch extends DynamicMenu
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'controller_id', 'sort', 'status', 'created_by', 'modified_by'], 'integer'],
            [['label', 'url', 'index_array', 'icon', 'key', 'description', 'created_date', 'modified_date'], 'safe'],
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
        $query = DynamicMenu::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => array('pageSize' => PAGE_SIZE),
            'sort'=> ['defaultOrder' => ['dynamic_menu_id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['!=', 'status', 2]);
                $query->orderBy('sort');
                $query->andFilterWhere([
            'id' => $this->id,
            'controller_id' => $this->controller_id,
            'sort' => $this->sort,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_date' => $this->created_date,
            'modified_by' => $this->modified_by,
            'modified_date' => $this->modified_date,
        ]);

        $query->andFilterWhere(['like', 'label', $this->label])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'index_array', $this->index_array])
            ->andFilterWhere(['like', 'icon', $this->icon])
            ->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
