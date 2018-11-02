<?php
use yii\widgets\Pjax;
use richardfan\sortable\SortableGridView;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TrainerClassSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Trainer Classes';
$this->params['breadcrumbs'][] = ['label' => 'Trainer Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(); ?><div class="trainer-class-index">
<div class="panel panel-default">
		<div class="panel-body">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php $dataProvider->pagination  = false;
        echo  SortableGridView::widget([ 
    'dataProvider' => $dataProvider,
    'sortUrl' => Url::to(['sortItem']),
    'sortingPromptText' => 'Sorting...',
    'failText' => 'Fail to sort',
        
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'trainer_class_id',
            'title:ntext',
            'description:ntext',
            'workout_type_id',
            'price',
            // 'start_date',
            // 'end_date',
            // 'time',

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
