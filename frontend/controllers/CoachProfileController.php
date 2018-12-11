<?php

namespace frontend\controllers;
use yii\web\UploadedFile; 
use Yii;
use yii\helpers\Url;
use common\components\TutorComponent;
use yii\data\SqlDataProvider;
use yii\web\Response; 
use yii\widgets\ActiveForm; 
use common\components\GeneralComponent;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use common\models\Chat;
use frontend\models\AppointmentSearch;
use common\models\UserSearch;

class CoachProfileController extends \yii\web\Controller
{
  public function beforeAction($action) {
        
        if(!@\yii::$app->user->id)
        {
            $this->redirect(['site/index'], 302);
            Yii::$app->end();
           
        }
            if (\Yii::$app->user->identity->role != 'trainer') {
         
                //throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
           
            }
        return true;
        
   }
    
    public function actionView($id)
    {
        
        $id = \common\components\GeneralComponent::decrypt($id);
        $model = $this->findModel($id);
        
        $coachClasses = YII::$app->db->createCommand("SELECT * FROM trainer_class where status !='2' AND created_by =".$model->id)->queryAll();
        $coachVideos = YII::$app->db->createCommand("SELECT * FROM trainer_video where status !='2' AND created_by =".$model->id)->queryAll();
        $coachBlogs = YII::$app->db->createCommand("SELECT * FROM blog where status !='2' AND created_by =".$model->id)->queryAll();
        
        return $this->render('view', [
                'model' => $model,
                'coachClasses' => $coachClasses,
                'coachVideos' => $coachVideos,
                'coachBlogs' => $coachBlogs,
            ]);
                
        
    }

     /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
      
    
}
