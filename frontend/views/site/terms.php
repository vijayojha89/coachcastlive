<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Terms and Conditions';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php

$pagedata = Yii::$app->db->createCommand("SELECT * FROM cms WHERE page_key = 'terms'")->queryOne();

?>
<?php if($pagedata['header_image']) { ?>
<div class="cmsbanner">
    <img src="<?php echo Yii::$app->homeUrl;?>uploads/header_image/<?php echo $pagedata['header_image'];?>" class="img-responsive" alt="" style="max-height: 300px;">
    <h1 style="left:48%;"><?=$pagedata['title'];?></h1>
</div>

<?php } ?>



<section class="brdcum-section">
    <div class="container" style="padding:0px;">  
         <ul class="brdcum">
            	<li><a href="<?php echo Yii::$app->homeUrl;?>">Home</a></li>
                <li>Terms And Conditions</li>
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

 