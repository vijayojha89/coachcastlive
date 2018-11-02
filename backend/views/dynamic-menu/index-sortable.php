<?php
use yii\widgets\Pjax;
use richardfan\sortable\SortableGridView;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel common\models\DynamicMenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Dynamic Menus');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dynamic Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(); ?><div class="dynamic-menu-index">
<div class="panel panel-default">
		<div class="panel-body">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php $dataProvider->pagination  = false;
        echo  SortableGridView::widget([ 
    'dataProvider' => $dataProvider,
    'sortUrl' => Url::to(['sortItem']),
    'sortingPromptText' => 'Sorting...',
    'failText' => 'Fail to sort',
        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
//            'controller_id',
            'label',
//            'url:url',
//            'index_array',
            // 'sort',
            // 'icon',
            // 'key',
            // 'description',

        ],
    ]); ?>
                </div>
                </div>
</div>

<style>
    .summary{
        display: none;
    }
</style>
