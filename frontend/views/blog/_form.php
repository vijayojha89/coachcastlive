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
/* @var $model frontend\models\Blog */
/* @var $form yii\widgets\ActiveForm */
$gnl = new \common\components\GeneralComponent(); 
?>
<div class="blog-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?php //$form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'description')->widget(TinyMce::className(), [
    'options' => ['rows' => 15],
    'clientOptions' => [
        'menubar'=>false,
        'statusbar'=> false,
        'mode'=>'exact',
        'plugins' => [
                           "autolink link image print preview anchor", 
                          //"advlist autolink lists link image charmap print preview hr anchor pagebreak",
                          //"searchreplace wordcount visualblocks visualchars code",
                          //"insertdatetime nonbreaking save table contextmenu directionality",
                          //"emoticons template paste textcolor colorpicker textpattern preview"
                     ],
        'paste_data_images'=> true,
       // 'toolbar1' => "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code | preview",
        'image_advtab'=> true,
        'forced_root_block'=>'',
        'relative_urls'=>false,
        'remove_script_host'=> false,
        'cleanup_on_startup'=>false,
        'trim_span_elements'=>false,
        'verify_html'=>false,
        'cleanup'=>false,
        'convert_urls'=>false,
        'valid_elements'=> "@[class],p[style],h3,h4,h5,h6,a[href|target],i[class],strong/b,div[align],br,table,tbody,thead,tr,td,ul,ol,li,img[src]",
        'apply_source_formatting' => false,               
        'verify_html' => false, 
        'allow_html_in_named_anchor'=> true,
        'plugin_preview_height'=> 600,
        'plugin_preview_width'=> 1100,
        
        //'content_css' => Yii::$app->params['url'].'css/font-css.css,'.Yii::$app->params['url'].'css/font-awesome.min.css,'.Yii::$app->params['url'].'css/bootstrap.min.css,'.Yii::$app->params['url'].'css/bootstrap-material.css,'.Yii::$app->params['url'].'css/style.css,'.Yii::$app->params['url'].'css/custom.css,'.Yii::$app->params['url'].'css/lightslider.css',

        'file_picker_callback'=> new yii\web\JsExpression("function(callback, value, meta) {
            
            if (meta.filetype == 'image') {
              $('#upload').trigger('click');
              $('#upload').on('change', function() {
                var file = this.files[0];
                var reader = new FileReader();
                reader.onload = function(e) {
                  callback(e.target.result, {
                    alt: ''
                  });
                };
                reader.readAsDataURL(file);
              });
            }
    }"),
    
         
    ]
])->label($title);?>
    <input name="image" type="file" id="upload" class="hidden" onchange="">

    <div class="row">

<div  class="col-sm-12">
        <div class="img_users">
                                           
         <?php echo $form->field($model, 'blog_image', [
         'template' => '{label}<span class="fileupload">Upload blog image</span> {input}{error}{hint}'
         ])->fileInput(['autofocus' => true,'value'=>'','class'=>'form-control']) ;
         ?>
        <!-- <img id="preview" class="mCS_img_loaded" src="<//?php echo $gnl->image_not_found_hb($model->profile_photo, 'profile_photo', 1); ?>" alt="" width="200" height="200">  -->
                <img id="preview_blog_image_0" class="img-border upload-preview" src="<?php echo $gnl->image_not_found_hb($model->blog_image, 'blog_image',1); ?>" />
        </div>
        </div>



    <!-- <div class="col-sm-2">
    <//?= $form->field($model, 'blog_image')->fileInput() ?>
    </div>
     <div class="col-sm-3">
         <img id="preview_blog_image_0" class="img-border upload-preview" src="<//?php echo $gnl->image_not_found_hb($model->blog_image, 'blog_image',1); ?>" />
     </div> -->
    </div>
    
    <div class="form-group pro-bottom-btn" id="button_update_delete">
        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Update', ['class' => $model->isNewRecord ? 'btn' : 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['blog/index'], ['class' => 'btn btn-dark']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    var URL = window.URL || window.webkitURL;

    var input_0 = document.querySelector('#blog-blog_image');
    var preview_0 = document.querySelector('#preview_blog_image_0');

    input_0.addEventListener('change', function() {
        preview_0.src = URL.createObjectURL(this.files[0]);
    });

    preview_0.addEventListener('load', function() {
        URL.revokeObjectURL(this.src);

    });
</script>