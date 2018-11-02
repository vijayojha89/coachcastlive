<?php

namespace backend\controllers;

use Yii;
use common\models\BulkMail;
use common\models\BulkMailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
/**
 * BulkMailController implements the CRUD actions for BulkMail model.
 */
class BulkMailController extends Controller
{


   public function beforeAction($action) {
        
        if (!parent::beforeAction($action)) {
        return false;
        }
        else if ( \Yii::$app->user->identity->role != "superAdmin") {
        $auth_item = 'BulkMailController';
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

    /**
     * Lists all BulkMail models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BulkMailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    
                   
        /**
     * Displays a single BulkMail model.
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
     * Creates a new BulkMail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BulkMail();
      $created_by=  $model->created_by = \Yii::$app->user->identity->id;
       $created_date= $model->created_date = date("Y-m-d H:i:s");
       $gnl = new \common\components\GeneralComponent();

        //$model->scenario = 'bulk-mailCreate';
           
        if ($model->load(Yii::$app->request->post())) {
            
        
             $users=  Yii::$app->request->post('BulkMail')['user_id'];
             if(!empty($users))
          {
          foreach ($users as $userlist) {
              
                 $adata = array();
               $adata['type'] = $model->type;
               $adata['user_id'] = $userlist;
               $adata['subject'] = $model->subject;
               $adata['body'] = $model->body;
               $adata['created_by'] = $created_by;
               $adata['created_date'] = $created_date;

               \Yii::$app->db->createCommand()->insert('bulk_mail',$adata)->execute();
               $userdetail= \common\models\User::findOne($userlist);
               
               
                /*-----------------------------------E-mail start --------------------------------*/
              
               $gnl->sendMail($userdetail->email, $model->subject, $model->body);
              
            /*-----------------------------------E mail end----------------------------------*/
                            
            
           
                  
            }
          }
            
                            
                            
                   //             $model->save();
            
                        
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Added Successfully'));
            return $this->redirect(['create']);
         
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing BulkMail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $id = \common\components\GeneralComponent::decrypt($id);
        $model = $this->findModel($id);
       // $model->modified_by = \Yii::$app->user->identity->id;
        $model->modified_date = date("Y-m-d H:i:s");
        
        //$model->scenario = 'bulk-mailUpadte';
        
                if ($model->load(Yii::$app->request->post())) {
                                $model->save();
            
                                
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Updated Successfully'));
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionGetusertype($val) {

        $where = '';
        $data = '';
        if ($val == "tutor") {
            $where .= ' AND role ="tutor"';
        }  else {
            $where .= ' AND role = "student"';
        }

        $data .= '<option value="">Select User</option>';

        if ($val != "") {
            $query = "SELECT id, CONCAT(first_name,' ',last_name) as fullname FROM user WHERE status =1  $where ORDER BY fullname ASC";
            $userdata = YII::$app->db->createCommand($query)->queryAll();
            if (@$userdata) {

                foreach ($userdata as $value) {
                    $data .= "<option value='" . $value['id'] . "'>" . $value['fullname'] . "</option>";
                }
            }
        }
        echo $data;
        die;
    }
   
                   
     
    /**
    *------------ Deactivate the rows -----------------
    */
    public function actionIsActive($id){
        $searchModel = new BulkMailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $this->findModel($id);
        if($model->status==0){
            $model->status = 1;
            $message = Yii::t('app', 'Record Activated Successfully');
            
        }else{
            $model->status = 0;
            $message = Yii::t('app', 'Record De-activated Successfully');
            
        }
        if ($model->save()) {
            //Yii::$app->getSession()->setFlash('success', $message);
            return $this->renderAjax('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        }        
    
    }
    
    
    /**
     * Deletes an existing BulkMail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    $id = \common\components\GeneralComponent::decrypt($id);
        $model = $this->findModel($id); 
       $model->status = 2;
      // $model->modified_by = \Yii::$app->user->identity->id;
        $model->modified_date = date("Y-m-d H:i:s");
       if ($model->save(false)) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Deleted Successfully'));
            return $this->redirect('index');
        }
    }

    /**
     * Finds the BulkMail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BulkMail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BulkMail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
