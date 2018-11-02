<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
//echo "<pre>";
//print_r(Yii::$app->user->identity);

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
     
    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
   
    <?php if ($model->isNewRecord) { ?>
        <?= $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'autocomplete' => false]) ?>
    <?php } else { ?>
        <?= $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['maxlength' => true, 'autocomplete' => false, "disabled" => "disabled"]) ?>
    <?php } ?>

    <?php if ($model->isNewRecord) { ?>
        <?= $form->field($model, 'password_hash')->passwordInput(['autocomplete' => false]) ?>

        <?= $form->field($model, 'confirm_password')->passwordInput(['autocomplete' => false]) ?>
    <?php } ?>

<div class="cl"></div>
<div class="image_setting img_block">
        <div class="upload_logo_file">
            <i class="glyphicon glyphicon-open-file"></i>
            <p>File Upload</p>
        </div><?php $gnl = new \common\components\GeneralComponent(); ?><?= $form->field($model, 'profile_photo')->fileInput() ?><img id="preview" src="<?php echo $gnl->image_not_found_hb( $model->profile_photo,'profile_photo'); ?>" height="100" />
    </div>
<div class="cl"></div>
<br /><br />

 <div class="cl"></div>
    <div class="panel panel-default container user_img_block" style="position:relative; float:left;">
        <div class="panel-heading container"> <strong> <span class="glyphicon glyphicon-th" style=""></span> Menu Access Permission</strong> </div>
            <div class="panel-body">
                <div class="form-group field-user-adminpermission">
                    <div class="row">
                        <?php
                         /*02032017*/
                        $connection = \Yii::$app->db;
                        $queryMenuList = $connection->createCommand('SELECT * FROM dynamic_menu WHERE type!=2 AND status = 1 AND role_id = 3');
                        $query = $queryMenuList->queryAll();
                       
                        
                        if(count($query) > 0){
                            //get selected menu id of operator
                            
                            $modelMenuselected = $connection->createCommand('SELECT menu_id FROM user_menu_access WHERE user_id="'.$model->id.'" AND status = 1');
                            $usersMenuselected = $modelMenuselected->queryAll();
                            //echo "<pre>";print_r($usersMenuselected);
                            //exit;
                            $selectedIds = array();
                            if(count($usersMenuselected) > 0){
                                
                                foreach ($usersMenuselected as $selectedValue){
                                    $selectedIds[] = $selectedValue['menu_id'];
                                }
                            }
                           
                            foreach ($query as $menuvalue){
                                if(in_array($menuvalue['id'], $selectedIds)){
                                    $checkedData = "checked";
                                }else{
                                    $checkedData = "";
                                }
                                
                                if($menuvalue['id'] ==1){ ?>
                                    
                        <input type="hidden" name="menuname[]" value="<?php echo $menuvalue['id'];?>">
                        
                                <?php }
                                else{
                            ?>
                                <div class="col-xs-6 col-sm-4">
                                    <label><input type="checkbox" id="businessinfo-taxi_service" name="menuname[]" value="<?php echo $menuvalue['id'];?>" <?php echo $checkedData;?>> <?php echo $menuvalue['label'];?></label>
                                </div>
                            <?php
                                }
                            
                            }
                        }else{
                            
                        }
                        ?>
                        
                        
                    </div>
                    
                </div>
            </div>
    </div>

    <div class="form-group" id="button_update_delete">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>&nbsp;&nbsp;&nbsp;
        <?php echo Html::a(Yii::t('app', 'Cancel'), ['user/sub-admin'], ['class' => 'btn btn-danger']);
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
//    (function() {

    var URL = window.URL || window.webkitURL;

    var input = document.querySelector('#user-profile_photo');
    var preview = document.querySelector('#preview');
    
    // When the file input changes, create a object URL around the file.
    input.addEventListener('change', function () {
        preview.src = URL.createObjectURL(this.files[0]);
    });
    
    // When the image loads, release object URL
    preview.addEventListener('load', function () {
        URL.revokeObjectURL(this.src);
        
       // alert('jQuery code here. W: ' + this.width + ', H: ' + this.height);
    });
</script>
