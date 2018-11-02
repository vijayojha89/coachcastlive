<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Expertise */

$this->title = Yii::t('app', 'Create Expertise');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Expertises'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expertise-create">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->
<div class="panel panel-default">
		<div class="panel-body">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
                </div>
</div>
</div>
