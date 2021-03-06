<?php

namespace backend\controllers;

use Yii;
use common\models\Setting;
use common\models\SettingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;


/**
 * SettingController implements the CRUD actions for Setting model.
 */
class SettingController extends Controller
{
   public function beforeAction($action) {
        
        if ( \Yii::$app->user->identity->role != "superAdmin") {
        $auth_item = ucfirst(Yii::$app->controller->id) . 'Controller';
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
     * Lists all Setting models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SettingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Setting model.
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
     * Creates a new Setting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Setting();
        $model->created_by = \Yii::$app->user->identity->id;
        $model->created_date = date("Y-m-d H:i:s");
        
           
        if ($model->load(Yii::$app->request->post())) {
                     $gnl = new \common\components\GeneralComponent();
                     $imageUrl_setting_logo_image = $gnl->uploadImageS3HB($model,'setting_logo_image');
                     $model->setting_logo_image =  $imageUrl_setting_logo_image;
            if ($model->setting_logo_image == '') {
                unset($model->setting_logo_image);
            }
                    $imageUrl_setting_favicon_image = $gnl->uploadImageS3HB($model,'setting_favicon_image');
                    $model->setting_favicon_image =  $imageUrl_setting_favicon_image;
            if ($model->setting_favicon_image == '') {
                unset($model->setting_favicon_image);
            }
                        $gnl->fileupload_File(realpath('../../') . '/uploads/', 'push_notification_ios', $model, 'push_notification_ios');
            if ($model->push_notification_ios == '') {
                unset($model->push_notification_ios);
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
     * Updates an existing Setting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $id =  \common\components\GeneralComponent::decrypt($id);
            
        $model = $this->findModel($id);
        $model->modified_by = \Yii::$app->user->identity->id;
      $setting_id = $model->getPrimaryKey();

        $model->modified_date = date("Y-m-d H:i:s");
        if ($model->load(Yii::$app->request->post())) {
            $model->push_notification_ios_password = Yii::$app->request->post()['Setting']['push_notification_ios_password'];
                     $gnl = new \common\components\GeneralComponent();
                     $imageUrl_setting_logo_image = $gnl->uploadImageS3HB($model,'setting_logo_image');
                     $model->setting_logo_image =  $imageUrl_setting_logo_image;
            if ($model->setting_logo_image == '') {
                unset($model->setting_logo_image);
            }
                    $imageUrl_setting_favicon_image = $gnl->uploadImageS3HB($model,'setting_favicon_image');
                    $model->setting_favicon_image =  $imageUrl_setting_favicon_image;
            if ($model->setting_favicon_image == '') {
                unset($model->setting_favicon_image);
            }
                        $gnl->fileupload_File(realpath('../../') . '/uploads/', 'push_notification_ios', $model, 'push_notification_ios');
            if ($model->push_notification_ios == '') {
                unset($model->push_notification_ios);
            }
            
            $imageUrl_refer_step1 = $gnl->uploadImageS3HB($model,'refer_step1');
                     $model->refer_step1 =  $imageUrl_refer_step1;
            if ($model->refer_step1 == '') {
                unset($model->refer_step1);
            }
            $imageUrl_refer_step2 = $gnl->uploadImageS3HB($model,'refer_step2');
                     $model->refer_step2 =  $imageUrl_refer_step2;
            if ($model->refer_step2 == '') {
                unset($model->refer_step2);
            }
            $imageUrl_refer_step3 = $gnl->uploadImageS3HB($model,'refer_step3');
                     $model->refer_step3 =  $imageUrl_refer_step3;
            if ($model->refer_step3 == '') {
                unset($model->refer_step3);
            }
                    $model->save(FALSE);
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Updated Successfully'));
            return $this->redirect(['setting/update?id='.\common\components\GeneralComponent::encrypt($setting_id)]);
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
        $searchModel = new SettingSearch();
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
     * Deletes an existing Setting model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id); 
       $model->is_active = 2;
       $model->modified_by = \Yii::$app->user->identity->id;
        $model->modified_date = date("Y-m-d H:i:s");
       if ($model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Data Deleted Successfully'));
            return $this->redirect('../index');
        }
    }

    /**
     * Finds the Setting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Setting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Setting::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
