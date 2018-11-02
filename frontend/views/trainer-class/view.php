<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\TrainerClass */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Trainer Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trainer-class-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->trainer_class_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->trainer_class_id], [
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
            'trainer_class_id',
            'title:ntext',
            'description:ntext',
            'workout_type_id',
            'price',
            'class_image',
            'created_by',
            'created_date',
            'modified_by',
            'modified_date',
            'status',
            'start_date',
            'end_date',
            'time',
        ],
    ]) ?>

</div>

