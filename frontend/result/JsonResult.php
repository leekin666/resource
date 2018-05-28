<?php
/**
 * Created by PhpStorm.
 * User: LeeSin
 * Date: 2018/5/18
 * Time: 13:53
 */

namespace frontend\result;

use Yii;
use yii\web\Response;

class JsonResult
{
    const SUCCESS_CODE = 0;
    const ERROR_CODE   = 40000;

    public static function success($data = [], $message = '')
    {
        $result = [
            'code' => self::SUCCESS_CODE,
            'message' => $message,
            'data' => $data
        ];
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        $response->data = $result;
        $response->send();
    }


    public static function error($message = '', $code = null, $data = [])
    {
        $result = [
            'code' => $code??self::ERROR_CODE,
            'message' => $message??'error',
            'data' => $data
        ];
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        $response->data = $result;
        $response->send();
    }

}