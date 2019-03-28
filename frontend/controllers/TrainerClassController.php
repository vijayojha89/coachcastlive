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
 * TrainerClassController implements the CRUD actions for TrainerClass model.
 */
class TrainerClassController extends Controller
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

    
                   
        /**
     * Displays a single TrainerClass model.
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

    public function actionBroadcast($id)
    {
        $id = \common\components\GeneralComponent::decrypt($id);
        // $userId = Yii::$app->user->identity->id;
        // $sql = "SELECT * FROM user_class WHERE class_id = '".$model->trainer_class_id."' AND  ORDER BY parent_comment_id asc, class_comment_id asc";
        // $query = \Yii::$app->db->createCommand($sql);
        // $record_set = $query->queryAll();
       
        return $this->render('broadcast', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCommentAdd()
    {
        $sessionId = isset($_POST['class_session_id']) ? $_POST['class_session_id'] : "";
        $commentId = isset($_POST['comment_id']) ? $_POST['comment_id'] : "";
        $comment = isset($_POST['comment']) ? $_POST['comment'] : "";
        $commentSenderName = isset($_POST['name']) ? $_POST['name'] : "";
        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : "";
        $user_image = isset($_POST['user_image']) ? $_POST['user_image'] : "";
        $date = date('Y-m-d H:i:s');

        $sql = "INSERT INTO class_comment(class_session_id,parent_comment_id,comment,user_name,user_id,user_image,created_date) VALUES ('" . $sessionId . "','" . $commentId . "','" . $comment . "','" . $commentSenderName . "','" . $user_id . "','".$user_image."','" . $date . "')";
        $query = \Yii::$app->db->createCommand($sql)->execute();
        return true;
    }   

    public function actionCommentList($id)
    {
        $sql = "SELECT * FROM class_comment WHERE class_session_id = '".$id."' ORDER BY parent_comment_id asc, class_comment_id asc";
        $query = \Yii::$app->db->createCommand($sql);
        $record_set = $query->queryAll();
        echo json_encode($record_set);
        die;
    }

    public function actionOnlineuserList($id)
    {
        $sql = "SELECT * FROM class_online_user WHERE class_online_id = '".$id."' AND status = 1";
        $query = \Yii::$app->db->createCommand($sql);
        $record_set = $query->queryAll();
        echo json_encode($record_set);
        die;
    }

    public function actionBlockuser($id)
    {
        $sql = "SELECT * FROM class_online_user WHERE class_online_user_id = '".$id."'";
        $result = \Yii::$app->db->createCommand($sql)->queryOne();
        if($result)
        {
            Yii::$app->db->createCommand("UPDATE class_online_user SET is_block=1 WHERE class_id =" . $result['class_id']." AND user_id ='".$result['user_id']."'")->execute();
            Yii::$app->db->createCommand("UPDATE user_class SET is_block=1 WHERE class_id =" . $result['class_id']." AND created_by ='".$result['user_id']."'")->execute();
        }
        echo '<pre>';
        print_r($result);
        die;

    }

    public function actionUnblockuser($id)
    {
        $sql = "SELECT * FROM class_online_user WHERE class_online_user_id = '".$id."'";
        $result = \Yii::$app->db->createCommand($sql)->queryOne();
        if($result)
        {
            Yii::$app->db->createCommand("UPDATE class_online_user SET is_block=0 WHERE class_id =" . $result['class_id']." AND user_id ='".$result['user_id']."'")->execute();
            Yii::$app->db->createCommand("UPDATE user_class SET is_block=0 WHERE class_id =" . $result['class_id']." AND created_by ='".$result['user_id']."'")->execute();
        }
        echo '<pre>';
        print_r($result);
        die;

    }

    public function actionLivesession($id)
    {
        $id = \common\components\GeneralComponent::decrypt($id);
        $model = $this->findModel($id);
        $userId = Yii::$app->user->identity->id;
        $sql = "SELECT * FROM user_class WHERE class_id = '".$model->trainer_class_id."' AND created_by='".$userId."'";
        $result = \Yii::$app->db->createCommand($sql)->queryOne();
        if($result)
        {
            return $this->render('livesession', [
                'model' => $model,
                'userClassDetail'=>$result
            ]);
        }
        else
        {
            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'You are not authorized'));
            return $this->redirect(['user/myjoinclass']);

        }
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

                             $gnl->fileupload(realpath('../../') . '/uploads/', 'class_image', $model, 'class_image');
                             if ($model->class_image == '')
                             {
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
                        
             

            $gnl->fileupload(realpath('../../') . '/uploads/', 'class_image', $model, 'class_image');
            if ($model->class_image == '')
            {
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
    
    
    public function actionJoin($id)
    {
        $encodeId = $id;
        $id = \common\components\GeneralComponent::decrypt($id);
        $model = $this->findModel($id);

        $sql = "SELECT * From user_class WHERE created_by = '".\Yii::$app->user->identity->id."' AND class_id=".$model->trainer_class_id;
        $class_Detail = yii::$app->db->createCommand($sql)->queryOne();
       
        if(!$class_Detail)
        {   
            Yii::$app->db->createCommand()->insert('user_class',
            [
                'class_id' => $model->trainer_class_id,
                'title' => $model->title,
                'description' => $model->description,   
                'workout_type_id' => $model->workout_type_id,
                'price' => $model->price,
                'class_image' => $model->class_image,
                'class_image' => $model->class_image,
                'created_by'=> \Yii::$app->user->identity->id,  
                'created_date' => date('Y-m-d H:i:s'),
                'status' => 1,
                'start_date'=> $model->start_date,  
                'end_date'=> $model->end_date,  
                'time'=> $model->time,  
                'trainer_id'=> $model->created_by,  
               
            ])->execute();

                               
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'You have successfully joined class.'));
            return $this->redirect(['view','id' => $encodeId]);
        } 
        else 
        {
            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'You have already joined this class.'));
            return $this->redirect(['view','id' => $encodeId]);
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
