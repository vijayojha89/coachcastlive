<?php
namespace api\modules\v1\controllers;
use yii;
use yii\base\Security;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\rest\ActiveController;
use yii\filters\VerbFilter;
use common\models\User;
use common\components\GeneralComponent;
use common\models\EmailTemplate;
use common\models\Friend;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use common\models\UserInvited;
use frontend\models\DeviceToken;
use frontend\models\Review;
use common\models\Common;
use common\components\TutorComponent;
use common\models\Question;
use common\models\AskAginQuestionsLog;
use common\models\InvitedTutor;

class TutorController extends Controller {

    /**
     * Get tutor list list
     * $view => 0:list,1:detail
     * $question_type => 1:active,2:completed
     */
    public function actionMyAnswers() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $tnl = new TutorComponent();
        $result = [];
        $data = [];
        if (trim(@$post['user_id'])) {

            $usercheck = GeneralComponent::verify_token($post['service_token']);
            if (!$usercheck) {
                $result['status'] = 5;
                $result['message'] = SERVICE_TOKEN_EXPIRE_MSG;
                return $result;
            }
        }

        $filter = [
            'price_type' => (isset($post['price_type']) && $post['price_type'] != '') ? $post['price_type'] : '',
            'budget_range' => (isset($post['budget_range']) && $post['budget_range'] != '') ? $post['budget_range'] : '',
            'qualification_id' => (isset($post['qualification_id']) && $post['qualification_id'] != '') ? $post['qualification_id'] : '',
            'subject_ids' => (isset($post['subject_ids']) && $post['subject_ids'] != '') ? $post['subject_ids'] : '',
            'is_priority_set' => (isset($post['is_priority_set']) && $post['is_priority_set'] != '') ? $post['is_priority_set'] : '',
            'dateorder' => (isset($post['dateorder']) && $post['dateorder'] != '') ? $post['dateorder'] : 2,
            'confirm_select_tutor' => (isset($post['confirm_select_tutor']) && $post['confirm_select_tutor'] != '') ? $post['confirm_select_tutor'] : 0,
        ];
        $tnl = new TutorComponent();
        $data = $tnl->tutor_questions($post['user_id'], $post['page'], $post['question_type'], $post['view'], (isset($post['question_id'])) ? $post['question_id'] : 0, $filter);

        $result['status'] = 1;
        $result['message'] = Yii::t('app', 'My questions list.');
        $result['data'] = $data;

