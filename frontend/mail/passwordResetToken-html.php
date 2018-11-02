<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user common\models\User */



$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>

<div style="padding:0px; margin:0px; background:#fff;">
    <table border="0" cellpadding="0" cellspacing="0" style="color:#333; margin:0 auto; width:600px;">
        <tr>
            <td valign="top" style="padding:0px;">
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="text-align:center; background:#393939; padding:30px 0;"><img src="<?= $logoimage ?>" title="" alt="" width="120"/></td>
                    </tr>
                    <tr>
                        <td style="padding:20px 25px 30px 25px;font-family: Arial, Helvetica, sans-serif; font-size:12px;">

                            <div class="password-reset">
                                <p>Hello <?= Html::encode($user->username) ?>,</p>

                                <p>Follow the link below to reset your password:</p>

                                <p><?= Html::a('Click here', $resetLink) ?></p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="color:#888888; background:#EDEDED; text-align:center; font-family: Arial, Helvetica, sans-serif; font-size:12px; padding:10px 0;">
                <?= yii::$app->params['footer'] ?>
            </td>
        </tr>
    </table>
</div>
