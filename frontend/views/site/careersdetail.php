<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Career Details';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="career_details">
	<div class="container">
    <div class="jobtitle"><?php echo $careerdetail['title'];?></div>
    <div class="joblocation"><?php echo $careerdetail['location'];?></div>
    <div class="jobdescription"><?php echo $careerdetail['description'];?></div>
   </div>
</div>

