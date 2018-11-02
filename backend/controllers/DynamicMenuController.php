<?php

namespace backend\controllers;

use Yii;
use common\models\DynamicMenu;
use common\models\DynamicMenuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use common\models\AuthItems;
/**
 * DynamicMenuController implements the CRUD actions for DynamicMenu model.
 */
class DynamicMenuController extends Controller
{


   public function beforeAction($action) {
        
        if (!parent::beforeAction($action)) {
        return false;
        }
        if (Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            return $this->goHome();
        }
        else if ( \Yii::$app->user->identity->role != "superAdmin" && \Yii::$app->user->identity->role != "businessRole") {
        $auth_item = 'DynamicMenuController';
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
        }return true;
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
//                    //'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all DynamicMenu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DynamicMenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    
                         /**
    *------------ sort rows -----------------
    */
      public function actions(){
        return [
            'sortItem' => [
                'class' => \richardfan\sortable\SortableAction::className(),
                'activeRecordClassName' => DynamicMenu::className(),
                'orderColumn' => 'sort',
            ], ];
    }
        public function actionIndexSortable()
    {
        $searchModel = new DynamicMenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-sortable', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
            
        /**
     * Displays a single DynamicMenu model.
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
     * Creates a new DynamicMenu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DynamicMenu();
        $model->created_by = \Yii::$app->user->identity->id;
        $model->created_date = date("Y-m-d H:i:s");
        $model->scenario = 'menuCreate';
        $model->role_id = 3;
//        =======================================add-controllers-start======================================================
/*$model_authitems = new AuthItems();
        $model_authitems->created_by = \Yii::$app->user->identity->id;
        $model_authitems->created_date = date("Y-m-d H:i:s");


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
            $model_authitems = new AuthItems();

            $model_authitems->auth_items_name = $key;
            $model_authitems->auth_items_type = 'backend';
            $model_authitems->auth_items_action = 'controller';
            $count = $model_authitems->findOne(["auth_items_name" => $key, "auth_items_type" => 'backend', "auth_items_action" => "controller"]);

            if (empty($count)) {
                $model_authitems->save(false);
               } else {
                //Yii::$app->getSession()->setFlash('success', Yii::t('app', 'No Controllers Available.')); 
                      }
                foreach ($value as $actionName){
                    if($model_authitems->auth_items_id){$primary_action_id = $model_authitems->auth_items_id;}
                    else{$primary_action_id = $count->auth_items_id;}
                    $modelactions = new AuthItems();
                    $modelactions->parent_auth_item_id = $primary_action_id;
                    $modelactions->auth_items_name = $actionName;
                    $modelactions->auth_items_type = 'backend';
                    $modelactions->auth_items_action = 'action';
                    $count_action = $model_authitems->findOne(["parent_auth_item_id"=>$primary_action_id,"auth_items_name" => $actionName, "auth_items_type" => 'backend', "auth_items_action" => "action"]);
                    
                    if (empty($count_action)) {
                    $modelactions->save(false);
                    }else {
                    //Yii::$app->getSession()->setFlash('success', Yii::t('app', 'No Controllers Available.'));
                    }
                }
        }
 * 
 */
//        =======================================add-controllers-end========================================================
        if ($model->load(Yii::$app->request->post())) {
            echo '<pre>';
        $actions = $_POST['DynamicMenu']['actions'];
        $string_actions = rtrim(implode(',', $actions), ',');

        $key = strtolower($model->label);
        $model->key = str_replace(' ','_',$key);
        $model->label = ucwords($model->label);
        $model->actions = $string_actions;
                        $model->save(false);
            
                        
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Added Successfully'));
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionAdd()
    {
        $model = new DynamicMenu();
        $model->created_by = \Yii::$app->user->identity->id;
        $model->created_date = date("Y-m-d H:i:s");
        $model->scenario = 'menuAdd';
        $model->role_id = 3;
//        =======================================add-controllers-start======================================================
/*$model_authitems = new AuthItems();
        $model_authitems->created_by = \Yii::$app->user->identity->id;
        $model_authitems->created_date = date("Y-m-d H:i:s");


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
            $model_authitems = new AuthItems();

            $model_authitems->auth_items_name = $key;
            $model_authitems->auth_items_type = 'backend';
            $model_authitems->auth_items_action = 'controller';
            $count = $model_authitems->findOne(["auth_items_name" => $key, "auth_items_type" => 'backend', "auth_items_action" => "controller"]);

            if (empty($count)) {
                $model_authitems->save(false);
               } else {
                //Yii::$app->getSession()->setFlash('success', Yii::t('app', 'No Controllers Available.')); 
                      }
                foreach ($value as $actionName){
                    if($model_authitems->auth_items_id){$primary_action_id = $model_authitems->auth_items_id;}
                    else{$primary_action_id = $count->auth_items_id;}
                    $modelactions = new AuthItems();
                    $modelactions->parent_auth_item_id = $primary_action_id;
                    $modelactions->auth_items_name = $actionName;
                    $modelactions->auth_items_type = 'backend';
                    $modelactions->auth_items_action = 'action';
                    $count_action = $model_authitems->findOne(["parent_auth_item_id"=>$primary_action_id,"auth_items_name" => $actionName, "auth_items_type" => 'backend', "auth_items_action" => "action"]);
                    
                    if (empty($count_action)) {
                    $modelactions->save(false);
                    }else {
                    //Yii::$app->getSession()->setFlash('success', Yii::t('app', 'No Controllers Available.'));
                    }
                }
        }
 * 
 */
//        =======================================add-controllers-end========================================================           
        if ($model->load(Yii::$app->request->post())) {
        $actions = $_POST['DynamicMenu']['actions'];
        $string_actions = rtrim(implode(',', $actions), ','); 
        $model->actions = $string_actions;
        $key = strtolower($model->label);
        $model->key = str_replace(' ','_',$key);
        $model->label = ucwords($model->label);
        $model->type = 0;
                        $model->save(false);
            
                        
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Added Successfully'));
            return $this->redirect(['index']);
        } else {
            return $this->render('add', [
                'model' => $model,
            ]);
        }
    }
    public function actionUpdateDropdown($id)
    {
        $id = \common\components\GeneralComponent::decrypt($id);
        $model = $this->findModel($id);
        $model->modified_by = \Yii::$app->user->identity->id;
        $model->modified_date = date("Y-m-d H:i:s");
        $model->scenario = 'menuUpdatedd';
                if ($model->load(Yii::$app->request->post())) {
                    $actions = $_POST['DynamicMenu']['actions'];
                    $string_actions = rtrim(implode(',', $actions), ','); 
                    $model->actions = $string_actions;
                        $model->save(false);
            
                                
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Updated Successfully'));
            return $this->redirect(['index']);
        } else {
            return $this->render('update_add', [
                'model' => $model,
            ]);
        }
    }
    /**
     * Updates an existing DynamicMenu model.
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
        $model->scenario = 'menuUpdate';
                if ($model->load(Yii::$app->request->post())) {
                    $actions = $_POST['DynamicMenu']['actions'];
                    $string_actions = rtrim(implode(',', $actions), ','); 
                    $model->actions = $string_actions;
                        $model->save(false);
            
                                
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
        $searchModel = new DynamicMenuSearch();
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
     * Deletes an existing DynamicMenu model.
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

    /**
     * Finds the DynamicMenu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DynamicMenu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DynamicMenu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionData()
    {
         $out = [];
        if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];
        if ($parents != null) {
        $prj_id = $parents[0];
        
        $out = \common\models\AuthItems::find()->where(["parent_auth_item_id" => $prj_id,'status' => 1])
                                    ->select(['auth_items_id as id','auth_items_name as name'])
                                    ->asArray()->all();
        $data = \yii\helpers\ArrayHelper::map($out, 'id', 'name');
        
        echo \yii\helpers\Json::encode(['output'=>$out, 'selected'=>'']);
        return;
        }
        }
        echo \yii\helpers\Json::encode(['output'=>'', 'selected'=>'']);
    }
}
