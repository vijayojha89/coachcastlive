<?php

namespace backend\controllers;

use Yii;
use common\models\Menu;
use common\models\MenuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends Controller
{
    
   public function beforeAction($action) {
        
        $auth_item = ucfirst(Yii::$app->controller->id) . 'Controller';
        $getcontroller_id = \common\models\AuthItems::findOne(["auth_items_name" => $auth_item]);

        $menuaccess = \common\models\MenuAccess::findAll(["user_id" => Yii::$app->user->identity->id, "menu_access_type" => "backend", "menu_access_controller" => $getcontroller_id->attributes['auth_items_id']]);
        //print_r($menuaccess);
        
        if (empty($menuaccess) && Yii::$app->user->identity->role=='subAdmin') {

         
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
           
        }

        return true;
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
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Menu model.
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
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Menu();
        $model->created_by = \Yii::$app->user->identity->id;
        $model->created_date = date("Y-m-d H:i:s");
  
           
        if ($model->load(Yii::$app->request->post())) {
                     $gnl = new \common\components\GeneralComponent();
                        $gnl->fileupload(realpath('../../') . '/uploads/', 'menu_image', $model, 'menu_image');
            if ($model->menu_image == '') {
                unset($model->menu_image);
            }
                    $gnl->fileupload(realpath('../../') . '/uploads/', 'menu_background_image', $model, 'menu_background_image');
            if ($model->menu_background_image == '') {
                unset($model->menu_background_image);
            }
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
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->modified_by = \Yii::$app->user->identity->id;
        $model->modified_date = date("Y-m-d H:i:s");
        if ($model->load(Yii::$app->request->post())) {
                     $gnl = new \common\components\GeneralComponent();
                        $gnl->fileupload(realpath('../../') . '/uploads/', 'menu_image', $model, 'menu_image');
            if ($model->menu_image == '') {
                unset($model->menu_image);
            }
                    $gnl->fileupload(realpath('../../') . '/uploads/', 'menu_background_image', $model, 'menu_background_image');
            if ($model->menu_background_image == '') {
                unset($model->menu_background_image);
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

    /**
    *------------ Deactivate the rows -----------------
    */
    public function actionIsActive($id){
        $searchModel = new MenuSearch();
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
     * Deletes an existing Menu model.
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
       if ($model->save(FALSE)) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Deleted Successfully'));
            return $this->redirect('index');
        }
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
