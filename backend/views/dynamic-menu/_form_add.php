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
/* @var $this yii\web\View */
/* @var $model common\models\DynamicMenu */
/* @var $form yii\widgets\ActiveForm */
$action_id = common\models\DynamicMenu::findOne(['id'=> $model->id]);
$actions_array = explode(',', $action_id['actions']);
$d = ArrayHelper::map(\common\models\AuthItems::find()
        ->where(['parent_auth_item_id' => $model->controller_id,'status'=>1])->all(), 'auth_items_id', 'auth_items_name' );
$v = ArrayHelper::getColumn(\common\models\AuthItems::find()
                                        ->where(['parent_auth_item_id' => $model->controller_id,'status'=>1,'auth_items_id'=>$actions_array])
                                        ->asArray()
                                        ->all(), 'auth_items_id');
$model->actions = $v;
?>
<div class="dynamic-menu-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php
        echo $form->field($model, 'menu_id')
                   ->dropDownList(ArrayHelper::map(\common\models\DynamicMenu::find()->where(['type'=>2,'status'=>1])->asArray()->all(), 'id', 'label'),['prompt'=>'Select...']);
    ?> 
    <?php
        echo $form->field($model, 'controller_id')
                   ->dropDownList(ArrayHelper::map(\common\models\AuthItems::find()->where(['parent_auth_item_id'=>0])->asArray()->all(), 'auth_items_id', 'auth_items_name'),['prompt'=>'Select...']);
    ?> 
       <?php
                            echo $form->field($model, 'actions')->widget(DepDrop::classname(), [
                            'data'=>$d,
                            'options' => ['multiple' => true],
                            'pluginOptions'=>[
                                'depends'=>['dynamicmenu-controller_id'],
                                'placeholder'=>'Select...',
                                'url'=>  \yii\helpers\Url::to(['dynamic-menu/data'])
                            ]
                        ]);
                    ?>
    <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'index_array')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>
    
    <?php // $form->field($model, 'key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <div class="form-group" id="button_update_delete">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['dynamic-menu/index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
 function dropdown(val){

               if(val == 2){
               $('#dynamicmenu-url').val('#');
               }
               else{
               $('#dynamicmenu-url').val('');    
               }
                    }
</script>
    