<?php

namespace api\modules\v1;

use \Yii;

class Module extends \yii\base\Module {

    public $controllerNamespace = 'api\modules\v1\controllers';

    public function init() {
        define('IMAGE_UPLOAD', $_SERVER['DOCUMENT_ROOT'] . '/fitmakerslive/uploads/');
        define('SERVICE_TOKEN', '8CNR16xCOk10TeZ');
        parent::init();
    }

}
