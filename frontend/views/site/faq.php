<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Faq';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php

$pagedata = Yii::$app->db->createCommand("SELECT * FROM cms WHERE page_key = 'faq'")->queryOne();

$allfaq_array = Yii::$app->db->createCommand("SELECT * FROM faq WHERE status = 1")->queryAll();


?>
<?php if($pagedata['header_image']) { ?>
<div class="cmsbanner">
    <img src="<?php echo Yii::$app->homeUrl;?>uploads/header_image/<?php echo $pagedata['header_image'];?>" class="img-responsive" alt="">
    <h1><?=$pagedata['title'];?></h1>
</div>

<?php } ?>


<section class="faq">
    	<div class="container">
        	<div class="row">
            	<div class="col-xs-12 col-sm-12">
                	<?php echo @$pagedata['content'];?>
                    
                    <div class="faq_tab">
                    	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <?php
                                if($allfaq_array) 
                                { 
                                
                                    foreach ($allfaq_array as $value) { ?>
                                        
                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="heading_<?php echo $value['faq_id'];?>">
                                                    <h4 class="panel-title">
                                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $value['faq_id'];?>" aria-expanded="true" aria-controls="collapse_<?php echo $value['faq_id'];?>">
                                                            <i class="more-less fa fa-angle-right" aria-hidden="true"></i>
                                                            <?php echo $value['title'];?>
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse_<?php echo $value['faq_id'];?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_<?php echo $value['faq_id'];?>">
                                                    <div class="panel-body">
                                                          <?php echo $value['content'];?>
                                                    </div>
                                                </div>
                                            </div>
                            
                                <?php }
                                
                                
                                }  ?>
                            
                        </div><!-- panel-group -->
                    </div>
                </div>
            </div>
        </div>
    </section>
 
<?php 

$this->registerJs("
	function toggleIcon(e) {
    $(e.target)
        .prev('.panel-heading')
        .find('.more-less')
        .toggleClass('fa-angle-right fa-angle-down');
}
$('.panel-group').on('hidden.bs.collapse', toggleIcon);
$('.panel-group').on('shown.bs.collapse', toggleIcon);
	

",  yii\web\View::POS_READY);?>