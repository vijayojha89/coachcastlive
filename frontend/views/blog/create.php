<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Blog */

$this->title = 'Add New Blog';
echo $this->render('//trainer/_trainer_header.php');
?>

<div id="content" class="inner_container">
  <section class="contentsection">
    <div class="container">
      
         
         <div class="row">
          <div class="col-xs-6">
           <h1 class="maintitle">Add New Blog</h1>
          </div>
          
          
     </div>
    </div>   
    
      <div class="container">
      <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
      
      
  </section>
</div>