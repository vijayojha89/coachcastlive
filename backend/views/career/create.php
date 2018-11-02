<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Career */

$this->title = Yii::t('app', 'Create Career');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Careers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="career-create">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->
<div class="panel panel-default">
		<div class="panel-body">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
                </div>
</div>
</div>
