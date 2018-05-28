<?php
/**
 * Created by PhpStorm.
 * User: LeeSin
 * Date: 2018/5/15 0015
 * Time: 20:34
 */
namespace common\components;

use Yii;
use yii\base\Component;
use OSS\OssClient;

class Aliyunoss extends Component
{
    public static $oss;
    public $uploadDir;

    public function __construct()
    {
        parent::__construct();
        $accessKeyId = Yii::$app->params['oss']['accessKeyId'];                     //获取阿里云oss的accessKeyId
        $accessKeySecret = Yii::$app->params['oss']['accessKeySecret'];             //获取阿里云oss的accessKeySecret
        $endpoint = Yii::$app->params['oss']['endPoint'];                           //获取阿里云oss的endPoint
        self::$oss = new OssClient($accessKeyId, $accessKeySecret, $endpoint);      //实例化OssClient对象
        $this->uploadDir = Yii::$app->params['oss']['uploadDir'];   //获取阿里云oss存储路径
    }

    /**
     * 上传本地文件到阿里云oss
     * @param $object   //文件名
     * @param $filepath //文件路径
     * @return array
     * @throws \OSS\Core\OssException
     */
    public function upload($object, $filepath)
    {

        $return = [];
        //获取阿里云oss的bucket
        $bucket = Yii::$app->params['oss']['bucket'];
        if( !self::$oss->doesBucketExist($bucket)){
            self::$oss->createBucket($bucket);
        }
        if ($this->uploadDir){
            $object = $this->uploadDir.date("Ymd").'/'.$object;
        }
        //调用uploadFile方法把服务器文件上传到阿里云oss
        try {
            $res = self::$oss->uploadFile($bucket, $object, $filepath);
            //获取上传后的远程地址
            $return['code'] = 0;
            $return['size'] = $res['info']['size_upload'];
            $return['url'] = $res['info']['url'];
        }catch(Exception $e) {
            $return['code'] = 1;
            $return['errmsg'] = $e->getMessage();
        }
        return $return;
    }

    /**
     * 上传内存中的内容到阿里云oss
     * @param $object   //文件名
     * @param $filepath //文件路径
     * @return array
     * @throws \OSS\Core\OssException
     */
    public function putObject($object, $filepath)
    {

        $return = [];
        //获取阿里云oss的bucket
        $bucket = Yii::$app->params['oss']['bucket'];
        if( !self::$oss->doesBucketExist($bucket)){
            self::$oss->createBucket($bucket);
        }
        if ($this->uploadDir){
            $object = $this->uploadDir.$object;
        }
    
        try {
            $res = self::$oss->putObject($bucket, $object, $filepath);
            
            $return['code'] = 0;
            $return['url'] = $res['info']['url'];
        }catch(Exception $e) {
            $return['code'] = 1;
            $return['errmsg'] = $e->getMessage();
        }
        return $return;
    }

    /**
     * 删除同一Bucket下多个文件
     * @param array $objects   //被删除的文件名数组
     * @return array
     */
    public function delete($objects)
    {
        $return = [];
        $bucket = Yii::$app->params['oss']['bucket'];
        foreach ((array)$objects as $k => $obj){
            $objects[$k] = $this->uploadDir.$obj;
        }
        try {
            $res = self::$oss->deleteObjects($bucket, $objects);
            $return['code'] = 0;
        }catch(Exception $e) {
            $return['code'] = 1;
            $return['errmsg'] = $e->getMessage();
        }
        return $return;
    }

}