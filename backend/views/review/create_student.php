<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Review */

$this->title = Yii::t('app', 'Create Review');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Student Reviews'), 'url' => ['student-review']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="review-create">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->
<div class="panel panel-default">
		<div class="panel-body">
    <?= $this->render('_form_student', [
        'model' => $model,
    ]) ?>
                </div>
</div>
</div>
