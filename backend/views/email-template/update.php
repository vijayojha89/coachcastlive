<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\EmailTemplate */

$this->title = Yii::t('app', 'Update {modelClass} ', [
    'modelClass' => 'Email Template',
]) ;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Email Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="email-template-update">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->
<div class="panel panel-default">
		<div class="panel-body">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
