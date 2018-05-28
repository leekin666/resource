<?php
namespace frontend\controllers\base;

use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class BaseController extends Controller
{

    /**
     * @param $action
     * @return bool|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }

    public function params($key, $defaultValue = null)
    {
        $request = Yii::$app->request;
        $method = strtolower($request->getMethod());
        $value = $request->$method($key, $defaultValue);
        if ($method == 'post' && !$request->$method($key)) {
            return $request->get($key, $defaultValue);
        }
        return $value;
    }

    public function responseJson($data)
    {
        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_JSON;
        $response->data = $data;
        $response->send();
    }

    
    public function responseError($statusCode, $errorMsg = "")
    {
        $response = Yii::$app->response;
        $response->setStatusCode($statusCode);
        $response->data = $errorMsg;
        $response->send();
    }

    public function urldecode($url)
    {
        if (strpos($url, "%")) {
            $url = urldecode($url);
            return $this->urldecode($url);
        } else {
            return $url;
        }
    }

}
