<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Cms */

$this->title = Yii::t('app', 'Create Cms');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-create">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->
<div class="panel panel-default">
		<div class="panel-body">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
                </div>
</div>
</div>
