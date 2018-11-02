<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Rolesallocation */

$this->title = 'Update Rolesallocation ' ;
$this->params['breadcrumbs'][] = ['label' => 'Rolesallocations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rolesallocation-update">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->
<div class="panel panel-default">
		<div class="panel-body">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
