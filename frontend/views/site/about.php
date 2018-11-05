<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php

$pagedata = Yii::$app->db->createCommand("SELECT * FROM cms WHERE page_key = 'about'")->queryOne();

?>

<?php if($pagedata['header_image']) { ?>
<!--<div class="cmsbanner">
    <img src="<?php echo Yii::$app->homeUrl;?>uploads/header_image/<?php echo $pagedata['header_image'];?>" class="img-responsive" alt="">
     <h1><?=$pagedata['title'];?></h1>
</div>-->

<?php } ?>

 <!-- Start Inner Banner area -->
 <div class="inner-banner-area">
            <div class="container">
                <div class="row">
                    <div class="innter-title">
                        <h2><?=$pagedata['title'];?></h2>
                    </div>
                </div>
            </div>
        </div>
<!-- End Inner Banner area -->
<div class="padding-space">
<div class="container">
        <div class="row">
        <h2 class="section-title-default2 title-bar-high2"><?=$pagedata['title'];?> </h2> 
              <?php echo $pagedata['content'];?>
     	</div>
</div>
</div>
 