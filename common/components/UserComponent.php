<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\db\Query;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;
//use frostealth\yii2\aws\s3\interfaces\Service;
use yii\helpers\Json;
use iisns\markdown\Markdown;
use common\models\User;
use common\models\FavTutor;
use common\models\Question;
use common\models\QuestionDocument;
use yii\helpers\ArrayHelper;
use common\models\Chat;
use common\components\GeneralComponent;
use common\components\NotificationComponent;

class UserComponent extends Component {

    public function fav_tutor($student_id) {
        $gnl = new GeneralComponent();
        $tutor_array = yii::$app->db->createCommand("SELECT user.* FROM fav_tutor
                                                   LEFT JOIN `user`
                                                   ON fav_tutor.tutor_id = user.id
                                                   WHERE fav_tutor.student_id = $student_id AND user.id > 0 ")->queryAll();

        if (!empty($tutor_array)) {

            foreach ($tutor_array as $tutor) {
                if ($tutor['role'] == 'student') {
                    $role = 1;
                } else if ($tutor['role'] == 'tutor') {
                    $role = 2;
                }
                $userobj = new User();
                $rattingdata = $userobj->usergetrating($tutor['id']);


                $data[] = [
                    'tutor_id' => (int) $tutor['id'],
                    'first_name' => (string) $tutor['first_name'],
                    'last_name' => (string) $tutor['last_name'],
                    'email' => (string) $tutor['email'],
                    'profile_photo' => (string) $gnl->image_not_found_api_main('profile_photo', $tutor['profile_photo']),
                    'profile_photo_thumb' => (string) $gnl->image_not_found_api_thumb('profile_photo', $tutor['profile_photo']),
                    'subjects' => (string) $this->user_subjects($tutor['id']),
                    'company_name' => (string) "", //$tutor['company_name'],
                    'tutor_rating' => $rattingdata['avg_rating'],
                    'tutor_no_of_user_rate' => (string) $rattingdata['no_of_user'],
                    'role' => (int) $role,
                ];
            }
            return $data;
        } else {
            return [];
        }
    }

    public function recommended_trainer($student_id, $subject_id, $page) {
        $gnl = new GeneralComponent();
        $start = PAGE_SIZE * ($page - 1);
        $trainer_array_data = yii::$app->db->createCommand("SELECT u.* FROM user u WHERE u.role = 'trainer' AND u.status = 1")->queryAll();
        $data['total_records'] = count($trainer_array_data);
        $data['page_size'] = PAGE_SIZE;
        $data['page_no'] = $page;
        $data['recommended_trainer'] = $trainer_array_data;
        return $data;
    }

    /*
     * $view => 0:list,1:detail
     * $question_type => 1:active,2:completed,3:expired
     */

    public function student_questions($student_id, $page, $question_type, $view, $question_id, $filter) {
        $gnl = new GeneralComponent();
        $start = PAGE_SIZE * ($page - 1);
        $dateorder = " ORDER BY `asked_date` DESC";

        if ($page > 0) {
            $page_condition = " LIMIT " . $start . ", " . PAGE_SIZE;
        } else {
            $page_condition = "";
        }
        if ($view == 1) {
            $view_condition = " AND `question_id` = $question_id ";
        } else {
            $view_condition = "";
        }
        if ($filter['price_type'] != "") {
            $price_type_condition = " AND `price_type` = " . $filter['price_type'] . " ";
        } else {
            $price_type_condition = "";
        }
        if ($filter['budget_range'] != "") {
            list($min_budget, $max_budget) = explode(',', $filter['budget_range']);
            $budget_range_condition = " AND ((min_budget BETWEEN $min_budget AND $max_budget ) OR (max_budget BETWEEN $min_budget AND $max_budget))";
        } else {
            $budget_range_condition = "";
        }
        if ($filter['qualification_id'] != "") {
            $qualification_condition = " AND `qualification_id` = " . $filter['qualification_id'] . " ";
        } else {
            $qualification_condition = "";
        }
        if ($filter['subject_ids'] != "") {
            $subject_condition = " AND `subject_id` IN (" . $filter['subject_ids'] . ")";
        } else {
            $subject_condition = "";
        }
        if ($filter['confirm_select_tutor'] == 1) {
            $confirm_select_tutor = " AND `confirm_select_tutor` <> 0";
        } else {
            $confirm_select_tutor = "";
        }
        if ($filter['dateorder'] == 1) {
            $dateorder = "  ORDER BY `asked_date` ASC";
        }
        if ($filter['is_priority_set'] != "") {
            $priority_condition = " AND `is_priority_set` = " . $filter['is_priority_set'] . " ";
        } else {
            $priority_condition = "";
        }
        $filter_condition = $price_type_condition . $budget_range_condition . $qualification_condition . $subject_condition . $priority_condition . $confirm_select_tutor;

        if ($question_type == 1) {
            $question_array_sql = "SELECT * FROM question WHERE created_by = " . $student_id . " AND  question_status = 1  AND status = 1 
                               AND ((TIMESTAMPDIFF(Minute, question.`asked_date`, '" . date('Y-m-d H:i:s') . "') <= (`time_limit`*60))  OR (question.confirm_select_tutor != 0)) " . $filter_condition;
            $question_sql = "SELECT question.*,
                         (`time_limit`*60) - TIMESTAMPDIFF(Minute, question.`asked_date`, '" . date('Y-m-d H:i:s') . "') as time_diff,
                         (SELECT name FROM qualification where qualification.qualification_id = question.qualification_id) as qualification_name,
                         (SELECT name FROM subject where subject.subject_id = question.subject_id) as subject_name
                         FROM question WHERE created_by = " . $student_id . " AND  question_status = 1 $view_condition AND status = 1 AND ((TIMESTAMPDIFF(Minute, question.`asked_date`, '" . date('Y-m-d H:i:s') . "') <= (`time_limit`*60))  OR (question.confirm_select_tutor != 0)) $filter_condition
                         $dateorder" . $page_condition;
        } else if ($question_type == 2) {
            $question_array_sql = "SELECT * FROM question WHERE created_by = " . $student_id . " AND  question_status IN (2,4,5,6,7)  AND status = 1 " . $filter_condition;
            $question_sql = "SELECT question.*,
                          (`time_limit`*60) - TIMESTAMPDIFF(Minute, question.`asked_date`, '" . date('Y-m-d H:i:s') . "') as time_diff,
                         (SELECT name FROM qualification where qualification.qualification_id = question.qualification_id) as qualification_name,
                         (SELECT name FROM subject where subject.subject_id = question.subject_id) as subject_name
                         FROM question WHERE created_by = " . $student_id . " AND  question_status IN (2,4,5,6,7) $view_condition AND status = 1 $filter_condition 
                         $dateorder" . $page_condition;
        } else if ($question_type == 3) {
            $question_array_sql = "SELECT * FROM question WHERE created_by = " . $student_id . " AND confirm_select_tutor = 0 AND  question_status = 1  AND status = 1 $view_condition AND TIMESTAMPDIFF(Minute, question.`asked_date`, '" . date('Y-m-d H:i:s') . "') > (`time_limit`*60)" . $filter_condition;
            $question_sql = "SELECT question.*,
                          (`time_limit`*60) - TIMESTAMPDIFF(Minute, question.`asked_date`, '" . date('Y-m-d H:i:s') . "') as time_diff,
                         (SELECT name FROM qualification where qualification.qualification_id = question.qualification_id) as qualification_name,
                         (SELECT name FROM subject where subject.subject_id = question.subject_id) as subject_name
                         FROM question 
                         WHERE created_by = " . $student_id . " AND confirm_select_tutor = 0 AND question_status IN (1,3)  AND status = 1 $view_condition AND TIMESTAMPDIFF(Minute, question.`asked_date`, '" . date('Y-m-d H:i:s') . "') > (`time_limit`*60) $filter_condition
                         $dateorder" . $page_condition;
        }
        //echo $question_array_sql;exit;
        $question_array = yii::$app->db->createCommand($question_sql)->queryAll();
        $question_array_data = yii::$app->db->createCommand($question_array_sql)->queryAll();

        if (!empty($question_array)) {
            foreach ($question_array as $question) {
                if ($question['question_status'] == 2) {
                    $mnl = new MasterComponent();
                    $payment = $mnl->payment_detail($question['question_id']);
                }
                $answer = [];
                $tutor_details = ($view == 1 && $question['confirm_select_tutor'] == 0) ? $this->get_tutor_for_question($question['question_id'], $student_id, $question_type) : [];

                if ($view == 1 && $question['confirm_select_tutor'] != 0 && ($question['question_status'] == 2 || $question['question_status'] == 1)) {
                    $answer_statement = Chat::find()->where(['chat_id' => $question['answer_id']])->One();
                    if (!empty($answer_statement)) {
                        $answer = [
                            "answer_id" => (int) $answer_statement['chat_id'],
                            "answer_type" => (int) $answer_statement['message_type'],
                            "answer_text" => (string) $answer_statement['message'],
                            "file_name" => (string) $gnl->image_not_found_api_main('chat_file', $answer_statement['file_name']),
                            "file_name_original" => (string) $answer_statement['file_original_name'],
                        ];
                    }
                }
                $is_tutor_confirmed = 0;
                if ($question['confirm_select_tutor'] != 0) {
                    $is_tutor_confirmed = 1;
                }
                $questions[] = [
                    'question_id' => (int) $question['question_id'],
                    'title' => (string) $question['title'],
                    'description' => (string) $question['description'],
                    'time_limit' => (int) $question['time_limit'],
                    'is_priority_set' => (int) $question['is_priority_set'],
                    'qualification_id' => (int) $question['qualification_id'],
                    'qualification_name' => (string) $question['qualification_name'],
                    'subject_id' => (int) $question['subject_id'],
                    'subject_name' => (string) $question['subject_name'],
                    'price_type' => (int) $question['price_type'],
                    'price' => (string) $question['price'],
                    'min_budget' => (string) $question['min_budget'],
                    'max_budget' => (string) $question['max_budget'],
                    //'confirm_bid'=>(string) $question['confirm_bid'],
                    'bid_status' => (int) $question['bid_status'],
                    'asked_date' => (string) strtotime($question['asked_date']),
                    'current_date' => (string) time(),
                    'time_diff' => (int) $question['time_diff'],
                    'completed_date' => ($question['question_status'] == 2 || $question['question_status'] == 4 || $question['question_status'] == 5 || $question['question_status'] == 6 || $question['question_status'] == 7) ? (string) strtotime($question['completed_date']) : "",
                    'documents' => $this->get_question_docs($question['question_id']),
                    'is_tutor_confirmed' => (int) $is_tutor_confirmed,
                    'confirmed_tutor_id' => (int) $question['confirm_select_tutor'],
                    'answer' => $answer,
                    'confirmed_tutor_details' => ($question['confirm_select_tutor'] != 0) ? $this->get_tutor_profile($question['confirm_select_tutor'], $student_id, $question['question_id']) : (object) array(),
                    'role' => 2,
                    'tutor_details' => $tutor_details,
                    'payment_type' => (string) $payment['payment_type'],
                    'payment_amount' => (int) $payment['amount'],
                    'question_status' => (int) $question['question_status'],
                ];
            }
        } else {
            $questions = [];
        }

        $data['total_records'] = count($question_array_data);
        $data['page_size'] = PAGE_SIZE;
        $data['page_no'] = $page;
        $data['questions'] = $questions;
        return $data;
    }
/*
 * if $flag == 1 then return ids
 */
    public function user_subjects($user_id,$flag = 0) {
        $subject_ids = yii::$app->db->createCommand("SELECT subject.* FROM subject
                                                   LEFT JOIN `student_tutor_subject`
                                                   ON subject.subject_id = student_tutor_subject.subject_id
                                                   WHERE student_tutor_subject.user_id = $user_id ")->queryAll();
        $i = 0;
        foreach ($subject_ids as $value) {
            $listarray[$i] = ($flag == 1)?$value['subject_id']:$value['name'];
            $i++;
        }
        $subject_names = rtrim(implode(', ', $listarray), ',');
        return $subject_names;
    }

    public function get_columnval_from_id($tbl, $primary_key, $primary_key_val, $col) {
        $data = yii::$app->db->createCommand("SELECT $col FROM $tbl
                                              WHERE $primary_key = $primary_key_val ")->queryOne();
        return $data[$col];
    }

    public function get_question_docs($question_id) {
        $docs = QuestionDocument::find()->where(['question_id' => $question_id, 'status' => 1])->asArray()->All();
        $gnl = new GeneralComponent();
        $data = [];
        $i = 0;
        foreach ($docs as $doc) {
            $data[$i]['document_name'] = (string) $gnl->image_not_found_api_main('question_document', $doc['document_name']);
            $data[$i]['original_name'] = (string) $doc['original_name'];
            /* if($doc['document_type'] == 1){
              $data[$i]['document_name_thumb'] = (string) $gnl->image_not_found_api_thumb('document_name', $doc['document_name']);
              }
             * 
             */
            $i++;
        }
        return $data;
    }

    /*
     * $question_type
     * 1 : tutor detail who are invited and accepted for question
     * 2 : tutor detail whom student selected for answer
     */

    public function get_tutor_for_question($question_id, $student_id, $question_type) {
        $gnl = new GeneralComponent();
        $data = [];
        if ($question_type == 2) {
            $question_type_condition = "AND `invited_tutor`.is_confirmed = 1";
        } else if ($question_type == 1) {
            $question_type_condition = "";
        } else {
            return (object) array();
        }

        $tutor_sql = "SELECT user.* ,invited_tutor.* ,if( fav_tutor.fav_tutor_id is NULL,0,1) as is_favourite
                                    FROM invited_tutor
                                    LEFT JOIN `user`
                                    ON invited_tutor.tutor_id = user.id 
                                    LEFT JOIN fav_tutor ON fav_tutor.tutor_id = invited_tutor.tutor_id 
                                    WHERE invited_tutor.question_id = $question_id AND 
                                          invited_tutor.status = 1 AND 
                                          invited_tutor.tutor_requst_status = 1 
                                          AND fav_tutor.student_id = $student_id $question_type_condition";

        $tutor_array = yii::$app->db->createCommand($tutor_sql)->queryAll();
        // print_r($tutor_array);exit;
        $i = 0;
        foreach ($tutor_array as $tutor) {
            $data[$i] = $this->get_tutor_profile($tutor['id'], $student_id, $question_id);
            $i++;
        }
        return $data;
    }

    /*
     * get tutor profile
     */

    public function get_tutor_profile($tutor_id, $student_id, $question_id = 0) {
        $gnl = new GeneralComponent();
        $data = [];

        $tutor_sql = "SELECT user.* , 
                   IF((SELECT fav_tutor_id FROM fav_tutor WHERE fav_tutor.tutor_id = $tutor_id AND fav_tutor.student_id = $student_id  LIMIT 1), 1, 0) as is_favourite,
                     (SELECT COUNT(*) FROM `question` WHERE `confirm_select_tutor` = $tutor_id AND `question_status` = 2) as questions_answered,
                     (SELECT tutor_bid_amount FROM `invited_tutor` WHERE `tutor_id` = $tutor_id AND `question_id` = $question_id) as tutor_bid_amount
                                    FROM user
                                    WHERE  user.id =  $tutor_id ";

        $tutor = yii::$app->db->createCommand($tutor_sql)->queryOne();
        if ($tutor) {
            $rating_data = new User();
            $rating = $rating_data->usergetrating($tutor_id);
            $userobj = new User();
            $rattingdata = $userobj->usergetrating($tutor['id']);
            if ($tutor['role'] == 'student') {
                $role = 1;
            } else if ($tutor['role'] == 'tutor') {
                $role = 2;
            }
            $data = [
                'tutor_id' => (int) $tutor['id'],
                'first_name' => (string) $tutor['first_name'],
                'last_name' => (string) $tutor['last_name'],
                'bio' => (string) $tutor['bio'],
                'email' => (string) $tutor['email'],
                'profile_photo' => (string) $gnl->image_not_found_api_main('profile_photo', $tutor['profile_photo']),
                'profile_photo_thumb' => (string) $gnl->image_not_found_api_thumb('profile_photo', $tutor['profile_photo']),
                'cv_doc' => (string) $gnl->image_not_found_api_main('cv_doc', $tutor['cv_doc']),
                'subjects' => (string) $this->user_subjects($tutor['id']),
                'company_name' => (string) "", // $tutor['company_name'],
                'tutor_rating' => number_format($rating['avg_rating'], 1),
                'tutor_no_of_user_rate' => (string) $rattingdata['no_of_user'],
                'is_favourite' => (int) $tutor['is_favourite'], //0 : No,1 : Yes
                'questions_answered' => (int) $tutor['questions_answered'],
                'tutor_reviews' => $this->get_tutor_reviews($tutor_id),
                'role' => (int) $role,
                'tutor_bid_amount' => (float) $tutor['tutor_bid_amount'],
            ];
        }
        return $data;
    }

    /*
     * get tutor reviews
     */

    public function get_tutor_reviews($tutor_id) {
        $gnl = new GeneralComponent();
        $data = [];

        $review_sql = "SELECT user.* , review.*,
                       (SELECT name FROM qualification where qualification.qualification_id = user.qualification_id) as qualification_name
                                    FROM review
                                    LEFT JOIN user  ON review.posted_by = user.id 
                                    WHERE  review.posted_for =  $tutor_id";

        $review_array = yii::$app->db->createCommand($review_sql)->queryAll();
        if (!empty($review_array)) {
            foreach ($review_array as $review) {
                if ($review['role'] == 'student') {
                    $role = 1;
                } else if ($review['role'] == 'tutor') {
                    $role = 2;
                }
                $userobj = new User();
                $rattingdata = $userobj->usergetrating($review['id']);
                $tutor_who_reviewed[] = [
                    'user_id' => (int) $review['id'],
                    'first_name' => (string) $review['first_name'],
                    'last_name' => (string) $review['last_name'],
                    'bio' => (string) $review['bio'],
                    'email' => (string) $review['email'],
                    'profile_photo' => (string) $gnl->image_not_found_api_main('profile_photo', $review['profile_photo']),
                    'profile_photo_thumb' => (string) $gnl->image_not_found_api_thumb('profile_photo', $review['profile_photo']),
                    'qualification' => (string) $review['qualification_name'],
                    'subjects' => (string) $this->user_subjects($review['id']),
                    'tutor_rating' => (string) $review['rating'],
                    'review_opt' => (string) $review['review_opt'],
                    'comment' => (string) $review['comment'],
                    'tutor_no_of_user_rate' => (string) $rattingdata['no_of_user'],
                    'role' => (int) $role,
                ];
            }
        } else {
            $tutor_who_reviewed = [];
        }

        return $tutor_who_reviewed;
    }

    public function add_fav_tutor($student_id, $tutor_id) {
        $is_fav_tutor = FavTutor::find()->where(['student_id' => $student_id, 'tutor_id' => $tutor_id])->asArray()->one();
        if (!empty($is_fav_tutor)) {
            FavTutor::deleteAll(['student_id' => $student_id, 'tutor_id' => $tutor_id]);
            return 2;
        } else {
            $model = new FavTutor();
            $model->student_id = $student_id;
            $model->tutor_id = $tutor_id;
            $model->created_date = date('Y-m-d H:i:s');
            $model->save(false);
            return 1;
        }
    }

    /*
     * accept question by student
     */

    public function accept_tutor($student_id, $tutor_id, $question_id) {
        $question = Question::find()->where(['question_id' => $question_id, 'status' => 1, 'confirm_select_tutor' => 0])
                        ->andWhere('TIMESTAMPDIFF(Minute, question.`asked_date`, "' . date('Y-m-d H:i:s') . '") <= (`time_limit`*60)')->one();

        if (empty($question)) {
            return 3;
        }
        $invite_tutor_detail = \common\models\InvitedTutor::find()->where(['question_id' => $question_id, 'tutor_id' => $tutor_id, 'tutor_requst_status' => 1, 'is_confirmed' => 0])->one();
        if (empty($invite_tutor_detail)) {
            return 2;
        }
        $sender = User::findOne($student_id);
        $invite_tutor_details = \common\models\InvitedTutor::findAll(['question_id' => $question_id]);
        foreach ($invite_tutor_details as $value) {
            if ($value['tutor_id'] == $tutor_id) {
                $bidprice = $invite_tutor_detail['tutor_bid_amount'];
                $sql_questions = "UPDATE invited_tutor SET is_confirmed=1,student_accept_time = '" . date("Y-m-d H:i:s") . "' WHERE invited_tutor_id =" . $invite_tutor_detail['invited_tutor_id'];
                $data_questions = yii::$app->db->createCommand($sql_questions)->execute();

                $sql_questions = "UPDATE question SET bid_status=1,confirm_select_tutor=$tutor_id,confirm_bid=$bidprice  WHERE question_id =" . $question_id;
                $data_questions = yii::$app->db->createCommand($sql_questions)->execute();

                $receiver = User::findOne($tutor_id);
//              -------------------------------------N_PUSHNOTIFICATION12-START---------------------------------------
                if($question['price_type'] == 1)
                {
                $push_noti_msg = $sender['first_name'] . " " . $sender['last_name'] . " has specifically selected you to answer their question.";
                }
                else if($question['price_type'] == 2){
                $push_noti_msg = "Your bid has been selected by ".$sender['first_name'] . " " . $sender['last_name'] . ".";
                }
                $noti_type = 12;
                $data = [];
//                        if ($receiver->is_notification == 1) {
                $param = ["message" => $push_noti_msg, "type" => $noti_type, "data" => $data];
                if ($receiver->device_type == 'ios') {
                                NotificationComponent::send_push($receiver->device_token, $param, "ios");
                }
                if ($receiver->device_type == 'android') {
                                NotificationComponent::send_push($receiver->device_token, $param, "android");
                }
//                        }
                GeneralComponent::saveNotificationLog($question_id, $sender['id'], $receiver['id'], $noti_type, $push_noti_msg, $sender['id']);
//            -------------------------------------PUSHNOTIFICATION-END---------------------------------------
            } else {
                $sql_questions = "UPDATE invited_tutor SET is_confirmed=2 WHERE invited_tutor_id =" . $value['invited_tutor_id'];
                $data_questions = yii::$app->db->createCommand($sql_questions)->execute();
                $receiver = User::findOne($value['tutor_id']);
//              -------------------------------------N_PUSHNOTIFICATION13-START---------------------------------------
                $push_noti_msg = $sender['first_name'] . " " . $sender['last_name'] . " has confirmed another tutor.";
                $noti_type = 13;
                $data = [];
//                        if ($receiver->is_notification == 1) {
                $param = ["message" => $push_noti_msg, "type" => $noti_type, "data" => $data];
                if ($receiver->device_type == 'ios') {
                                NotificationComponent::send_push($receiver->device_token, $param, "ios");
                }
                if ($receiver->device_type == 'android') {
                                NotificationComponent::send_push($receiver->device_token, $param, "android");
                }
//                        }
                GeneralComponent::saveNotificationLog($question_id, $sender['id'], $receiver['id'], $noti_type, $push_noti_msg, $sender['id']);
//            -------------------------------------PUSHNOTIFICATION-END---------------------------------------                   
            }
        }


        return 1;
    }

    /*
     * reject question by student
     */

    public function reject_tutor($student_id, $tutor_id, $question_id) {
        $question = Question::find()->where(['question_id' => $question_id, 'status' => 1, 'confirm_select_tutor' => 0])->andWhere('TIMESTAMPDIFF(Minute, question.`asked_date`, "' . date('Y-m-d H:i:s') . '") <= (`time_limit`*60)')->one();

        if (empty($question)) {
            return 3;
        }
        $invite_tutor_detail = \common\models\InvitedTutor::find()->where(['question_id' => $question_id, 'tutor_id' => $tutor_id, 'tutor_requst_status' => 1, 'is_confirmed' => 0])->one();
        if (empty($invite_tutor_detail)) {
            return 2;
        }

        $sql_questions = "UPDATE invited_tutor SET is_confirmed=2 WHERE tutor_id = $tutor_id AND question_id = $question_id";
        $data_questions = yii::$app->db->createCommand($sql_questions)->execute();

        return 1;
    }

    public function referral_discoun_check($user_id) {

        $discount_percent = 0;
        $userdata = User::findOne($user_id);
        if (@$userdata['referral_code']) {
            $questiondetail = Question::findOne(['created_by' => $user_id]);
            if (!$questiondetail) {
                $referer_user_detail = User::findOne(['student_referral_code' => $userdata['referral_code']]);
                if ($referer_user_detail) {
                    $setting_data = \common\models\Setting::findOne(1);
                    if ($setting_data['referral_commission'] > 0) {
                        $discount_percent = $setting_data['referral_commission'];
                    } else {
                        $discount_percent = 0;
                    }
                }
            }
        }

        return $discount_percent;
    }
    
     /*
     * extend time limit
     */

    public function extend_time_limit($question_id, $user_id,$hour) {
        $question = Question::find()->where(['question_id' => $question_id, 'status' => 1,'created_by'=>$user_id ,'confirm_select_tutor' => 0])
                        ->andWhere('(`time_limit`*60) - TIMESTAMPDIFF(Minute, question.`asked_date`, "' . date('Y-m-d H:i:s') . '")   BETWEEN 0 AND 60')->one();

        if (empty($question)) {
            return 2;//you can not extend timelimit now;
        }
        else{
           $question->time_limit = $question['time_limit']+$hour;
           $question->save(FALSE);
            
        return 1;
        }

    }
}
