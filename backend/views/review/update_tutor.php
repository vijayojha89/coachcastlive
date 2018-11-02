<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Review */

$this->title = Yii::t('app', 'Update {modelClass} ', [
    'modelClass' => 'Review',
]) ;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tutor Reviews'), 'url' => ['tutor-review']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="review-update">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->
<div class="panel panel-default">
		<div class="panel-body">
    <?= $this->render('_form_tutor', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
