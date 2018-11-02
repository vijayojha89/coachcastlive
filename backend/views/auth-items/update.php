<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AuthItems */

$this->title = Yii::t('app', 'Update {modelClass} ', [
    'modelClass' => 'Auth Items',
]) ;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auth Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->auth_items_id, 'url' => ['view', 'id' => $model->auth_items_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="auth-items-update">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->
<div class="panel panel-default">
		<div class="panel-body">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
