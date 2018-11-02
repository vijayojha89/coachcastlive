<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;



/* @var $this yii\web\View */
/* @var $searchModel common\models\AuthItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Auth Items');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(); ?><div class="auth-items-index">
<div class="panel panel-default">
    <div class="panel-body">


        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>                    

        <div class="form-group">
        <label class="control-label" for="tickets-project_id">Roles</label>
            <select id="tickets-project_id" class="form-control" name="role">
           <?php                foreach ($allRoles as $role){?>
            <option value="<?php echo $role['id'];?>" selected=""><?php echo $role['role_name'];?></option>
           <?php }?>
            </select>

        <div class="help-block"></div>
        </div>  
         <?= $form->field($model, 'auth_items_name')->textInput() ?>
       
        <?php ActiveForm::end(); ?>

    </div>
    </div>
</div>

<?php $this->registerJs(
                
         '
             function active_deactive(url){
             
    $.ajax({
            url: url, 
            success: function(result){
            //alert(result);
    }});

  }
            
            ',
        \yii\web\VIEW::POS_HEAD); ?><?php Pjax::end(); ?>