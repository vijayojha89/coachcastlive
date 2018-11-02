<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Roles */

$this->title = 'Update Roles ' ;
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="roles-update">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->
<div class="panel panel-default">
		<div class="panel-body">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
