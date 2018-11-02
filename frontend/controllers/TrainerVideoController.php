<?php

namespace frontend\controllers;

use Yii;
use yii\web\UploadedFile; 
use yii\helpers\Url;
use frontend\models\TrainerVideo;
use frontend\models\TrainerVideoSearch;
use yii\web\Controller;
use yii\data\SqlDataProvider;
use yii\web\Response; 
use yii\widgets\ActiveForm; 
use common\components\GeneralComponent;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
/**
 * TrainerVideoController implements the CRUD actions for TrainerVideo model.
 */
class TrainerVideoController extends Controller
{


   public function beforeAction($action) {
        
        return true;
   }
    /**
     * Lists all TrainerVideo models.
     * @return mixed
     */
    public function actionIndex()
    {
        //Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Data Added Successfully'));
        $searchModel = new TrainerVideoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    
                   
        /**
     * Displays a single TrainerVideo model.
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
     * Creates a new TrainerVideo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TrainerVideo();
        $gnl = new \common\components\GeneralComponent();
        $model->created_by = \Yii::$app->user->identity->id;
        $model->created_date = date("Y-m-d H:i:s");
       
        //$model->scenario = 'trainer-videoCreate';
           
        if ($model->load(Yii::$app->request->post())) 
        {
            $model->title = ucwords($model->title);
            $gnl->fileupload(realpath('../../') . '/uploads/', 'video_image', $model, 'video_image');
            if ($model->video_image == '')
            {
                unset($model->video_image);
            }
        
            $videoUrl_1 = $gnl->uploadVideoS3HB($model,'video_file');
            $model->video_file =  $videoUrl_1;
        
            if ($model->video_file == '') {
                unset($model->video_file);
            }
            $model->save();
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Video Added Successfully'));
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TrainerVideo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        
        $id = \common\components\GeneralComponent::decrypt($id);
        $model = $this->findModel($id);
        $gnl = new \common\components\GeneralComponent();
        $model->modified_by = \Yii::$app->user->identity->id;
        $model->modified_date = date("Y-m-d H:i:s");
        
        if ($model->load(Yii::$app->request->post())) {
                $model->title = ucwords($model->title);
            
            $gnl->fileupload(realpath('../../') . '/uploads/', 'video_image', $model, 'video_image');
            if ($model->video_image == '')
            {
                unset($model->video_image);
            }
                
                $videoUrl_1 = $gnl->uploadVideoS3HB($model,'video_file');
            $model->video_file =  $videoUrl_1;
        
            if ($model->video_file == '') {
                unset($model->video_file);
            }
            
                    $model->save();
            
                                
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Video Updated Successfully'));
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
        $searchModel = new TrainerVideoSearch();
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
     * Deletes an existing TrainerVideo model.
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
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Video Deleted Successfully'));
            return $this->redirect('index');
        }
    }

    /**
     * Finds the TrainerVideo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TrainerVideo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TrainerVideo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionSchedule()
    {
        return $this->render('schedule');
    }
    
    public function actionTransations()
    {
        return $this->render('transactions');
    }
}
