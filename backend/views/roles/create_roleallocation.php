<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Rolesallocation */

$this->title = 'Roles Allocation';
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['roles/index']];
$this->params['breadcrumbs'][] = 'View Actions For Roles';
?>

<?= Html::a(Yii::t('app', 'Refresh Actions'), ['roles/access?id='.$_REQUEST['id']], ['class' => 'btn btn-success']).'   '.
 Html::a('<i">Delete Actions</i>', ['auth-items/index'], ['class' => 'btn btn-success']) .'   '.
 Html::a('<i">Back</i>', ['index'], ['class' => 'btn btn-info']) ; ?>
<div class="rolesallocation-create">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->
<div class="panel panel-default">
		<div class="panel-body">
    <?= $this->render('_form_roleallocation', [
        'model' => $model,
        'allauthItems'=>$allauthItems
    ]) ?>
                </div>
</div>
</div>
