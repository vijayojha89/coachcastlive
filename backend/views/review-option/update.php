<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ReviewOption */

$this->title = Yii::t('app', 'Update {modelClass} ', [
    'modelClass' => 'Review Option',
]) ;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Review Options'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="review-option-update">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->
<div class="panel panel-default">
		<div class="panel-body">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
