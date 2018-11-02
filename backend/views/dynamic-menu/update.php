<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DynamicMenu */

$this->title = Yii::t('app', 'Update {modelClass} ', [
    'modelClass' => 'Dynamic Menu',
]) ;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dynamic Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="dynamic-menu-update">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->
<div class="panel panel-default">
		<div class="panel-body">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
