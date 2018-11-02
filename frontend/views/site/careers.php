<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Careers';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$pagedata = Yii::$app->db->createCommand("SELECT * FROM cms WHERE page_key = 'careers'")->queryOne();

?>

<?php if ($pagedata['header_image']) { ?>
    <div class="cmsbanner">
        <img src="<?php echo Yii::$app->homeUrl; ?>uploads/header_image/<?php echo $pagedata['header_image']; ?>" class="img-responsive" alt="">
        <h1><?=$pagedata['title'];?></h1>
    </div>

<?php } ?>

<?php $page_break = explode('{dynamic-content}', $pagedata['content']); 


echo $page_break[0];


//Yii::$app->params['career_category']

?>



<div class="career_studypad">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-10 middle_section">
                <h2>Careers at FitMakersLive</h2>
                
                <?php $careerdata = Yii::$app->db->createCommand("SELECT * FROM career WHERE status = 1 ORDER BY career_id DESC")->queryAll(); 
                
                if($careerdata)
                {
                    $group_array = array();
                    foreach ($careerdata as $value) {
                        $group_array[$value['department']][] = $value;
                    }
                    
                    foreach ($group_array as $key => $cvalue) {
                        echo '<h4>'.Yii::$app->params['career_category'][$key].'</h4>';
                        echo '<ul>';
                        foreach ($cvalue as $value) {
                           echo '<li><a href="'.Url::to(['/site/careersdetail','id'=>$value['career_id']]).'">'.$value['title'].'</a><span>'.$value['location'].'</span></li>';   
                        }
                        
                        echo '</ul>';
                        
                    }
                    
                    
                }    
                
                ?>
            </div>
        </div>
    </div>
</div>

<?php if(@$page_break[1]) { 
    
    echo $page_break[1];
    
} ?>