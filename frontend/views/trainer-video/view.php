<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TrainerVideo */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Trainer Videos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trainer-video-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->trainer_video_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->trainer_video_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'trainer_video_id',
            'title:ntext',
            'description:ntext',
            'workout_type_id',
            'price',
            'video_image',
            'video_file',
            'created_by',
            'created_date',
            'modified_by',
            'modified_date',
            'status',
        ],
    ]) ?>

</div>