        return $result;
    }

    /**
     * Get student profile
     * user_id (tutor_id),student_id
     */
    public function actionStudentProfile() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $result = [];
        $data = [];
        if (trim(@$post['user_id'])) {

            $usercheck = GeneralComponent::verify_token($post['service_token']);
            if (!$usercheck) {
                $result['status'] = 5;
                $result['message'] = SERVICE_TOKEN_EXPIRE_MSG;
                return $result;
            }
        }

        $tnl = new TutorComponent();
        $data = $tnl->get_student_profile($post['student_id'], 1);

        $result['status'] = 1;
        $result['message'] = Yii::t('app', 'Tutor profile.');
        $result['data'] = $data;

        return $result;
    }

    /**
     * reject question by tutor
     * question_id,user_id
     */
    public function actionRejectQuestion() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $tnl = new TutorComponent();
        $result = [];
        $data = [];

        if (trim(@$post['user_id'])) {

            $usercheck = GeneralComponent::verify_token($post['service_token']);
            if (!$usercheck) {
                $result['status'] = 5;
                $result['message'] = SERVICE_TOKEN_EXPIRE_MSG;
                return $result;
            }
        }
        if ($data = $tnl->reject_question($post['question_id'], $post['user_id'])) {
            if ($data == 1) {
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'Question rejected.');
            } else if ($data == 3) {
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'This question is not available anymore.');
            } else if ($data == 2) {
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'You are already confirmed.');
            } else if ($data == 4) {
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'You are already rejected.');
            }
        }

        return $result;
    }

    /**
     * review for tutor
     * service_token,user_id,tutor_id,review_opt,comment,rating,question_id
     */
    public function actionAddStudentReview() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $result = [];
        $data = [];
        if (trim(@$post['user_id'])) {

            $usercheck = GeneralComponent::verify_token($post['service_token']);
            if (!$usercheck) {
                $result['status'] = 5;
                $result['message'] = SERVICE_TOKEN_EXPIRE_MSG;
                return $result;
            }
        }
        $review = Review::find()->where(['question_id' => $post['question_id'], 'posted_by' => $post['user_id'], 'posted_for' => $post['student_id'], 'status' => 1])->one();
        if (empty($review)) {
            $user_by = User::findOne($post['user_id']);
            $user_for = User::findOne($post['student_id']);
            $model = new Review();
            $model->review_opt = $post['review_opt'];
            $model->comment = $post['comment'];
            $model->rating = $post['rating'];
            $model->posted_by = $post['user_id'];
            $model->posted_for = $post['student_id'];
            $model->question_id = $post['question_id'];
            $model->created_date = date("Y-m-d H:i:s");
            $model->posted_by_role = $user_by['role'];
            $model->posted_for_role = $user_for['role'];
            $model->save(FALSE);

            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'Review added.');
        } else {
            $result['status'] = 0;
            $result['message'] = Yii::t('app', 'Already added.');
        }


        return $result;
    }

    /**
     * tutor accepts to answer the question
     * service_token,user_id,tutor_id,question_id
     */
    public function actionAcceptQuestion() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $tnl = new TutorComponent();
        $result = [];
        $data = [];
        if (trim(@$post['user_id'])) {

            $usercheck = GeneralComponent::verify_token($post['service_token']);
            if (!$usercheck) {
                $result['status'] = 5;
                $result['message'] = SERVICE_TOKEN_EXPIRE_MSG;
                return $result;
            }
        }

        if ($data = $tnl->accept_question($post['question_id'], $post['user_id'], $post['tutor_bid_amount'])) {
            if ($data == 0) {
                $result['status'] = 0;
                $result['message'] = Yii::t('app', 'Bid amount is invalid.');
            } else if ($data == 1) {
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'Question accepted.');
            } else if ($data == 3) {
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'This question is not available anymore.');
            } else if ($data == 2) {
                $result['status'] = 1;
                $result['message'] = Yii::t('app', 'Student has selected another tutor.');
            }
        }

        return $result;
    }

    /**
     * tutor requests expertide
     * service_token,user_id,expertise
     */
    public function actionRequestExpertise() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $tnl = new TutorComponent();
        $result = [];
        $data = [];
        if (trim(@$post['user_id'])) {

            $usercheck = GeneralComponent::verify_token($post['service_token']);
            if (!$usercheck) {
                $result['status'] = 5;
                $result['message'] = SERVICE_TOKEN_EXPIRE_MSG;
                return $result;
            }
        }

        if (isset($post['user_id']) && isset($post['expertise'])) {

            $tnl = new TutorComponent();
            $tnl->add_expertise($post);
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'Expertise requested.');
        }

        return $result;
    }

    public function actionRemoveNotification($id) {
        $mnl = new \common\components\MasterComponent();
        $data = $mnl->remove_notification($id);
    }
    
    public function actionFinancialTransaction()
    {
        $user_id = $_REQUEST['user_id'];
        $page = $_REQUEST['page'];
        $start = PAGE_SIZE * ($page - 1);
        
        if($page > 0)
        {
            $page_condition = " LIMIT " . $start . ", " . PAGE_SIZE;
        }
        else
        {
            $page_condition = ""; 
        }
        
        
        if(@$_REQUEST['dateorder'] == 1)
        {
            $orderby = 'q.asked_date ASC';
        }   
        else if(@$_REQUEST['dateorder'] == 2)
        {
            $orderby = 'q.asked_date DESC';
        }    
        else
        {
            $orderby = 'q.question_id DESC';
            
        }   
        
        
        $where = "";
        if($_REQUEST['user_id'])
        {
            $where .=" AND q.confirm_select_tutor = $user_id"; 
        } 
        
        if(trim($_REQUEST['price_type']))
        {
                 $price_type = $_REQUEST['price_type'];
                 
                 $where .=" AND q.price_type = $price_type";
                 
                 if($price_type == 2)
                 {
                     if(@$_REQUEST['range_min_max'])
                     {
                         $range_array = explode(',', trim($_REQUEST['range_min_max']));
                         
                         $where .= " AND (confirm_bid BETWEEN $range_array[0] AND $range_array[1] )";
                         
                     }    
                     
                 }    
        }    
        
        if(@$_REQUEST['from_date'] AND @$_REQUEST['to_date'])
        {    
                $fromdate = date('Y-m-d',strtotime($_REQUEST['from_date']));
                $todate = date('Y-m-d',strtotime($_REQUEST['to_date']));
                $where .= " AND DATE(asked_date) >='$fromdate' AND DATE(asked_date) <= '$todate'";
        }
        
        if(@$_REQUEST['export']==1)
        {
            $page_condition = "";
        }
        
        $query  = "SELECT q.*,qf.name as qualification_name,s.name as subject_name,t.studypad_txn_id,q.created_by as student_id,CONCAT(u.first_name,' ',u.last_name) as student_name FROM question q 
                   LEFT JOIN qualification  qf ON q.qualification_id = qf.qualification_id
                   LEFT JOIN subject s ON q.subject_id = s.subject_id
                   LEFT JOIN user u ON q.created_by = u.id
                   LEFT JOIN transaction t ON q.question_id = t.question_id
                   WHERE q.status != 2 $where 
                   ORDER BY $orderby $page_condition";
        $query_result = \Yii::$app->db->createCommand($query)->queryAll();
        $query  = "SELECT COUNT(*) FROM question q WHERE q.status != 2 $where";
        $total_records = \Yii::$app->db->createCommand($query)->queryScalar();
        if($query_result)
        {
            $transaction_detail = array();
            $i = 0;
            foreach ($query_result as $value) {
                $transaction_detail[$i]['question_id'] = (integer)$value['question_id'];
                $transaction_detail[$i]['title'] = (string)$value['title'];
                $transaction_detail[$i]['description'] = (string)$value['description'];
                $transaction_detail[$i]['qualification_name'] = (string)$value['qualification_name'];
                $transaction_detail[$i]['subject_name'] = (string)$value['subject_name'];
                $transaction_detail[$i]['asked_date'] = (string)\common\components\GeneralComponent::date_format(['asked_date']);
                $transaction_detail[$i]['price'] = (string)\common\components\GeneralComponent::front_priceformat($value['confirm_bid']);
                $transaction_detail[$i]['question_status'] = $value['question_status'];
                $transaction_detail[$i]['student_name'] = $value['student_name'];
                $transaction_detail[$i]['question_status'] = $value['question_status'];
                $transaction_detail[$i]['studypad_txn_id'] = $value['studypad_txn_id'];
                $tnl = new TutorComponent();
                $transaction_detail[$i]['student_detail'] = $tnl->get_student_profile($value['student_id'],1);
                $i++;
            }
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'success.');
            $result['data'] = $transaction_detail;
            $result['total_records'] = $total_records;
            $result['page_size'] = PAGE_SIZE;
            $result['page_no'] = $page;
        } 
        else
        {
            $result['status'] = 1;
            $result['message'] = Yii::t('app', 'No record found.');
            $result['data'] = array();
        }    
        
        
        if(@$_REQUEST['export']==1)
        {
        
            $filename = 'financials-transactions_' .  date('m/d/Y').'.csv';
            $fp = fopen('php://output', 'w');
            ob_clean();
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename='.$filename);
            fputcsv($fp, ['Question Title','Qualification','Subject','Date','Price','Transaction ID','Status','Student']);
            if($transaction_detail)
            {
                foreach ($transaction_detail as $value) {
                    $csvdata = array(   
                                        $value['title'],
                                        $value['qualification_name'],
                                        $value['subject_name'],
                                        $value['asked_date'],
                                        $value['price'],
                                        $value['studypad_txn_id'],
                                        $value['question_status'],
                                        $value['student_name']
                                    );
                    fputcsv($fp, $csvdata);
                }
            }
            fclose($fp);
            exit;
        }
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $result;
    }
    
    
    public function actionInvoices()
    {
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        
        $user_id = $post['user_id'];
        
        $page = $_REQUEST['page'];
        $start = PAGE_SIZE * ($page - 1);
        
        if($page > 0)
        {
            $page_condition = " LIMIT " . $start . ", " . PAGE_SIZE;
        }
        else
        {
            $page_condition = ""; 
        }
        
        
        $userallinvoice = Yii::$app->db->createCommand("SELECT * FROM invoice WHERE user_id = '$user_id' $page_condition")->queryAll();
        $total_records = \Yii::$app->db->createCommand("SELECT COUNT(*) FROM invoice WHERE user_id = '$user_id'")->queryScalar();
        $result = array();
        if($userallinvoice)
        {
            
            $invoice_data = array();
            $i = 0;
            foreach ($userallinvoice as $value)
            {
             
                $invoice_data[$i]['invoice_number'] = '#'.$value['zoho_invoice_number'];
                $invoice_data[$i]['date'] = \common\components\GeneralComponent::date_format($value['zoho_date']);;
                $invoice_data[$i]['status'] = ucfirst($value['zoho_status']);
                $invoice_data[$i]['total'] = \common\components\GeneralComponent::front_priceformat($value['zoho_total']);
                
                $invoice_item_data = array();
                $j = 0;
                
                $line_item_detail = unserialize($value['zoho_line_items']); 
                if($line_item_detail)
                { 
                    foreach ($line_item_detail as $lvalue) 
                    {
                        $name = trim($lvalue->name);
                        $description = trim($lvalue->description);
                        if($name)
                        {
                            $studypad_txn_id = $name;
                        }
                        else
                        {
                             $studypad_txn_id = $description;
                        }    

                        $transaction_detail = Yii::$app->db->createCommand("SELECT * FROM transaction WHERE studypad_txn_id = '$studypad_txn_id'")->queryOne();
                        if($transaction_detail)
                        {
                            $question_detail = \common\models\Question::findOne($transaction_detail['question_id']);
                            $user_detail = \common\models\User::findOne($question_detail['created_by']);
                            $qualification_data = \common\models\Qualification::findOne($question_detail['qualification_id']);
                            $subject_data = \common\models\Subject::findOne($question_detail['subject_id']);
                        }  

                        $studentname = (trim(@$user_detail['first_name'].' '.@$user_detail['last_name']))?trim(@$user_detail['first_name'].' '.@$user_detail['last_name']):'-';
                        $subjectname = (@$subject_data['name'])?@$subject_data['name']:'-';
                        $qualificationname = (@$qualification_data['name'])?@$qualification_data['name']:'-';
                        $date = (@$question_detail->asked_date)?\common\components\GeneralComponent::date_format(@$question_detail->asked_date):'-';
                        $transactionid = (@$transaction_detail['studypad_txn_id'])?$transaction_detail['studypad_txn_id']:'-';
                        $price = ($lvalue->item_total)?\common\components\GeneralComponent::front_priceformat($lvalue->item_total):'-';
                        
                        $invoice_item_data[$j]['student_name'] = $studentname;
                        $invoice_item_data[$j]['qualification_name'] = $qualificationname;
                        $invoice_item_data[$j]['subject_name'] = $subjectname;
                        $invoice_item_data[$j]['date'] = $date;
                        $invoice_item_data[$j]['status'] = ucfirst($value['zoho_status']);
                        $invoice_item_data[$j]['studypad_txn_id'] = $transactionid;
                        $invoice_item_data[$j]['price'] = $price;
                        $j++;
                        
                    }
                }
                
                $invoice_data[$i]['line_item_detail'] = $invoice_item_data;
                
                $i++;
            }
            
            $result['status'] = 1;
            $result['message'] = "success";
            $result['data'] = $invoice_data;
            
            $result['total_records'] = $total_records;
            $result['page_size'] = PAGE_SIZE;
            $result['page_no'] = $page;
            
        }
        else
        {
            $result['status'] = 1;
            $result['message'] = "No record found";
            $result['data'] = array();
        }
        
        return $result;
    }   
    
    
}
?>