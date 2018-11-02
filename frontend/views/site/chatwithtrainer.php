<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Chat With Trainer';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php

$pagedata = Yii::$app->db->createCommand("SELECT * FROM cms WHERE page_key = 'chatwithtrainer'")->queryOne();

?>

<?php if($pagedata['header_image']) { ?>
<div class="cmsbanner" style="background-color: black;height:350px;">
    
     <h1><?=$pagedata['title'];?></h1>
</div>

<?php } ?>



<section class="brdcum-section">
    <div class="container" style="padding:0px;">  
         <ul class="brdcum">
            	<li><a href="<?php echo Yii::$app->homeUrl;?>">Home</a></li>
                <li>Chat With A Trainer</li>
            </ul>
        
      
</div>
</section>

<section class="innerpage-section">
 	 <div class="container">
             <div class="row" style="min-height: 500px;"> 
              
             
              <?php echo $pagedata['content'];?>
              
              
              
              
              
     	</div>
     </div>
</section>

 