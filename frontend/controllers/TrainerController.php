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


class TrainerController extends \yii\web\Controller
{
  public function beforeAction($action) {
        
        if(!@\yii::$app->user->id)
        {
            $this->redirect(['site/index'], 302);
            Yii::$app->end();
           
        }
            if (\Yii::$app->user->identity->role != 'trainer') {
         
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
           
            }
        return true;
        
   }
    
   
   public function actionDashboard()
   {
       return $this->render('dashboard'); 
   }        
   
   public function actionIndex()
    {
        return $this->render('index');
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
    
        
    public function actionMyanswer()
    {
        
        $searchModel = new QuestionSearch();
        $dataProvider = $searchModel->searchTutorQuestions(Yii::$app->request->queryParams);

        return $this->render('myanswer', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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


    
    public function actionSetting()
    {
       $user = User::find()->where(['id'=>\Yii::$app->user->identity->id,'status'=>1])->One();
       return $this->render('setting',
                            ['checkselection'=>$user['email_on_selection'],
                             'checkinvoice'=>$user['email_on_invoice']]);
        
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
            return $this->render('availability', ['availabilityDetail'=>$availability_Detail]);
        }
        else
        {
            return $this->redirect(['site/index']);
        }
        
    }
    
    
    public function actionReject()
    {
        $tnl = new TutorComponent();
        if($data = $tnl->reject_question($_REQUEST['question_id'], $_REQUEST['tutor_id'])){
                if($data == 1 ){
                    Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Question rejected.'));
                }
                else if($data == 3 ){
                    Yii::$app->getSession()->setFlash('error', Yii::t('app', 'This question is not available anymore.'));
                }
                else if($data == 2 ){
                    Yii::$app->getSession()->setFlash('error', Yii::t('app', 'You are already confirmed.'));
                }
                else if($data == 4 ){
                    Yii::$app->getSession()->setFlash('error', Yii::t('app', 'You are already rejected.'));
                }
               return $this->redirect(['tutor/myanswer']);
        }
        else {
           return FALSE;}
    } 
    public function actionAccept()
    {
        $tnl = new TutorComponent();
        $tutor_bid_amount = (isset($_REQUEST['tutor_bid_amount'])) ? $_REQUEST['tutor_bid_amount'] : 0;
        if( $data = $tnl->accept_question($_REQUEST['question_id'], $_REQUEST['tutor_id'],$tutor_bid_amount)){
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Question accepted.'));
               return $this->redirect(['tutor/myanswer']);
        }
        else {
           return FALSE;}
    } 
    public function actionBidAmount()
    {
        $question_id = $_REQUEST['question_id'];
        $tutor_id = $_REQUEST['tutor_id'];
        
        $model = Question::findOne($question_id);
        $min_bid = $model['min_budget'];
        $max_bid = $model['max_budget'];
        $model->scenario = 'bidamount';
        
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }  
        else  if ($model->load(Yii::$app->request->post())) {
           // echo '<pre>'; print_r(Yii::$app->request->post());exit;
            $tnl = new TutorComponent();
            $tutor_bid_amount = (isset($_REQUEST['tutor_bid_amount'])) ? $_REQUEST['tutor_bid_amount'] : 0;
            $data = $tnl->accept_question($question_id, $tutor_id,Yii::$app->request->post()['Question']['bid_amount']);
                if($data == 0 ){
                Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Bid Submitted Successfully'));
                }
                else if($data == 1 ){
                    Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Question accepted'));
                }
                else if($data == 3 ){
                    Yii::$app->getSession()->setFlash('error', Yii::t('app', 'This question is not available anymore'));
                }
                else if($data == 2 ){
                    Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Student has selected another tutor'));
                }
               return $this->redirect(['tutor/myanswer']);
        } else {
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('bidamount', ['model'=>$model,'min_bid'=>$min_bid,'max_bid'=>$max_bid]);
            }
        }
    }
    
