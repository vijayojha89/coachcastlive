<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>CoachCast Live</title>
    </head>
    <body style="margin:0; padding:0;">
        <table width="620" border="0" align="center" cellpadding="0" cellspacing="0" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:24px; color:#989797;">

            <tr>
                <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td align="center" valign="middle" style="background-color:#000; height:120px;"><img src="<?= $logoimage ?>" alt="" /></td>
                        </tr>
                    </table></td>
            </tr>
            <tr>
                <td valign="top" style="padding-top:20px; padding-bottom:20px;">
                    <?= $messegedata ?>
                </td>
            </tr>
            <tr>
                <td valign="top" style="border-top:#ebebeb solid 1px; padding-top:30px; padding-bottom:30px; text-align:center; color:#989797; font-size:14px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="text-align:center; color:#989797;">Copyright © <?php echo date('Y');?> CoachCast Live , All rights reserved.</td>
                        </tr>
                        <tr>
                            <td height="20"></td>
                        </tr>
                        <tr>
                            <td style="text-align:center; color:#989797;">You are receiving this email because you have signed up to CoachCast Live</td>
                        </tr>
                        <tr>
                            <td height="20"></td>
                        </tr>
                        <tr>
                            <td style="text-align:center; color:#989797;"><strong>Need Help ? Please contact us via email</strong></td>
                        </tr>
                        <tr>
                            <td height="20"></td>
                        </tr>
                        <tr>
                            <td style="text-align:center; color:#989797;"><a href="mailto:support@coachcastlive.com" target="_blank" style="color:#989797; text-decoration:none;">support@coachcastlive.com</a></td>
                        </tr>
                        <tr>
                            <td height="20"></td>
                        </tr>
                        <tr>
                            <td style="text-align:center; color:#989797;">
                                <a href="<?php echo Yii::$app->params['url'].'site/terms';?>" style="color:#989797; text-decoration:none;">Terms & Conditions</a> 
                                <a href="<?php echo Yii::$app->params['url'].'site/howitworks';?>" style="color:#989797; text-decoration:none;padding-left: 20px;">How it Works</a>
                                <a href="<?php echo Yii::$app->params['url'].'site/about';?>" style="color:#989797; text-decoration:none;padding-left: 20px;">About Us</a>
                                <a href="<?php echo Yii::$app->params['url'].'site/faq';?>" style="color:#989797; text-decoration:none;padding-left: 20px;">FAQ</a>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>

    </body>
</html>