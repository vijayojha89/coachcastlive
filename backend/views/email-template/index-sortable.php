<?php
use yii\widgets\Pjax;
use richardfan\sortable\SortableGridView;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel common\models\EmailTemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Email Templates');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Email Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(); ?><div class="email-template-index">
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

            'emailtemplate_id:email',
            'emailtemplate_name:email',
            'emailtemplate_subject:ntext',
            'emailtemplate_body:ntext',

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
