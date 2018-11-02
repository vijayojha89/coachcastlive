<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Qualification */

$this->title = Yii::t('app', 'Create Qualification');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Qualifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="qualification-create">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->
<div class="panel panel-default">
		<div class="panel-body">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
                </div>
</div>
</div>
