<?php

namespace backend\controllers;

use Yii;
use common\models\AuthItems;
use common\models\AuthItemsSearch;
use common\models\Roles;
use common\models\RolesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * AuthItemsController implements the CRUD actions for AuthItems model.
 */
class AuthItemsController extends Controller {

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
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all AuthItems models.
     * @return mixed
     */
    public function actionIndex() {
        
        
        $searchModel = new AuthItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AuthItems model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AuthItems model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        
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
//            echo "<pre>";
//            print_r($count->auth_items_id);exit;
            
            if (empty($count)) {
                $model->save(false);
               } else {
                Yii::$app->getSession()->setFlash('success', Yii::t('app', 'No Controllers Available.')); 
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
                Yii::$app->getSession()->setFlash('success', Yii::t('app', 'No Controllers Available.'));
                    }
                }
            
        }


//        if ($model->load(Yii::$app->request->post())) {
//                        $model->save();
//            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Added Successfully'));
//            return $this->redirect(['index']);
//        } else {
        return $this->render('create', [
                    'model' => $model,
        ]);
//        }
    }

    /**
     * Updates an existing AuthItems model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
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
     * ------------ Deactivate the rows -----------------
     */
    public function actionIsActive($id) {
        $searchModel = new AuthItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $this->findModel($id);
        if ($model->status == 0) {
            $model->status = 1;
            $message = Yii::t('app', 'Record Activated Successfully');
        } else {
            $model->status = 0;
            $message = Yii::t('app', 'Record De-activated Successfully');
        }
        if ($model->save(false)) {
            //Yii::$app->getSession()->setFlash('success', $message);
            return $this->renderAjax('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Deletes an existing AuthItems model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        if ($model->delete()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Deleted Successfully'));
            return $this->redirect('index');
        }
    }

    /**
     * Finds the AuthItems model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AuthItems the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = AuthItems::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionAllocatelist() {
        $model = new AuthItems();
        $searchModel = new AuthItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
         $searchModelRole = new RolesSearch();
        $query = "SELECT * from roles where status = 1";
        $dropdown = Yii::$app->db->createCommand($query)->queryAll();
      
        return $this->render('allocation', [
                    'model'=>$model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'allRoles'=> $dropdown
        ]);
    }

}
