<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use common\components\GeneralComponent;

$gnl = new GeneralComponent();

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
//echo "<pre>";
//print_r(Yii::$app->user->identity);

?>
<?php
    $initialPreview = [];

    if (!empty($model->cv_doc)) {
        $pathImg = $gnl->image_not_found_hb($model->cv_doc, 'cv_doc',1);
        $initialPreview[] = 
                '<div class="file-preview-other filecsv" ><span class="file-other-icon">
                 <a target="_blank" class="file-preview-other" data-pjax="false" href="'. $pathImg.'">
                  <i class="glyphicon glyphicon-file"></i>
                 </a> 
                 </span></div>'   ;
    }
//    print_r($initialPreview);exit;
    ?> 

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="form-group"><h3>Personal Details</h3></div>
    <div class="cl"></div>
                    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'mobile_no')->textInput(['maxlength' => true]) ?>
                    <?php if ($model->isNewRecord) { ?>
                    <?= $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'autocomplete' => false]) ?>
                    <?php } else { ?>
                    <?= $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'autocomplete' => false, "disabled" => "disabled"]) ?>
                    <?php } ?>  
                    <?php if ($model->isNewRecord) { ?>
                    <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'confirm_password')->passwordInput(['maxlength' => true]) ?>  
                    <?php } ?>
                    <?php
                    $model->expertise_ids = $modelExpertiseSelected;
                    echo $form->field($model, 'expertise_ids')
                            ->label('Expertise')
                            ->widget(Select2::classname(), [
                                'data' => ['Select Name' => ArrayHelper::map(common\models\Subject::find()->where(['status' => 1])
                                            ->asArray()->all(), 'subject_id', 'name')],
                                'options' => ['multiple' => true, 'placeholder' => 'Select expertise'],
                    ]);
                    ?>  
                    <?php // $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>
<div class="cl"></div>
    <?php



if($model->mobile_verified == 1)
{ ?>
     <div class="form-group field-user-mobile_verified">
                        <input name="User[mobile_verified]" value="1" type="hidden">
                        <label><input type="checkbox" checked disabled="disabled"> Mobile Verified</label>
                    </div>
    
<?php }else{ 
    
    echo $form->field($model, 'mobile_verified')->checkbox();
    
}
?>

 <?php  if($model->email_verified == 1){ ?>

     <div class="form-group field-user-email_verified">
                        <input name="User[email_verified]" value="1" type="hidden">
                        <label><input type="checkbox" checked disabled="disabled"> Email Verified</label>
                    </div>
    
 <?php }else{
         
                  echo $form->field($model, 'email_verified')->checkbox();
    
    } ?>
<?php
    echo $form->field($model, 'cv_doc')->widget(FileInput::classname(), [
                            'model' => $model,
                            'attribute' => 'cv_doc',
                            'options' => [
                                'multiple' => FALSE,
                                 'accept' => '.doc, .docx, .pdf',
                                 'overwriteInitial' => false,
                            ], 
                            'pluginOptions' => [
                                'showPreview' => TRUE,
                                'showCaption' => TRUE,
                                'showUpload' => false,
                                'showRemove' => false,
                                'browseClass' => 'btn btn-primary btn-sm',
                                'browseLabel' => 'Upload',
                                'browseIcon' => '<i class="glyphicon glyphicon-file"></i>',
                                'layoutTemplates' => ['footer' => ''],
                                'initialPreview' => $initialPreview,
                                'previewFileType' => 'doc'
                            ]
                        ])->label('Upload CV (Optional)');
    ?>    
    
     <?= $form->field($model, 'user_type')->hiddenInput(['maxlength' => true])->label(false) ?>
    
    <div class="form-group" id="button_update_delete">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>&nbsp;&nbsp;&nbsp;
        <?php echo Html::a(Yii::t('app', 'Cancel'), ['user/tutor'], ['class' => 'btn btn-danger']);
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

