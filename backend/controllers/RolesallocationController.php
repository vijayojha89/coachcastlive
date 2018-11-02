<?php

namespace backend\controllers;

use Yii;
use common\models\Rolesallocation;
use common\models\RolesallocationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * RolesallocationController implements the CRUD actions for Rolesallocation model.
 */
class RolesallocationController extends Controller
{


   public function beforeAction($action) {
        
        if ( \Yii::$app->user->identity->role != "superAdmin") {
        $auth_item = 'RolesallocationController';
        $auth_item_action = ucfirst(Yii::$app->controller->action->id) ;
        $auth_action = str_replace('-','',$auth_item_action);
        $getrole = \common\models\Roles::findOne(["role_name" => \Yii::$app->user->identity->role]);
        $getcontroller_id = \common\models\AuthItems::findOne(["auth_items_name" => $auth_item]);
        $getaction_id = \common\models\AuthItems::findOne(["parent_auth_item_id"=>$getcontroller_id->auth_items_id,
                                                           "auth_items_name" => $auth_action]);

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
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Rolesallocation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RolesallocationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Rolesallocation model.
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
     * Creates a new Rolesallocation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Rolesallocation();
        $checkRole = \common\models\Roles::find()->where(['id' => $_REQUEST['id']])->count();
        if($checkRole==0){
            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Something Went Wrong!'));
            return $this->redirect(['roles/index']); 
        }

        $query = "SELECT * from auth_items where parent_auth_item_id=0 and status = 1";
        $allauthItems = Yii::$app->db->createCommand($query)->queryAll();
           
        if ($model->load(Yii::$app->request->post())) {
            if(count($_REQUEST['actions']>0)){
               $rollId =  $_REQUEST['Rolesallocation']['role_id'];
               $deleteAllRoleAccess = Yii::$app->db->createCommand("
                DELETE FROM roles_allocation 
                WHERE role_id = '$rollId' 
                ")->execute();
                foreach($_REQUEST['actions'] as $key=>$valuecontroller){
                    $controllerId = $key;
                    foreach($valuecontroller as $keyaction=>$valueactions){
                        $modelInsert = new Rolesallocation();
                        $modelInsert->created_by = \Yii::$app->user->identity->id;
                        $modelInsert->created_date = date("Y-m-d H:i:s");
                        $actionId = $keyaction;
                        $modelInsert->role_id = $rollId;
                        $modelInsert->controller_id = $controllerId;
                        $modelInsert->action_id =$actionId;
                        $modelInsert->status = 1;
                        $modelInsert->save();
                    }

                }
            }
           
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Added Successfully'));
            return $this->redirect(['roles/index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'allauthItems'=>$allauthItems
            ]);
        }
    }

    /**
     * Updates an existing Rolesallocation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        /*$checkRole = \common\models\Roles::find()->where(['id' => $id])->count();
        if($checkRole==0){
            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Something Went Wrong!'));
            return $this->redirect(['roles/index']); 
        }*/
        $model->modified_by = \Yii::$app->user->identity->id;
        $model->modified_date = date("Y-m-d H:i:s");
        if ($model->load(Yii::$app->request->post())) {
            if(count($_REQUEST['actions']>0)){
               $rollId =  $_REQUEST['Rolesallocation']['role_id'];
               $deleteAllRoleAccess = Yii::$app->db->createCommand("
                DELETE FROM roles_allocation 
                WHERE role_id = '$rollId' 
                ")->execute();
                foreach($_REQUEST['actions'] as $key=>$valuecontroller){
                    $controllerId = $key;
                    foreach($valuecontroller as $keyaction=>$valueactions){
                        $modelInsert = new Rolesallocation();
                        $modelInsert->created_by = \Yii::$app->user->identity->id;
                        $modelInsert->created_date = date("Y-m-d H:i:s");
                        $actionId = $keyaction;
                        $modelInsert->role_id = $rollId;
                        $modelInsert->controller_id = $controllerId;
                        $modelInsert->action_id =$actionId;
                        $modelInsert->status = 1;
                        $modelInsert->save();
                    }

                }
            }
                        //$model->save();
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Updated Successfully'));
            return $this->redirect(['roles/index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
    *------------ Deactivate the rows -----------------
    */
    public function actionIsActive($id){
        $searchModel = new RolesallocationSearch();
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
     * Deletes an existing Rolesallocation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id); 
       $model->status = 2;
       $model->modified_by = \Yii::$app->user->identity->id;
        $model->modified_date = date("Y-m-d H:i:s");
       if ($model->save(false)) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Deleted Successfully'));
            return $this->redirect('index');
        }
    }

    /**
     * Finds the Rolesallocation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Rolesallocation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rolesallocation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
