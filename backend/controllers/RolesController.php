<?php

namespace backend\controllers;

use Yii;
use common\models\Roles;
use common\models\RolesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use common\models\AuthItems;
use common\models\Rolesallocation;

/**
 * RolesController implements the CRUD actions for Roles model.
 */
class RolesController extends Controller
{


   public function beforeAction($action) {
        
        if (!parent::beforeAction($action)) {
        return false;
        }
        else if ( \Yii::$app->user->identity->role != "superAdmin") {
        $auth_item = 'RolesController';
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

    
    public function actionAccess() {
        
//        $Truncate_auths = Yii::$app->db->createCommand("TRUNCATE TABLE  auth_items;")->query();
        
        $model = new AuthItems();
        $model->created_by = \Yii::$app->user->identity->id;
        $model->created_date = date("Y-m-d H:i:s");


        $controllerlist = [];
        if ($handle = opendir('../controllers')) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && substr($file, strrpos($file, '.') - 10) == 'Controller.php') {
                    $controllerlist[] = $file;
                }
            }
            closedir($handle);
        }
        asort($controllerlist);
        $fulllist = [];
        foreach ($controllerlist as $controller):
            $handle = fopen('../controllers/' . $controller, "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    if (preg_match('/public function action(.*?)\(/', $line, $display)):
                        if (strlen($display[1]) > 2):
                            $fulllist[substr($controller, 0, -4)][] = strtolower($display[1]);
                        endif;
                    endif;
                }
            }
            fclose($handle);
        endforeach;
        //print_r($fulllist);exit;
        
        foreach ($fulllist as $key => $value) {
            //print_r($key);
            $model = new AuthItems();

            $model->auth_items_name = $key;
            $model->auth_items_type = 'backend';
            $model->auth_items_action = 'controller';
            $count = $model->findOne(["auth_items_name" => $key, "auth_items_type" => 'backend', "auth_items_action" => "controller"]);

            if (empty($count)) {
                $model->save(false);
               } else {
                //Yii::$app->getSession()->setFlash('success', Yii::t('app', 'No Controllers Available.')); 
                      }
                foreach ($value as $actionName){
                    if($model->auth_items_id){$primary_action_id = $model->auth_items_id;}
                    else{$primary_action_id = $count->auth_items_id;}
                    $modelactions = new AuthItems();
                    $modelactions->parent_auth_item_id = $primary_action_id;
                    $modelactions->auth_items_name = $actionName;
                    $modelactions->auth_items_type = 'backend';
                    $modelactions->auth_items_action = 'action';
                    $count_action = $model->findOne(["parent_auth_item_id"=>$primary_action_id,"auth_items_name" => $actionName, "auth_items_type" => 'backend', "auth_items_action" => "action"]);
                    
                    if (empty($count_action)) {
                    $modelactions->save(false);
                    }else {
                    //Yii::$app->getSession()->setFlash('success', Yii::t('app', 'No Controllers Available.'));
                    }
                }
        }
        return $this->redirect('role-allocation?id='.$_REQUEST['id']);
    }
    
    
    /**
     * Lists all Roles models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RolesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Roles model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $id = \common\components\GeneralComponent::decrypt($id);
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Roles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Roles();
        $model->created_by = \Yii::$app->user->identity->id;
        $model->created_date = date("Y-m-d H:i:s");
        
           
        if ($model->load(Yii::$app->request->post())) {
                        $model->save();
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Added Successfully'));
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Roles model.
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

    /**
    *------------ Deactivate the rows -----------------
    */
    public function actionIsActive($id){
        $searchModel = new RolesSearch();
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
     * Deletes an existing Roles model.
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
            return $this->redirect('index');
        }
    }

     public function actionRoleAllocation()
    {
        $id = \common\components\GeneralComponent::decrypt($_REQUEST['id']);
        $model = new Rolesallocation();
        $checkRole = \common\models\Roles::find()->where(['id' => $id])->count();
        if($checkRole==0){
            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Something Went Wrong!'));
            return $this->redirect(['roles/index']); 
        }
        
        $query = "SELECT * from auth_items where parent_auth_item_id=0 and status = 1";
        $allauthItems = Yii::$app->db->createCommand($query)->queryAll();
//        if(isset($_POST)){echo "<pre>";print_r($_POST);exit;}
        if (Yii::$app->request->post()) {
            if(count($_REQUEST['actions']>0)){
               $rollId =  $_REQUEST['AuthItems']['role_id'];
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
            $model = new AuthItems();
        $model->created_by = \Yii::$app->user->identity->id;
        $model->created_date = date("Y-m-d H:i:s");


        $controllerlist = [];
        if ($handle = opendir('../controllers')) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && substr($file, strrpos($file, '.') - 10) == 'Controller.php') {
                    $controllerlist[] = $file;
                }
            }
            closedir($handle);
        }
        asort($controllerlist);
        $fulllist = [];
        foreach ($controllerlist as $controller):
            $handle = fopen('../controllers/' . $controller, "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    if (preg_match('/public function action(.*?)\(/', $line, $display)):
                        if (strlen($display[1]) > 2):
                            $fulllist[substr($controller, 0, -4)][] = strtolower($display[1]);
                        endif;
                    endif;
                }
            }
            fclose($handle);
        endforeach;
        //print_r($fulllist);exit;
        
        foreach ($fulllist as $key => $value) {
            //print_r($key);
            $model = new AuthItems();

            $model->auth_items_name = $key;
            $model->auth_items_type = 'backend';
            $model->auth_items_action = 'controller';
            $count = $model->findOne(["auth_items_name" => $key, "auth_items_type" => 'backend', "auth_items_action" => "controller"]);

            if (empty($count)) {
                $model->save(false);
               } else {
                //Yii::$app->getSession()->setFlash('success', Yii::t('app', 'No Controllers Available.')); 
                      }
                foreach ($value as $actionName){
                    if($model->auth_items_id){$primary_action_id = $model->auth_items_id;}
                    else{$primary_action_id = $count->auth_items_id;}
                    $modelactions = new AuthItems();
                    $modelactions->parent_auth_item_id = $primary_action_id;
                    $modelactions->auth_items_name = $actionName;
                    $modelactions->auth_items_type = 'backend';
                    $modelactions->auth_items_action = 'action';
                    $count_action = $model->findOne(["parent_auth_item_id"=>$primary_action_id,"auth_items_name" => $actionName, "auth_items_type" => 'backend', "auth_items_action" => "action"]);
                    
                    if (empty($count_action)) {
                    $modelactions->save(false);
                    }else {
                    //Yii::$app->getSession()->setFlash('success', Yii::t('app', 'No Controllers Available.'));
                    }
                }
        }
            return $this->render('create_roleallocation', [
                'model' => $model,
                'allauthItems'=>$allauthItems
            ]);
        }
    }
    
    /**
     * Finds the Roles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Roles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Roles::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
