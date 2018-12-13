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


    public function actionAvailability($id)
    {

        $id = \common\components\GeneralComponent::decrypt($id);
        $model = $this->findModel($id);
        $sql = "SELECT * From trainer_availability WHERE trainer_id = ".$model->id;
        $availability_Detail = yii::$app->db->createCommand($sql)->queryOne();
        return $this->render('availability', [ 'model' => $model,'availabilityDetail'=>$availability_Detail]);
    }


    public function actionAboutme($id)
    {
        $gnl = new GeneralComponent();
        $id = \common\components\GeneralComponent::decrypt($id);
        $model = $this->findModel($id);
        return $this->render('aboutme', ['model' => $model]);
    }



    public function actionBookAppointment($id)
    {
        $model = new \frontend\models\Appointment();
        $id = \common\components\GeneralComponent::decrypt($id);
        $usermodel = $this->findModel($id);
        $model->trainer_id = $usermodel->id;
        $model->created_by = \Yii::$app->user->identity->id;
        $model->created_date = date("Y-m-d H:i:s");
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }  
        else if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $trainer_id = $model->trainer_id;
            $receiver = User::findOne($trainer_id);
            $model->price = $receiver['schedule_call_fee'];
            $model->save(false);
            $appointment_id = $model->getPrimaryKey();
            
            $sender = User::findOne(Yii::$app->user->identity->id);
            $receiver = User::findOne($trainer_id);

            $push_noti_msg = $sender['first_name']." ".$sender['last_name']." has schedule an appointment.";
            $noti_type = 1;
            $data = [];
            $param = ["message" => $push_noti_msg, "type" => $noti_type, "data" => $data];
            GeneralComponent::saveNotificationLog($appointment_id, $sender['id'], $receiver['id'], $noti_type, $push_noti_msg, $sender['id']);
            
            //----------------N_EMAILNOTIFICATION-------------mail-to-TRAINER-------------------------------------------
            $gnl = new GeneralComponent();
            $email_model = \common\models\EmailTemplate::findOne(5);
            $subject = $email_model->emailtemplate_subject;
            $bodymessage = $email_model->emailtemplate_body;
            $bodymessage = str_replace('{coachname}', $receiver->first_name.' '.$receiver->last_name, $bodymessage);
            $bodymessage = str_replace('{username}', $sender->first_name.' '.$sender->last_name, $bodymessage);
            $gnl->saveMail($receiver->email, $subject, $bodymessage);
        
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Appointment added successfully'));
            return $this->redirect(Yii::$app->request->referrer);
        }
        else
        {
             return $this->render('bookappointment',['model'=>$model,'usermodel'=>$usermodel]);
        }
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
