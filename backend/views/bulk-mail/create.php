<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BulkMail */

$this->title = Yii::t('app', 'Create Bulk Mail');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bulk Mails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bulk-mail-create">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->
<div class="panel panel-default">
		<div class="panel-body">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
                </div>
</div>
</div>
