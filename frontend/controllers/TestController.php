<?php
namespace frontend\controllers;

use Yii;
use common\models\Country;
use yii\rest\ActiveController;
use common\components\Aliyunoss;
use frontend\controllers\base\BaseController;

/**
 * 上传接口
 */
class TestController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionWe()
    {
//        echo phpinfo();die;
        $sign_package = Yii::$app->wechatJssdk->getSignPackage();

//        $upload_url = Url::toRoute('upload-head-img', true);
//        $save_url = Url::toRoute('save-user-info', true);
//        $tip_url = Url::toRoute('tip-info', true);
//        print_r($sign_package);die;
        return $this->renderPartial('we', [
            'sign_package' => $sign_package,
//            'upload_url' => $upload_url,
//            'save_url' => $save_url,
//            'tip_url' => $tip_url
        ]);
    }
}
