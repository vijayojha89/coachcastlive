<?php

namespace frontend\controllers;

use Yii;
use frontend\models\User;
use common\models\UserSearch;
use frontend\models\AppointmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\components\GeneralComponent;
use yii\data\SqlDataProvider;
use yii\helpers\Url;
/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller {
    
    
    /*   

      public function behaviors() {
      return [
      'access' => [
      'class' => AccessControl::className(),
      'rules' => [

      [
      'allow' => true,
      'roles' => ['@'],
      ],
      ],
      ],
      'verbs' => [
      'class' => VerbFilter::className(),
      'actions' => [
      //         'delete' => ['post'],
      ],
      ],
      ];
      }
     *
     */

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'signup-user', 'signup-trainer'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new User();
        $model->created_by = \Yii::$app->user->identity->id;
        $model->created_date = date("Y-m-d H:i:s");

        $model->scenario = 'frontStdCreate';

        if ($model->load(Yii::$app->request->post())) {

            $model->signup($post);


            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Added Successfully'));
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    public function actionSignup() {
        $model = new User();
        $model->created_by = \Yii::$app->user->identity->id;
        $model->created_date = date("Y-m-d H:i:s");
       
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } else if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup(Yii::$app->request->post()['User'])) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Sign up successfull. Please verify your email address to activate account'));
                return $this->redirect(['site/index']);
            }
        } else {
            
                return $this->render('signup', [
                            'model' => $model,
                ]);
            
        }
    }

    public function actionSignupTrainer() {
        $model = new User();
        $model->created_by = \Yii::$app->user->identity->id;
        $model->created_date = date("Y-m-d H:i:s");
        $model->scenario = 'trainer_signup';
        $model->user_type = 2;
        // if social login
        $social_data = Yii::$app->session->get('social_login');
        if (!empty($social_data)) {
            $model->email = $social_data['email'];
            if (!empty($social_data['name'])) {
                $name = explode(' ', $social_data['name']);
                $model->first_name = trim($name[0]);
                $model->last_name = trim($name[1]);
            }
            $model->social_type = $social_data['social_type'];
            $model->social_id = $social_data['social_login_id'];
            Yii::$app->session->remove('social_login');
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } else if ($model->load(Yii::$app->request->post())) {
            
            if ($user = $model->signup(Yii::$app->request->post()['User'])) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Sign up successfull. Please verify your email address to activate account'));
                return $this->redirect(['site/index']);
            }
        } else {
            
                return $this->render('signup_trainer', [
                            'model' => $model,
                ]);
            
        }
    }

    public function actionSignupUser() {
        $model = new User();
        $model->created_by = \Yii::$app->user->identity->id;
        $model->created_date = date("Y-m-d H:i:s");
        $model->scenario = 'student_signup';
        $model->user_type = 1;

        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } else if ($model->load(Yii::$app->request->post())) {
            
            if ($user = $model->signup(Yii::$app->request->post()['User'])) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Sign up successfull. Please verify your email address to activate account'));
                return $this->redirect(['site/index']);
            }
        } else {
            
                return $this->render('signup_user', [
                            'model' => $model,
                ]);
            
        }
    }
    
     public function actionDashboard()
   {
       return $this->render('dashboard'); 
   }   
   

  
     
    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $id = \common\components\GeneralComponent::decrypt($id);
        $model = $this->findModel($id);
        // $model->modified_by = \Yii::$app->user->identity->id;
        $model->modified_date = date("Y-m-d H:i:s");

        //$model->scenario = 'userUpadte';

        if ($model->load(Yii::$app->request->post())) {
            $model->first_name = $model->first_name;
            $model->last_name = $model->last_name;
            $model->username = $model->username;
//            $model->company_name = ucwords($model->company_name);
            $gnl = new \common\components\GeneralComponent();

            $imageUrl_0 = $gnl->uploadImageS3HB($model, 'profile_photo');
            $model->profile_photo = $imageUrl_0;

            if ($model->profile_photo == '') {
                unset($model->profile_photo);
            }
            $model->save();


            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Updated Successfully'));
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

   
    
   public function actionMyappointment()
    {
        
        $searchModel = new AppointmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('myappointment', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    
    public function actionAddappointment()
    {
        $model = new \frontend\models\Appointment();
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
            return $this->redirect(['user/myappointment']);
        }
        else
        {
             return $this->render('addappointment',['model'=>$model]);
        }
    }
    
    
    
    public function actionNotifications()
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(Url::toRoute('site/email-login'));
        }

        $mnl = new \common\components\MasterComponent();
        $data = $mnl->notifications(\Yii::$app->user->identity->id,$_REQUEST['page']);
        $noti_data = "SELECT ng.*,
                            (SELECT CONCAT(first_name,' ',last_name) from user WHERE id=ng.notification_from) as notification_from_name,
                            (SELECT profile_photo from user WHERE id=ng.notification_from) as notification_from_profile_photo
                            FROM notification_generalization as ng
                            WHERE ng.notification_to='" . \Yii::$app->user->identity->id . "' and ng.status=0
                            ORDER BY ng.notification_generalization_id DESC " ;
        $dataProvider = new SqlDataProvider([
            'sql' => $noti_data,
            'params' => [],
            'totalCount' => $data['total_records'],
            'sort' => [],
            'pagination' => [
                'pageSize' => $data['page_limit'],
            ],
        ]);
        return $this->render('notifications',['dataProvider'=>$dataProvider]);
    }
    /**
     * Cancel appointment
     */
    
    public function actionCancelAppointment()
    {
        if(Yii::$app->request->isAjax)
        {
            $appointment_id = $_POST['appointment_id'];
            if($appointment_id)
            {    
                $appointmentDetail = \frontend\models\Appointment::find()->where(['appointment_id' => $appointment_id, 'created_by' => Yii::$app->user->identity->id])->one();
                $appointmentDetail->status = '2';
                $appointmentDetail->save(false);
                
                
                $userDetail = User::findOne($appointmentDetail->created_by);
                $trainerDetail = User::findOne($appointmentDetail->trainer_id);
                
                $appointmentTime = date('Y-m-d h:i A',strtotime($appointmentDetail->appointment_date));
                $gnl = new GeneralComponent();
                $email_model = \common\models\EmailTemplate::findOne(17);
                $subject = $email_model->emailtemplate_subject;
                $bodymessage = $email_model->emailtemplate_body;
                $bodymessage = str_replace('{username}', $userDetail->first_name.' '.$userDetail->last_name, $bodymessage);
                $bodymessage = str_replace('{coachname}', $trainerDetail->first_name.' '.$trainerDetail->last_name, $bodymessage);
                $bodymessage = str_replace('{appointmenttime}', $appointmentTime, $bodymessage);
                $gnl->saveMail($userDetail->email, $subject, $bodymessage);

                echo "success";
                exit;
            }
            else
            {
                echo "error";
                exit;
            }   
        }
        else
        {
            echo "error";
            exit;
        }
    }
    
    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
