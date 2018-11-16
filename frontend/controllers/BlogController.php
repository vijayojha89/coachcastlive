<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Blog;
use frontend\models\BlogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
/**
 * BlogController implements the CRUD actions for Blog model.
 */
class BlogController extends Controller
{

     public function beforeAction($action) {
        
        return true;
   }

    /**
     * Lists all Blog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BlogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    
                   
        /**
     * Displays a single Blog model.
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
     * Creates a new Blog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Blog();
        $model->created_by = \Yii::$app->user->identity->id;
        $model->created_date = date("Y-m-d H:i:s");
       
        //$model->scenario = 'blogCreate';
           
        if ($model->load(Yii::$app->request->post())) {
                $model->title = ucwords($model->title);
                             $gnl = new \common\components\GeneralComponent();
                      
             $gnl->fileupload(realpath('../../') . '/uploads/', 'blog_image', $model, 'blog_image');
             if ($model->blog_image == '')
             {
                 unset($model->blog_image);
             }


                    $model->save();
            
                        
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Blog Added Successfully'));
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Blog model.
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
        
        //$model->scenario = 'blogUpadte';
        
                if ($model->load(Yii::$app->request->post())) {
                $model->title = ucwords($model->title);
                             $gnl = new \common\components\GeneralComponent();
                        
             $imageUrl_0 = $gnl->uploadImageS3HB($model,'blog_image');
             $model->blog_image =  $imageUrl_0;

            if ($model->blog_image == '') {
                unset($model->blog_image);
            }
                    $model->save();
            
                                
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Blog Updated Successfully'));
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
        $searchModel = new BlogSearch();
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
     * Deletes an existing Blog model.
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
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Blog Deleted Successfully'));
            return $this->redirect('index');
        }
    }

    /**
     * Finds the Blog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Blog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Blog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
