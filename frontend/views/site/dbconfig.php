<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="container">
  <div class="left-right-content">
    <div class="col-md-12">
      <div class="row">
        <div class="cl"></div>
      </div>
    </div>
  </div>
  <div class="cl"></div>
  <div class="founder-form">
   
   <div class="row">
    <?php
        $form = \yii\widgets\ActiveForm::begin([
                    'id' => 'db-form',
                    'class'=>'dbform'
        ]);
        ?>
     <div class="col-md-6">   
    <?= $form->field($model, 'dbhost')->textInput(['maxlength' => 50])->label('Host Name') ?>
    </div><div class="cl"></div>
    <div class="col-md-6">
    <?= $form->field($model, 'dbname')->textInput(['maxlength' => 50])->label('Database Name') ?>
    </div><div class="cl"></div>
    <div class="col-md-6">
    <?= $form->field($model, 'username')->textInput(['maxlength' => 50])->label('User Name') ?>
    </div><div class="cl"></div>
    <div class="col-md-6">
    <?= $form->field($model, 'password')->passwordInput(['rows' => 6])->label('Password') ?>
    </div><div class="cl"></div>
    <div class="col-md-12 btnbox">
    				<?= yii\helpers\Html::submitButton('Submit', ['class' => 'btn btn-primary submitbtn', 'name' => 'db-button']) ?>
    </div>
				<?php \yii\widgets\ActiveForm::end(); ?>
  </div>
  </div>
</div>