    public function actionStudentProfile()
    {
            $tnl = new TutorComponent();
            $student_data = $tnl->get_student_profile($_REQUEST['student_id'],1);
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('student-profile', ['student_data'=>$student_data,
                                                             'accept_button'=>$_REQUEST['accept'],
                                                             'reject_button'=>$_REQUEST['reject'],
                                                             'question_id'=>$_REQUEST['qid'],
                                                             'price_type'=>$_REQUEST['ptype']]);
            }
        
    }
    
    public function actionMarkCompleted()
    {
        $question_id = $_REQUEST['qid'];
        $select_answer = $_REQUEST['select_answer'];
        $chat_intitiated = Chat::find()->where(['question_id'=>$question_id,'sender_id'=>\Yii::$app->user->identity->id])->asArray()->all();
        if(count($chat_intitiated) < 1){
            return 0;//There is no answer for this question yet.
        }
             
       $mnl = new \common\components\MasterComponent();
       $question = Question::find()->where(['question_id'=>$question_id])->one();
       if($question['question_status'] == 2 && $select_answer != 1){
            return 2;//question is already marked as completed.
       }
       if($question['question_status']==4||$question['question_status']==5||$question['question_status']==6||$question['question_status']==7){
            return 3;//question is cancelled.
       }
       $chat_data = $mnl->tutor_answer($question_id);
       return $this->renderAjax('tutor-answers', ['model'=>$model,'chat_data'=>$chat_data]);
       
        
    }
    public function actionMarkCompletedDone()
    {
       $model = new \frontend\models\Review();
       $student_id = $_REQUEST['sid'];
       $question_id = $_REQUEST['qid'];
       $tnl = new \common\components\TutorComponent();
       $student_data = $tnl->get_student_profile($student_id,1);
       if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }  
        else  if ($model->load(Yii::$app->request->post())) {
          $review = \frontend\models\Review::find()->where(['question_id'=>$question_id,'posted_by'=>\Yii::$app->user->identity->id,'posted_for'=>$student_id,'status'=>1])->one();
        if(empty($review)){
             $post = Yii::$app->request->post()['Review'];
            $model->review_opt=$post['review_opt'];
            $model->comment=$post['comment'];
            $model->rating=$post['rating'];
            $model->posted_by= \Yii::$app->user->identity->id;
            $model->posted_for= $student_id;
            $model->question_id= $question_id;
            $model->created_date=date("Y-m-d H:i:s");
            $model->posted_by_role= 'tutor';
            $model->posted_for_role= 'student';
            $model->save(FALSE);
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Review Added Successfully'));
        }
        else{
            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Already Added'));
        }
        
            return $this->redirect(['tutor/myanswer']);
        }
        else{
       $post = ['question_id'=>$question_id,'user_id'=>\Yii::$app->user->identity->id,'chat_id'=>$_REQUEST['form']['chat_id'],'user_type'=>2];     
       $cnl = new \common\components\ChatComponent();
       $data = $cnl->mark_completed($post);
       if($data == 1){
         return $this->renderAjax('review', ['model'=>$model,'student_data'=>$student_data,'question_id'=>$question_id]);
       }
       if($data == 6){
         $review = \frontend\models\Review::find()->where(['question_id'=>$question_id,'posted_by'=>\Yii::$app->user->identity->id,'posted_for'=>$student_id,'status'=>1])->one();
         if(empty($review)){
         return $this->renderAjax('review', ['model'=>$model,'student_data'=>$student_data,'question_id'=>$question_id]);   
         }
         else{
         Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Answer Is Selected Successfully'));
         return $this->redirect(['tutor/myanswer']);
         }
       }
       else{
           return $data;
       }
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
       public function actionReviewByTutor()
    {
       $model = new \frontend\models\Review();
       $question_id = $_REQUEST['qid'];
       $question = Question::find()->where(['question_id'=>$question_id])->one();
            $tutor_id = @Yii::$app->user->id;
            $student_id = $question['created_by']; 
       $tnl = new \common\components\TutorComponent();
       $student_data = $tnl->get_student_profile($student_id,1);
            
       if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }  
        else  if ($model->load(Yii::$app->request->post())) {
          $review = \frontend\models\Review::find()->where(['question_id'=>$question_id,'posted_by'=>\Yii::$app->user->identity->id,'posted_for'=>$student_id,'status'=>1])->one();
        if(empty($review)){
             $post = Yii::$app->request->post()['Review'];
            $model->review_opt=$post['review_opt'];
            $model->comment=$post['comment'];
            $model->rating=$post['rating'];
            $model->posted_by= \Yii::$app->user->identity->id;
            $model->posted_for= $student_id;
            $model->question_id= $question_id;
            $model->created_date=date("Y-m-d H:i:s");
            $model->posted_by_role= 'tutor';
            $model->posted_for_role= 'student';
            $model->save(FALSE);
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Review Added Successfully'));
        }
        else{
            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Already Added'));
        }
        
            return $this->redirect(['tutor/myanswer']);
        }
        else{
         return $this->renderAjax('review', ['model'=>$model,'student_data'=>$student_data,'question_id'=>$question_id]);
       
        }
            
        } 
        
/**
     * tutor requests expertide
     * service_token,user_id,expertise
     */
    public function actionRequestExpertise() {
     {
        
        $model = new \common\models\Subject();
        
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }  
        else  if ($model->load(Yii::$app->request->post())) {
//            echo '<pre>'; print_r(Yii::$app->request->post());exit;
           $post = [
            'user_id' => \Yii::$app->user->identity->id,
            'expertise' => Yii::$app->request->post()['Subject']['name']
        ];
        
        $tnl = new TutorComponent();
        $tnl->add_expertise($post);
        Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Request to add new expertise has been sent.'));
               return $this->redirect(['tutor/profile']);
        } else {
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('add-expertise', ['model'=>$model]);
            }
        }
    }
        
    }        
public function actionSettingMail()
    {
        $user = User::find()->where(['id'=>\Yii::$app->user->identity->id,'status'=>1])->One();
        if(!empty($user)){
            if($_REQUEST['type'] == 1)
            {
            $user->email_on_selection = ($user['email_on_selection'] == 0)?1:0;
            }
            if($_REQUEST['type'] == 2)
            {
            $user->email_on_invoice = ($user['email_on_invoice'] == 0)?1:0;
            }
        $user->save(FALSE);
        }
    }
    
    
}
