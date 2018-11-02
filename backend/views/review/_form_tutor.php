<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\time\TimePicker;
use dosamigos\tinymce\TinyMce;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use kartik\rating\StarRating;
$mnl = new \common\components\MasterComponent();
$data = $mnl->get_review_option('tutor');
/* @var $this yii\web\View */
/* @var $model common\models\Review */
/* @var $form yii\widgets\ActiveForm */
$question = common\models\Question::findOne($model->question_id);
$model->question_id = $question['title'];
?>
<div class="review-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
  <?= $form->field($model, 'question_id')->textInput(['disabled' => true]) ?>
    <div class="cl"></div>
    <?php 
      echo $form->field($model, 'posted_for')
      ->dropDownList(ArrayHelper::map(\common\models\User::find()->where(['role'=>'tutor','status'=>1])->asArray()->all(), 'id', 'first_name'),
        ['prompt'=>'Select...','disabled'=>($model->isNewRecord)? FALSE:TRUE]);
                      ?>
    <div class="cl"></div>
    <?php echo $form->field($model, 'rating')->widget(StarRating::classname(), [
                'pluginOptions' => ['step' => 1,'size'=>'small']
            ]);?>
    <div class="cl"></div>
    <?php 
      echo $form->field($model, 'review_opt')
      ->dropDownList(ArrayHelper::map(common\models\ReviewOption::find()->where(['role'=>'tutor','status'=>1])->asArray()->all(), 'review_option_id', 'option'),['prompt'=>'Select...']);
                      ?>
    <div class="cl"></div>
    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <div class="form-group" id="button_update_delete">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['review/tutor-review'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
    