<?php

namespace backend\controllers;

use Yii;
use common\models\Review;
use common\models\ReviewSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
/**
 * ReviewController implements the CRUD actions for Review model.
 */
class ReviewController extends Controller
{


   public function beforeAction($action) {
        
        if (!parent::beforeAction($action)) {
        return false;
        }
        else if ( \Yii::$app->user->identity->role != "superAdmin") {
        $auth_item = 'ReviewController';
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
           //         'delete' => ['post'],
                ],
            ],
        ];
    }

    
    public function actionTutorReview()
    {
        $searchModel = new ReviewSearch();
        $dataProvider = $searchModel->searchTutor(Yii::$app->request->queryParams);

        return $this->render('index_tutor', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionStudentReview()
    {
        $searchModel = new ReviewSearch();
        $dataProvider = $searchModel->searchStudent(Yii::$app->request->queryParams);

        return $this->render('index_student', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
     /**
     * Creates a new Review model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateTutorReview()
    {
        $model = new Review();
//        $model->created_by = \Yii::$app->user->identity->id;
//        $model->created_date = date("Y-m-d H:i:s");
       
        //$model->scenario = 'reviewCreate';
           
        if ($model->load(Yii::$app->request->post())) {
                     $post = Yii::$app->request->post()['Review'];
            $model->review_opt=$post['review_opt'];
            $model->comment=$post['comment'];
            $model->rating=$post['rating'];
            $model->posted_by= \Yii::$app->user->identity->id;
            $model->posted_for= $post['posted_for'];
            $model->question_id= 0;
            $model->created_date=date("Y-m-d H:i:s");
            $model->posted_by_role= \Yii::$app->user->identity->role;
            $model->posted_for_role= 'tutor';
            $model->save(FALSE);
                        
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Added Successfully'));
            return $this->redirect(['tutor-review']);
        } else {
            return $this->render('create_tutor', [
                'model' => $model,
            ]);
        }
    }
    public function actionCreateStudentReview()
    {
        $model = new Review();
//        $model->created_by = \Yii::$app->user->identity->id;
//        $model->created_date = date("Y-m-d H:i:s");
       
        //$model->scenario = 'reviewCreate';
           
        if ($model->load(Yii::$app->request->post())) {
                                $post = Yii::$app->request->post()['Review'];
            $model->review_opt=$post['review_opt'];
            $model->comment=$post['comment'];
            $model->rating=$post['rating'];
            $model->posted_by= \Yii::$app->user->identity->id;
            $model->posted_for= $post['posted_for'];
            $model->question_id= 0;
            $model->created_date=date("Y-m-d H:i:s");
            $model->posted_by_role= \Yii::$app->user->identity->role;
            $model->posted_for_role= 'student';
            $model->save(FALSE);
            
                        
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Added Successfully'));
            return $this->redirect(['student-review']);
        } else {
            return $this->render('create_student', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Review model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateTutorReview($id)
    {
        $id = \common\components\GeneralComponent::decrypt($id);
        $model = $this->findModel($id);
       // $model->modified_by = \Yii::$app->user->identity->id;
//        $model->modified_date = date("Y-m-d H:i:s");
        
        //$model->scenario = 'reviewUpadte';
        
                if ($model->load(Yii::$app->request->post())) {
            $model->save(FALSE);
            
                                
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Updated Successfully'));
            return $this->redirect(['tutor-review']);
        } else {
            return $this->render('update_tutor', [
                'model' => $model,
            ]);
        }
    }
    public function actionUpdateStudentReview($id)
    {
        $id = \common\components\GeneralComponent::decrypt($id);
        $model = $this->findModel($id);
       // $model->modified_by = \Yii::$app->user->identity->id;
//        $model->modified_date = date("Y-m-d H:i:s");
        
        //$model->scenario = 'reviewUpadte';
        
                if ($model->load(Yii::$app->request->post())) {
            $model->save(FALSE);
            
                                
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Updated Successfully'));
            return $this->redirect(['student-review']);
        } else {
            return $this->render('update_student', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Review model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Review the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Review::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionDeleteTutor($id)
    {
        $id = \common\components\GeneralComponent::decrypt($id);
        $model = $this->findModel($id); 
       $model->status = 2;
       if ($model->save(false)) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Deleted Successfully'));
            return $this->redirect('tutor-review');
        }
    }public function actionDeleteStudent($id)
    {
        $id = \common\components\GeneralComponent::decrypt($id);
        $model = $this->findModel($id); 
       $model->status = 2;
       if ($model->save(false)) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Deleted Successfully'));
            return $this->redirect('student-review');
        }
    }
     /**
    *------------ Deactivate the rows -----------------
    */
    public function actionIsActive($id){
        $searchModel = new ReviewSearch();
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
}
