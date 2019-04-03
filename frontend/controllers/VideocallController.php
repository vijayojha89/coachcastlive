<?php

namespace frontend\controllers;

use Yii;
use frontend\models\User;
use common\models\UserSearch;
use frontend\models\AppointmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\components\GeneralComponent;
use yii\data\SqlDataProvider;

/**
 * UserController implements the CRUD actions for User model.
 */
class VideocallController extends Controller {
    
    
    /*   

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
      //         'delete' => ['post'],
      ],
      ],
      ];
      }
     *
     */

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'signup-user', 'signup-trainer'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

  

   

   
    public function actionIndex($id)
    {
        $id = \common\components\GeneralComponent::decrypt($id);
        $sql = "SELECT * FROM appointment_confirm WHERE appointment_id = '".$id."' AND status = 1";
        $result = \Yii::$app->db->createCommand($sql)->queryOne();
        return $this->render('index', [
            'appointmentDetail'=>$result,
        ]); 
    }   
   
 
    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    
    
    /**
     * Cancel appointment
     */
    
   
    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
