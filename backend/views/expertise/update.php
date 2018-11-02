<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Expertise */

$this->title = Yii::t('app', 'Update {modelClass} ', [
    'modelClass' => 'Expertise',
]) ;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expertises'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="expertise-update">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->
<div class="panel panel-default">
		<div class="panel-body">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
