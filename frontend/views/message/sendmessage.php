<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Message */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Send Message';

?>


<?php $this->beginPage(); ?>
<?php $this->beginBody(); ?>

<div class="modal-header">
         <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Message</h4>
      </div>


<div class="modal-body">
    <div id="messagebox" class="mainbox">
        

        
        
        <?php //$form = ActiveForm::begin();
        
        
        $form = ActiveForm::begin([
    'id' => 'message-form',
    'enableClientValidation' => true,
    'options' => [
        'validateOnSubmit' => true,
        'class' => 'form'
    ],
])
                
                
                ?>

           <?= $form->field($model, 'message_text')->textarea(['rows' => 6]) ?>

       <div class="form-group">
        <?= Html::submitButton('Send', ['class' => 'btn']) ?>
    </div>

            

        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php $this->endBody(); ?>
<?php $this->endPage(); ?>