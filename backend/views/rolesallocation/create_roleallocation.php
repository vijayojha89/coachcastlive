<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Rolesallocation */

$this->title = 'Roles Allocation';
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['roles/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::a(Yii::t('app', 'Refresh Actions'), ['roles/access'], ['class' => 'btn btn-success']) ?>
<div class="rolesallocation-create">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->
<div class="panel panel-default">
		<div class="panel-body">
    <?= $this->render('_form', [
        'model' => $model,
        'allauthItems'=>$allauthItems
    ]) ?>
                </div>
</div>
</div>
