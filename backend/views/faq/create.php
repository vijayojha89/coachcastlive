<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Faq */

$this->title = Yii::t('app', 'Create FAQ');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'FAQs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-create">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->
<div class="panel panel-default">
		<div class="panel-body">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
                </div>
</div>
</div>
