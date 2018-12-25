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

class TrainerController extends \yii\web\Controller
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
    

   
   public function actionAcceptReject()
   {
      $appointment_id = $_POST['appointment_id'];
      $appointmentDetail = \frontend\models\Appointment::find()->where(['appointment_id' => $appointment_id, 'status' => 1, 'appointment_status' => 0])->one();
      if(empty($appointmentDetail)){
          Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Something went wrong'));
          echo "error";
      }
      else 
      {
          $userDetail = User::findOne($appointmentDetail->created_by);
          $trainerDetail = User::findOne($appointmentDetail->trainer_id);
          if($_REQUEST['action'] == "accept")
          {
              $sql_appointment = "UPDATE appointment SET appointment_status=1 WHERE appointment_id =".$appointmentDetail->appointment_id;
              $data_appointment = yii::$app->db->createCommand($sql_appointment)->execute();
          
              $push_noti_msg = $trainerDetail['first_name'] . " " . $trainerDetail['last_name'] . " has accepted your appointment request.";
              GeneralComponent::saveNotificationLog($appointmentDetail->appointment_id, $appointmentDetail->trainer_id, $appointmentDetail->created_by, 2, $push_noti_msg, $appointmentDetail->trainer_id);
              
              $appointment_confirm = array();
              $appointment_confirm['appointment_id'] = $appointmentDetail->appointment_id;
              $appointment_confirm['user_id'] = $appointmentDetail->created_by;
              $appointment_confirm['trainer_id'] = $appointmentDetail->trainer_id;
              $appointment_confirm['appointment_date'] = $appointmentDetail->appointment_date;
              $appointment_confirm['created_date'] = date('Y-m-d H:i:s');
              $appointment_confirm['status'] = 0;
              \Yii::$app->db->createCommand()->insert('appointment_confirm',$appointment_confirm)->execute();
              
              $appointmentTime = date('Y-m-d h:i A',strtotime($appointmentDetail->appointment_date));
              $gnl = new GeneralComponent();
              $email_model = \common\models\EmailTemplate::findOne(15);
              $subject = $email_model->emailtemplate_subject;
              $bodymessage = $email_model->emailtemplate_body;
              $bodymessage = str_replace('{username}', $userDetail->first_name.' '.$userDetail->last_name, $bodymessage);
              $bodymessage = str_replace('{coachname}', $trainerDetail->first_name.' '.$trainerDetail->last_name, $bodymessage);
              $bodymessage = str_replace('{appointmenttime}', $appointmentTime, $bodymessage);
              $gnl->saveMail($userDetail->email, $subject, $bodymessage);
              echo "success";
              die;
          }
          else if($_REQUEST['action'] == "reject")
          {
              $sql_appointment = "UPDATE appointment SET appointment_status=2 WHERE appointment_id =".$appointmentDetail->appointment_id;
              $data_appointment = yii::$app->db->createCommand($sql_appointment)->execute();
              
              $push_noti_msg = $trainerDetail['first_name'] . " " . $trainerDetail['last_name'] . " has rejected your appointment request.";
              GeneralComponent::saveNotificationLog($appointmentDetail->appointment_id, $appointmentDetail->trainer_id, $appointmentDetail->created_by, 3, $push_noti_msg, $appointmentDetail->trainer_id);
              
              $appointmentTime = date('Y-m-d h:i A',strtotime($appointmentDetail->appointment_date));
              $gnl = new GeneralComponent();
              $email_model = \common\models\EmailTemplate::findOne(16);
              $subject = $email_model->emailtemplate_subject;
              $bodymessage = $email_model->emailtemplate_body;
              $bodymessage = str_replace('{username}', $userDetail->first_name.' '.$userDetail->last_name, $bodymessage);
              $bodymessage = str_replace('{coachname}', $trainerDetail->first_name.' '.$trainerDetail->last_name, $bodymessage);
              $bodymessage = str_replace('{appointmenttime}', $appointmentTime, $bodymessage);
              $gnl->saveMail($userDetail->email, $subject, $bodymessage);
              echo "success";
              die;
          }
          else
          {    
               echo "error";
               die;
          }     
      }
          
      exit;     
   }
    
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,'trainer');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionSchedules()
    {
        $searchModel = new AppointmentSearch();
        $dataProvider = $searchModel->trainersearch(Yii::$app->request->queryParams);
        return $this->render('schedules', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
   
    function reArrayFiles(&$file_post) {

        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

        return $file_ary;
    }
    
        
    
    
    
    public function actionChangePassword()
    {
        $model = new PasswordResetRequestForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
                return $this->goHome();
            }
        }

    }  
    
    
    public function actionProfile()
    {
        $gnl = new GeneralComponent();
        $id = \Yii::$app->user->identity->id;
        $model = $this->findModel($id);
        $model->modified_by = \Yii::$app->user->identity->id;
        $model->modified_date = date("Y-m-d H:i:s");
        
        $model_pwd = \common\models\ChangePassword::findOne($id);
        $getpasswordhash = $model_pwd['password_hash'];
        if (!$model_pwd) {
            throw new NotFoundHttpException('User not found');
        }
        if(Yii::$app->request->isAjax && ($model_pwd->load(Yii::$app->request->post()) || $model->load(Yii::$app->request->post())) ){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model_pwd);
        }  
        else if ($model->load(Yii::$app->request->post()) )
        {  
            
            $gnl->fileupload(realpath('../../') . '/uploads/', 'profile_photo', $model, 'profile_photo');
            if ($model->profile_photo == '')
            {
                unset($model->profile_photo);
            }
//            $gnl->fileupload(realpath('../../') . '/uploads/', 'banner_image', $model, 'banner_image');
//            if ($model->banner_image == '')
//            {
//                unset($model->banner_image);
//            }
            $model->save(false);  
            Yii::$app->db->createCommand()->update('user', ['trainer_profile_complete' => 1], 'id='.$id)->execute();
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Profile Updated Successfully'));
            return $this->redirect(['profile']);
           
        } 
        else if ($model_pwd->load(Yii::$app->request->post())) 
        {
            $oldpass = Yii::$app->request->post('ChangePassword')['old_password'];  
            if (Yii::$app->getSecurity()->validatePassword($oldpass, $getpasswordhash))
            {
                $objSecurity = new \yii\base\Security();
                if (!empty($model_pwd->password_hash)) {
                    $model_pwd->password_hash = $objSecurity->generatePasswordHash($model_pwd->password_hash);
                    $model_pwd->save(false);
                }
                else
                {
                        Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'Please try again'));
                        return $this->render('profile', [
                       'model' => $model,
                       'model_pwd' => $model_pwd,
                   ]);
                
                 }
            }
            else
            {
                 Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'Old Password is incorrect'));
                        return $this->render('profile', [
                       'model' => $model,
                       'model_pwd' => $model_pwd,
                   ]);
            }
            
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Password Updated Successfully'));
            return $this->redirect(['profile']);
        }
        else
        {
            return $this->render('profile', [
                'model' => $model,
                'model_pwd' => $model_pwd,
            ]);
        }
    }


   
    public function actionAvailability()
    {
        if(@Yii::$app->user->identity->role == "trainer")
        {
            $sql = "SELECT * From trainer_availability WHERE trainer_id = ".\Yii::$app->user->identity->id;
            $availability_Detail = yii::$app->db->createCommand($sql)->queryOne();
            if(!$availability_Detail)
            {

               Yii::$app->db->createCommand()->insert('trainer_availability',
                    [
                        'month_availability' => 'a:0:{}',
                        'day_availability' => 'a:0:{}',
                        'time_availability' => 'a:0:{}',   
                        'trainer_id'=> \Yii::$app->user->identity->id,  
                        'created_by'=> \Yii::$app->user->identity->id,  
                        'created_date' => date('Y-m-d H:i:s'),
                        'status' => 1
                    ])->execute();
                return $this->redirect(['trainer/availability']);                
            }

            if(@$_POST)
            {
                $monthAvail = $availability_Detail['month_availability']; 
                if(@$_POST['monthselected'])
                {
                    $monthAvail = serialize($_POST['monthselected']);
                }

                $dayAvail = $availability_Detail['day_availability']; 
                if(@$_POST['dayselected'])
                {
                    $dayAvail = serialize($_POST['dayselected']);
                }

                $timeAvail = $availability_Detail['day_availability']; 
                if(@$_POST['timecall'])
                {
                    $timeAvail = serialize($_POST['timecall']);
                }
                
                Yii::$app->db->createCommand()->update('trainer_availability',
                    [
                        'month_availability' => $monthAvail,
                        'day_availability' => $dayAvail,
                        'time_availability' => $timeAvail, 
                        'modified_date'=>date('Y-m-d H:i:s'),
                    ],'trainer_id ='.Yii::$app->user->identity->id)->execute();

                    Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Availability upated successfully.'));
                    return $this->redirect(['trainer/availability']);        
            }

            return $this->render('availability', ['availabilityDetail'=>$availability_Detail]);
        }
        else
        {
            return $this->redirect(['site/index']);
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
