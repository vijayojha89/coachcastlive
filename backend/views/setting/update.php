<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Setting */

$this->title = Yii::t('app', 'Update {modelClass} ', [
    'modelClass' => 'Setting',
]) ;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Settings'), ];
//$this->params['breadcrumbs'][] = ['label' => $model->setting_id, 'url' => ['view', 'id' => $model->setting_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="setting-update">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->
<div class="panel panel-default">
		<div class="panel-body">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
