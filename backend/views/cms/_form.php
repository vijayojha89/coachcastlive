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
use common\components\GeneralComponent;

$gnl = new GeneralComponent();
/* @var $this yii\web\View */
/* @var $model common\models\Cms */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .field-cms-content{
        width:100%!important;
    }
    .file-preview-image{
        max-width: 250px;
    }
    .fileinput-remove{
        display: none;
    }
</style> 
<?php
//echo yii\helpers\Url::base();exit;
$this->registerJsFile(yii\helpers\Url::base() . '/js/tinymce/tinymce.min.js', [yii\web\JqueryAsset::className()]);

$this->registerJs(" 
 tinymce.init({
    menubar:false,
    selector: '#cms-content',   
    plugins: [
         'advlist autolink link image lists charmap print preview hr anchor pagebreak',
         'searchreplace wordcount visualblocks visualchars code insertdatetime media nonbreaking',
         'table contextmenu directionality emoticons paste textcolor filemanager'
    ],
    image_advtab: true,
    toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | fontselect fontsizeselect styleselect forecolor backcolor | link unlink anchor | image | preview code',
    verify_html:false, 
    convert_urls:false,
 
  });       

");
?>             
<div class="cms-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    
    
    <?php 
    
    $not_required_image = array('home','careers','privacy','terms');
            
    if(!in_array($model->page_key, $not_required_image))        
    {        
   
    ?>
    
    <?php
    $initialPreview = [];

    if (!empty($model->header_image)) {
        $pathImg = $gnl->image_not_found_hb($model->header_image, 'header_image');
        
        $pathImg = str_replace('thumb/','', $pathImg);
        $initialPreview[] = Html::a(Html::img($pathImg, ['class' => 'file-preview-image', 'width' => '100%', 'height' => '100%']), $pathImg, ['class' => 'image-preview-newtab', 'target' => '_blank']);
    }
    ?>                    
    <div class="cl"></div>
    <div class="form-group field-cms-header_image">
        <?php
        echo '<label class="control-label">Header Image<small> (Recommended size : 1600x500)</small></label>';
        ?>  
        <?=
        $form->field($model, "header_image")->label(false)->widget(kartik\file\FileInput::classname(), [
            'options' => [
                'multiple' => false,
                'accept' => '.jpg, .jpeg, .png',
                'class' => 'optionvalue-img'
            ],
            'pluginOptions' => [
                'previewFileType' => 'image',
                'showUpload' => false,
                'showRemove' => false,
                'showCaption' => false,
                'browseClass' => 'btn btn-primary btn-sm',
                'browseLabel' => ' Pick image',
                'browseIcon' => '<i class="glyphicon glyphicon-picture"></i>',
                'previewSettings' => [
//                    'image' => ['width' => '640px', 'height' => '100px']
                ],
                'initialPreview' => $initialPreview,
                'layoutTemplates' => ['footer' => '']
            ]
        ])
        ?>
    </div>
    
    <?php } ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 15]) ?>

    <input name="image" type="file" id="upload" class="hidden" onchange="">

    <div class="form-group" id="button_update_delete">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['cms/index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
