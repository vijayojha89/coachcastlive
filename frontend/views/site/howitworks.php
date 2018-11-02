<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'How It Works';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php

$pagedata = Yii::$app->db->createCommand("SELECT * FROM cms WHERE page_key = 'howitworks'")->queryOne();

?>

<?php if($pagedata['header_image']) { ?>
<div class="cmsbanner">
    <img src="<?php echo Yii::$app->homeUrl;?>uploads/header_image/<?php echo $pagedata['header_image'];?>" class="img-responsive" alt="">
    <h1><?=$pagedata['title'];?></h1>
</div>

<?php } ?>

<?php echo $pagedata['content'];?>
 