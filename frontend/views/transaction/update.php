<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Transaction */

$this->title = 'Update Transaction ' ;
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="transaction-update">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->
<div class="panel panel-default">
		<div class="panel-body">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
</div>
</div>
