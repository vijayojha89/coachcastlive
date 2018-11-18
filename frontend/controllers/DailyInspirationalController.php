<?php

namespace frontend\controllers;

use Yii;
use frontend\models\TrainerClass;
use frontend\models\TrainerClassSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
/**
 * DailyInspirationalController implements the CRUD actions for TrainerClass model.
 */
class DailyInspirationalController extends Controller
{


   public function beforeAction($action) {
        
        return true;
   }




    /**
     * Lists all TrainerClass models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TrainerClassSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    
    public function actionCommentAdd()
    {
        $commentId = isset($_POST['comment_id']) ? $_POST['comment_id'] : "";
        $comment = isset($_POST['comment']) ? $_POST['comment'] : "";
        $commentSenderName = isset($_POST['name']) ? $_POST['name'] : "";
        $date = date('Y-m-d H:i:s');

        $sql = "INSERT INTO tbl_comment(parent_comment_id,comment,comment_sender_name,date) VALUES ('" . $commentId . "','" . $comment . "','" . $commentSenderName . "','" . $date . "')";
        echo "test";
        die;

    }   
    
    public function actionCommentList()
    {
        echo "RE";
        die;
    }
        /**
     * Displays a single TrainerClass model.
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
     * Creates a new TrainerClass model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TrainerClass();
        $model->created_by = \Yii::$app->user->identity->id;
        $model->created_date = date("Y-m-d H:i:s");
       
        //$model->scenario = 'trainer-classCreate';
           
        if ($model->load(Yii::$app->request->post())) {
                $model->title = ucwords($model->title);
                             $gnl = new \common\components\GeneralComponent();
                         $imageUrl_0 = $gnl->uploadImageS3HB($model,'class_image');
             $model->class_image =  $imageUrl_0;

            if ($model->class_image == '') {
                unset($model->class_image);
            }
           
            $model->save();
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Class Added Successfully'));
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TrainerClass model.
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
        
        //$model->scenario = 'trainer-classUpadte';
        
                if ($model->load(Yii::$app->request->post())) {
                $model->title = ucwords($model->title);
                             $gnl = new \common\components\GeneralComponent();
                        
             $imageUrl_0 = $gnl->uploadImageS3HB($model,'class_image');
             $model->class_image =  $imageUrl_0;

            if ($model->class_image == '') {
                unset($model->class_image);
            }
                    $model->save();
            
                                
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Class Updated Successfully'));
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
        $searchModel = new TrainerClassSearch();
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
     * Deletes an existing TrainerClass model.
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
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Class Deleted Successfully'));
            return $this->redirect('index');
        }
    }

    /**
     * Finds the TrainerClass model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TrainerClass the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TrainerClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
