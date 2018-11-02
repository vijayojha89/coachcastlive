<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Rolesallocation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rolesallocation-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="col-lg-12">
        <div class="md-col-12">
            <div class="form-group">
                <label class="control-label" for="tickets-project_id"><?php 
                 $queryRole = "SELECT * from roles where id=".\common\components\GeneralComponent::decrypt($_REQUEST['id'])." and status = 1";
                 $roleName= Yii::$app->db->createCommand($queryRole)->queryOne();
               ?>
                    <h2><?php echo $roleName['role_name'];?></h2></label>
                <div class="help-block"></div>
            </div> 
        </div>
    </div>
    <?php
   
    $resCheckforEdit = yii\helpers\ArrayHelper::getColumn(\common\models\Rolesallocation::find()->select('action_id')->where(["role_id"=>\common\components\GeneralComponent::decrypt($_REQUEST['id'])])->All(), 'action_id');
    foreach ($allauthItems as $authitems){
    ?>
     <div class="col-lg-12">
        <div class="col-md-6">
            <table>
            <tr>
        <th>
            <?php echo $authitems['auth_items_name'];?>
        </th>
            </tr>
            
         <?php
            $query = "SELECT * from auth_items where parent_auth_item_id=".$authitems['auth_items_id']." and status = 1";
            $allauthItemsactions = Yii::$app->db->createCommand($query)->queryAll();
            foreach ($allauthItemsactions as $authitemsactions){
            if(count($resCheckforEdit)>0){
                if(in_array($authitemsactions['auth_items_id'], $resCheckforEdit)){
                    $checked = "checked='checked'";
                }else{
                    $checked = "";
                }
            }else{
                $checked = "";
            }
         ?>
            <tr>
        <td>
            <input type="checkbox" <?php echo $checked;?> name="<?php echo 'actions['.$authitems['auth_items_id'].']['.$authitemsactions['auth_items_id'].']';?>" value="0"><?php echo $authitemsactions['auth_items_name'];?>
        </td>    
            </tr>
            <?php }?>
        
     </table>
            <?php echo $form->field($model, 'role_id')->hiddenInput(['value' => \common\components\GeneralComponent::decrypt($_REQUEST['id'])])->label(false);?>
        </div>
    </div>
    <?php }?>
    <div class="cl"></div> 
    <br>
    <div class="form-group" id="button_update_delete">
        
<?= Html::submitButton(count($resCheckforEdit)>0 ? 'Update' : 'Add', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['roles/index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
