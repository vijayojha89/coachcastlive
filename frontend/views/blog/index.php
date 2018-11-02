<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;



/* @var $this yii\web\View */
/* @var $searchModel frontend\models\BlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Blog';
echo $this->render('//trainer/_trainer_header.php');

?>

<div id="content" class="inner_container">
  <section class="contentsection">
    <div class="container">
      
         
         <div class="row">
          <div class="col-xs-6">
           <h1 class="maintitle">Blogs</h1>
          </div>
          <div class="col-xs-6">
              <div class="provideo-add"> 
                  <a class="add_vbtn" href="<?php echo Url::to(['blog/create']);?>">
                      <span><i class="fa fa-plus-circle"></i> Add New</span>
                  </a>
             </div>
          </div>
          
     </div>
    </div>
      <div class="container">
      <?php Pjax::begin(); ?>
          <?= 
ListView::widget([
    'dataProvider' => $dataProvider,
    'options' => [
        'tag' => 'div',
        'class' => 'search-result-pg row trainer_dashbord_blog',
        'id' => 'list-wrapper',
    ],
    'layout' => "\n{items}\n{pager}",
    'itemView' => '_list_item',
]);
    

?>
          
<?php Pjax::end(); ?>
          
    </div>
      
      
  </section>
</div>