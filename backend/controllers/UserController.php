<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\Response; 
use yii\widgets\ActiveForm; 
use common\components\GeneralComponent;
use common\models\Rolesallocation;
use common\models\StudentTutorSubject;
use yii\helpers\ArrayHelper;
/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{


   public function beforeAction($action) {
        
        if (!parent::beforeAction($action)) {
        return false;
        }
        else if ( \Yii::$app->user->identity->role != "superAdmin"  && Yii::$app->controller->action->id != 'update-profile') {
        $auth_item = 'UserController';
        $auth_item_action = ucfirst(Yii::$app->controller->action->id) ;
        $auth_action = str_replace('-','',$auth_item_action);
        $getrole = \common\models\Roles::findOne(["role_name" => \Yii::$app->user->identity->role]);
        $getcontroller_id = \common\models\AuthItems::findOne(["auth_items_name" => $auth_item]);
        $getaction_id = \common\models\AuthItems::findOne(["parent_auth_item_id"=>$getcontroller_id->auth_items_id,
                                                           "auth_items_name" => $auth_action]);
        
        if (empty($getaction_id) ) {
         
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
           
        }
        $get_access = \common\models\Rolesallocation::findOne(["controller_id"=>$getcontroller_id->auth_items_id,
                                                                 "action_id" => $getaction_id->auth_items_id,
                                                                "user_id" => \Yii::$app->user->identity->id,
                                                                 "role_id" => $getrole->id]);
        

        if (empty($get_access) ) {
         
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
           
        }
        } return true;
   }





    public function behaviors()
    {
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
                  //  'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->redirect('sub-admin');
    }
    
    public function actionSubAdmin()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,'subAdmin');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionTutor()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,'tutor');

        return $this->render('index-tutor', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionStudent()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,'student');

        return $this->render('index-student', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateSubAdmin()
    {
        $model = new User();
        $model->created_by = \Yii::$app->user->identity->id;
        $model->created_date = date("Y-m-d H:i:s");
        
    if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }  
    else    if ($model->load(Yii::$app->request->post())) {
                     $gnl = new \common\components\GeneralComponent();

            //echo '<pre>';print_r(Yii::$app->request->post());exit();
            $imageUrl_profile_photo = $gnl->uploadImageS3HB($model,'profile_photo');
            $model->profile_photo =  $imageUrl_profile_photo;
            if ($model->profile_photo == '') {
                unset($model->profile_photo);
            }
            $pwd = $model->password_hash;
            $objSecurity = new \yii\base\Security();
            $model->password_hash = $objSecurity->generatePasswordHash($model->password_hash);
            $model->role = 'subAdmin';
            $model->save(false);
            
            $connection = \Yii::$app->db;
            $queryMenuList = $connection->createCommand('SELECT * FROM dynamic_menu WHERE type!=2 AND status = 1');
            $querymenulist = $queryMenuList->queryAll();

            
            if(count($querymenulist) > 0){
                //                ...........................start1...................................
                
                $role = Yii::$app->db->createCommand("SELECT id FROM roles WHERE role_name = 'subAdmin' AND status = 0")->queryOne();
                $rollId =  $role['id'];
               $deleteAllRoleAccess = Yii::$app->db->createCommand("
                DELETE FROM roles_allocation 
                WHERE role_id = '$rollId'  AND user_id = ".$model->id."
                ")->execute();
//               ..............................end1.....................................
                
                foreach ($querymenulist as $menuvalue){
                    if (in_array($menuvalue['id'], Yii::$app->request->post('menuname'))){
                        $status = 1;
 //                ................................start2.........................................
                    $get_controller_id = Yii::$app->db->createCommand("SELECT * FROM dynamic_menu WHERE id = ".$menuvalue['id']." AND status = 1")->queryOne();
                    $controllerId = $get_controller_id['controller_id'];
                    $get_action_array  = explode(',', $get_controller_id['actions']);
                    foreach($get_action_array as $valueactions){
                        
                        $modelInsert = new Rolesallocation();
                        $modelInsert->created_by = \Yii::$app->user->identity->id;
                        $modelInsert->created_date = date("Y-m-d H:i:s");
                        $actionId = $valueactions;
                        $modelInsert->user_id = $model->id;
                        $modelInsert->role_id = $rollId;
                        $modelInsert->controller_id = $controllerId;
                        $modelInsert->action_id =$actionId;
                        $modelInsert->status = 0;
                        $modelInsert->save();
                    }
//                    ...........................end2..............................................
                    }else{
                        $status = 0;
                    }
                            
                    $connection->createCommand()
                    ->insert('user_menu_access', [
                                    'user_id' => $model->id,
                                    'menu_id' => $menuvalue['id'],
                                    'menu_key'=>$menuvalue['key'],
                                    'status'=>$status])->execute();

                }
            }
            /*-----------------------------------E-mail start --------------------------------*/
              $email_model = \common\models\EmailTemplate::findOne(1);

                            $first_name = $model->first_name;
                            $last_name = $model->last_name;
                            $usermail = $model->email;
                            $password = $pwd;
                            $subject = $email_model->emailtemplate_subject;
                            $bodymessage = $email_model->emailtemplate_body;

                            $bodymessage = str_replace('{firstname}', $first_name, $bodymessage);
                            $bodymessage = str_replace('{lastname}', $last_name, $bodymessage);
                            $bodymessage = str_replace('{email}', $usermail, $bodymessage);
                            $bodymessage = str_replace('{password}', $password, $bodymessage);

                            $gnl->sendMail($usermail, $subject, $bodymessage);

            /*-----------------------------------E mail end----------------------------------*/
                        
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Added Successfully'));
            return $this->redirect(['sub-admin']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    } public function actionCreateTutor()
    {
        $model = new User();
        $model->created_by = \Yii::$app->user->identity->id;
        $model->created_date = date("Y-m-d H:i:s");
        $model->scenario = 'tutorCreate';
        $model->user_type = 2;
        //$model_payment = new \common\models\UserPaymentInfo();
        
    if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }  
    //else if ($model->load(Yii::$app->request->post()) && $model_payment->load(Yii::$app->request->post())) {
     else if ($model->load(Yii::$app->request->post())) {       
            $model_payment = Yii::$app->request->post()['UserPaymentInfo'];
            
            if ($user = $model->signup(Yii::$app->request->post()['User'], \yii\web\UploadedFile::getInstance($model, 'cv_doc'),2,$model_payment)) {
                        
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Added Successfully'));
            return $this->redirect(['tutor']);
            }
        } else {
            return $this->render('create-tutor', [
                'model' => $model,
                'model_payment' => $model_payment,
            ]);
        }
    }
     public function actionCreateStudent()
    {
        $model = new User();
        $model->created_by = \Yii::$app->user->identity->id;
        $model->created_date = date("Y-m-d H:i:s");
        $model->scenario = 'studentCreate';
        $model->user_type = 1;
        
    if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }  
    else    if ($model->load(Yii::$app->request->post())) {
            
            if ($user = $model->signup(Yii::$app->request->post()['User'],'',1)) {
                        
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Added Successfully'));
            return $this->redirect(['student']);
            }
        } else {
            return $this->render('create-student', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $id = \common\components\GeneralComponent::decrypt($id);
        $model = $this->findModel($id);
        $model->modified_by = \Yii::$app->user->identity->id;
        $model->modified_date = date("Y-m-d H:i:s");
        $model_payment = new \common\models\UserPaymentInfo();
        if ($model->load(Yii::$app->request->post())) {
            $gnl = new \common\components\GeneralComponent();
            $imageUrl_profile_photo = $gnl->uploadImageS3HB($model,'profile_photo');
            $model->profile_photo =  $imageUrl_profile_photo;
            if ($model->profile_photo == '') {
                unset($model->profile_photo);
            }
                    $model->save(FALSE);
            
            $connection = \Yii::$app->db;
            $model = $connection->createCommand('DELETE FROM user_menu_access WHERE user_id = "'.$id.'"');
            $model->execute();
            $connection = \Yii::$app->db;
            $queryMenuList = $connection->createCommand('SELECT * FROM dynamic_menu WHERE type!=2 AND status = 1');
            $querymenulist = $queryMenuList->queryAll();
           
            
            if(count($querymenulist) > 0){
//                  ...........................start1...................................
                
                $role = Yii::$app->db->createCommand("SELECT id FROM roles WHERE role_name = 'subAdmin' AND status = 0")->queryOne();
                $rollId =  $role['id'];
               $deleteAllRoleAccess = Yii::$app->db->createCommand("
                DELETE FROM roles_allocation 
                WHERE role_id = '$rollId' AND user_id = $id
                ")->execute();
//               ..............................end1.....................................
               
                foreach ($querymenulist as $menuvalue){
                    if (in_array($menuvalue['id'], Yii::$app->request->post('menuname'))){
                        $status = 1;
                        
 //                ................................start2.........................................
                    $get_controller_id = Yii::$app->db->createCommand("SELECT * FROM dynamic_menu WHERE id = ".$menuvalue['id']." AND status = 1")->queryOne();
                    $controllerId = $get_controller_id['controller_id'];
                    $get_action_array  = explode(',', $get_controller_id['actions']);
                    foreach($get_action_array as $valueactions){
                        $modelInsert = new Rolesallocation();
                        $modelInsert->created_by = \Yii::$app->user->identity->id;
                        $modelInsert->created_date = date("Y-m-d H:i:s");
                        $modelInsert->user_id = $id;
                        $actionId = $valueactions;
                        $modelInsert->role_id = $rollId;
                        $modelInsert->controller_id = $controllerId;
                        $modelInsert->action_id =$actionId;
                        $modelInsert->status = 0;
                        $modelInsert->save();
                    }
//                    ...........................end2..............................................                        
                    }else{
                        $status = 0;
                    }
                    $connection->createCommand()
                    ->insert('user_menu_access', [
                                    'user_id' => $id,
                                    'menu_id' => $menuvalue['id'],
                                    'menu_key'=>$menuvalue['key'],
                                    'status'=>$status])->execute();

                }
            }                     
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Updated Successfully'));
            return $this->redirect(['sub-admin']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    public function actionUpdateTutor($id)
    {
        $gnl = new GeneralComponent();
        $id = \common\components\GeneralComponent::decrypt($id);
        $model = $this->findModel($id);
        $model->scenario = 'tutorUpdate';
        $model->modified_by = \Yii::$app->user->identity->id;
        $model->modified_date = date("Y-m-d H:i:s");
        $model_payment = \common\models\UserPaymentInfo::findOne(['user_id'=>$id]);
        /*if(!$model_payment)
        {
            $user_payment_model = new \common\models\UserPaymentInfo();
            $user_payment_model->user_id = $id;
            $user_payment_model->save(false);
        }
         * 
         */
        $model_payment = \common\models\UserPaymentInfo::findOne(['user_id'=>$id]);
        
        $modelExpertiseSelected = ArrayHelper::getColumn(StudentTutorSubject::findAll(['user_id'=>$id,'status'=>0]), 'subject_id');
        
        if ($model->load(Yii::$app->request->post()) /*&& $model_payment->load(Yii::$app->request->post())*/) {
            
            if ($model->cv_doc == '') {
                unset($model->cv_doc);
            }
            $gnl->filedocuploadwebservice(realpath('../../') . '/uploads/', 'cv_doc', $model, 'cv_doc', \yii\web\UploadedFile::getInstance($model, 'cv_doc'));
            $model->save(false);    
           // $model_payment->save(false);
            \Yii::$app->db->createCommand('DELETE FROM `student_tutor_subject` WHERE `user_id` = '.$id.'')->execute();
            foreach (Yii::$app->request->post()['User']['expertise_ids'] as $expertise_id) {
                    $model_subject = new StudentTutorSubject();
                    $model_subject->user_id = $id;
                    $model_subject->subject_id = $expertise_id;
                    $model_subject->save(FALSE);
                }
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Updated Successfully'));
            return $this->redirect(['tutor']);
           
        } else {
            return $this->render('update-tutor', [
                'model' => $model,
                'model_payment' => $model_payment,
                'modelExpertiseSelected'=>$modelExpertiseSelected,
            ]);
        }
    }
    public function actionUpdateStudent($id)
    {
        $id = \common\components\GeneralComponent::decrypt($id);
        $model = $this->findModel($id);
        $model->scenario = 'studentUpdate';
        $model->modified_by = \Yii::$app->user->identity->id;
        $model->modified_date = date("Y-m-d H:i:s");
        
        $modelSubjectSelected = ArrayHelper::getColumn(StudentTutorSubject::findAll(['user_id'=>$id,'status'=>0]), 'subject_id');
        
           if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }  
    else if ($model->load(Yii::$app->request->post())) {
           
            $model->save(false);    
            \Yii::$app->db->createCommand('DELETE FROM `student_tutor_subject` WHERE `user_id` = '.$id.'')->execute();
            foreach (Yii::$app->request->post()['User']['subject_ids'] as $subject_id) {
                    $model_subject = new StudentTutorSubject();
                    $model_subject->user_id = $id;
                    $model_subject->subject_id = $subject_id;
                    $model_subject->save(FALSE);
                }
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Updated Successfully'));
            return $this->redirect(['student']);
            
        } else {
            return $this->render('update-student', [
                'model' => $model,
                'modelSubjectSelected'=>$modelSubjectSelected,
            ]);
        }
    }
    
    
          


    /**
    *------------ Deactivate the rows -----------------
    */
    public function actionIsActive($id){
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $this->findModel($id);
        if($model->status==0){
            $model->status = 1;
            $message = Yii::t('app', 'Record Activated Successfully');
            
        }else{
            $model->status = 0;
            $message = Yii::t('app', 'Record De-activated Successfully');
            
        }
        if ($model->save(false)) {
            //Yii::$app->getSession()->setFlash('success', $message);
            return $this->renderAjax('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        }        
    }
    
    
    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $id = \common\components\GeneralComponent::decrypt($id);
        $model = $this->findModel($id); 
       $model->status = 2;
       $model->modified_by = \Yii::$app->user->identity->id;
        $model->modified_date = date("Y-m-d H:i:s");
       if ($model->save(false)) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Deleted Successfully'));
            return $this->redirect('sub-admin');
        }
    }public function actionDeleteTutor($id)
    {
        $id = \common\components\GeneralComponent::decrypt($id);
        $model = $this->findModel($id); 
       $model->status = 2;
       $model->modified_by = \Yii::$app->user->identity->id;
        $model->modified_date = date("Y-m-d H:i:s");
       if ($model->save(false)) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Deleted Successfully'));
            return $this->redirect('tutor');
        }
    }public function actionDeleteStudent($id)
    {
        $id = \common\components\GeneralComponent::decrypt($id);
        $model = $this->findModel($id); 
       $model->status = 2;
       $model->modified_by = \Yii::$app->user->identity->id;
        $model->modified_date = date("Y-m-d H:i:s");
       if ($model->save(false)) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Deleted Successfully'));
            return $this->redirect('student');
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
    
    public function actionUpdateProfile() {



    $model = $this->findModel(\common\components\GeneralComponent::decrypt($_REQUEST['id']));


        $model->modified_by = \Yii::$app->user->identity->id;
        $model->modified_date = date("Y-m-d H:i:s");
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }  
       else    if ($model->load(Yii::$app->request->post())) {
            $gnl = new \common\components\GeneralComponent();
            $imageUrl_profile_photo = $gnl->uploadImageS3HB($model,'profile_photo');
            $model->profile_photo =  $imageUrl_profile_photo;
            if ($model->profile_photo == '') {
                unset($model->profile_photo);
            }
            $model->save(FALSE);


            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Updated Successfully'));
            return $this->redirect(['/']);
            }
            else {
            return $this->render('update_profile', [
                        'model' => $model,
            ]);
        }
    }
    
        public function actionChangePassword($id) {
       $id = \common\components\GeneralComponent::decrypt($id);
        $model = User::findOne($id);
        $getpasswordhash = $model['password_hash'];

        if (!$model) {
            throw new NotFoundHttpException('User not found');
        }

        $model->scenario = 'changePassword';

        if ($model->load(Yii::$app->request->post())) {
            $oldpass = Yii::$app->request->post('User')['old_password'];
           if (Yii::$app->getSecurity()->validatePassword($oldpass, $getpasswordhash)) {
                $objSecurity = new \yii\base\Security();
            if (!empty($model->password_hash)) {
                $model->password_hash = $objSecurity->generatePasswordHash($model->password_hash);
                $model->save(false);
            }
            else{
                 Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'Please try again'));
                 return $this->render('changePassword', compact('model'));
                
            }
            } else {
                 Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'Old Password is Incorrect'));
                 return $this->render('changePassword', compact('model'));
            }
            // return $this->redirect(['view', 'id' => $model->id]);

            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Password Updated Successfully'));
            return $this->redirect(['/']);
        }
        //Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Password Updated Successfully'));
        return $this->render('changePassword', compact('model'));
    }
    
        public function actionChangePasswordSubAdmin($id) {
       $id = \common\components\GeneralComponent::decrypt($id);
        $model = User::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('User not found');
        }

        $model->scenario = 'changePassword';

        if ($model->load(Yii::$app->request->post())) {
            
                $objSecurity = new \yii\base\Security();
                $model->password_hash = $objSecurity->generatePasswordHash($model->password_hash);
                $model->save(false);

            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Password Updated Successfully'));
            return $this->redirect(['sub-admin']);
        }
        //Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Password Updated Successfully'));
        return $this->render('change-password-sub-admin', compact('model'));
    }    
        public function actionChangePasswordTutor($id) {
       $id = \common\components\GeneralComponent::decrypt($id);
        $model = User::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('User not found');
        }

        $model->scenario = 'changePassword';

        if ($model->load(Yii::$app->request->post())) {
            
                $objSecurity = new \yii\base\Security();
                $model->password_hash = $objSecurity->generatePasswordHash($model->password_hash);
                $model->save(false);

            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Password Updated Successfully'));
            return $this->redirect(['tutor']);
        }
        //Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Password Updated Successfully'));
        return $this->render('change-password-tutor', compact('model'));
    }
        public function actionChangePasswordStudent($id) {
       $id = \common\components\GeneralComponent::decrypt($id);
        $model = User::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('User not found');
        }

        $model->scenario = 'changePassword';

        if ($model->load(Yii::$app->request->post())) {
            
                $objSecurity = new \yii\base\Security();
                $model->password_hash = $objSecurity->generatePasswordHash($model->password_hash);
                $model->save(false);

            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Password Updated Successfully'));
            return $this->redirect(['student']);
        }
        //Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Password Updated Successfully'));
        return $this->render('change-password-student', compact('model'));
    }    
    
        public function actionChangePasswordAdmin($id) {
        $id = \common\components\GeneralComponent::decrypt($id);
        $model = User::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('User not found');
        }

        $model->scenario = 'changePassword';

        if ($model->load(Yii::$app->request->post())) {

            $objSecurity = new \yii\base\Security();
            if (!empty($model->password_hash)) {
                $model->password_hash = $objSecurity->generatePasswordHash($model->password_hash);
                $model->save(false);
            }
            // return $this->redirect(['view', 'id' => $model->id]);

            return $this->redirect(['index-admin']);
        }

        return $this->render('changePasswordAdmin', compact('model'));
    }
public function actionVerify($id,$type) {
    $model = User::findOne($id);
    if($type == 1){
        $model->payment_verified = 1;
    }
    if($type == 2){
        $model->mobile_verified = 1;
    }
    if($type == 3){
        $model->onboard = 1;
    }
    
    $model->save(FALSE);
}
}
