<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('app', 'Update {modelClass} ', [
    'modelClass' => 'Profile',
]) ;
$this->params['breadcrumbs'][] = Yii::t('app', 'Update Profile');
?>
<div class="user-update">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->
<div class="panel panel-default">
		<div class="panel-body">
    <?= $this->render('_form_email', [
        'model' => $model,       
    ]) ?>
</div>
</div>
</div>
