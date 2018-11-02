<?php
use yii\widgets\Pjax;
use richardfan\sortable\SortableGridView;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel app\models\TrainerVideoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Trainer Videos';
$this->params['breadcrumbs'][] = ['label' => 'Trainer Videos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(); ?><div class="trainer-video-index">
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

            'trainer_video_id',
            'title:ntext',
            'description:ntext',
            'workout_type_id',
            'price',
            // 'video_file',

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
