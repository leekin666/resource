<?php
namespace frontend\controllers;

use Yii;
use common\components\File;
use frontend\result\JsonResult;
use frontend\models\WeliveResource;
use frontend\controllers\base\BaseController;

/**
 * 上传接口
 */
class UploadController extends BaseController
{
    public  $errorMsg = [
        -1 => '上传失败',
        -2 => '文件存储路径不合法',
        -3 => '上传非法格式文件',
        -4 => '文件大小不合符规定',
        -5 => 'token验证错误',
        -6 => '未上传文件',
        -7 => '删除失败',
        -8 => '存储失败'
    ];

    public  $errorCode = [
        -1 => 40091,
        -2 => 40092,
        -3 => 40093,
        -4 => 40094,
        -5 => 40095,
        -6 => 40096,
        -7 => 40097,
        -8 => 40098
    ];

    public $enableCsrfValidation=false;

    /**
     * 上传图片至阿里云OSS&&Redis
     */
    public function actionImage()
    {

        $extAllow = $this->params('ext',null);
        $sizeAllow = $this->params('size',null);
        $imgDataArr = $this->params('img_data');
//        Yii::$app->redis->set('imgdata'.time(),$imgData);
//        $is_ajax = Yii::$app->request->isAjax;
//        if ($extAllow || $sizeAllow) {
//            $allow = ['ext'=>$extAllow,'size'=>$sizeAllow];
//        }
//
//        if (!$imgData || !$is_ajax) {
//            return JsonResult::error($this->errorMsg[-1],$this->errorCode[-1]);
//        }
        $data = [];
        $length = count($imgDataArr);
        Yii::$app->redis->set('length',$length);
        $resource = new WeliveResource();
        for ($key = 0; $key < $length; $key++){
            $imgData = base64_decode($imgDataArr[$key]);

            $file_name = mt_rand(0, 1000).time().'.jpg';
            $data[$key] = $this->saveObjToOss($file_name, $imgData);

            Yii::$app->redis->set('img'.$key,json_encode($data[$key]['url']));
        }
//        Yii::$app->redis->set('data',json_encode($data));
        return JsonResult::success($data);
    }

    public function saveObjToOss($object, $imgData)
    {
        $resource = new WeliveResource();
        try {
            $ossRes = Yii::$app->Aliyunoss->putObject($object, $imgData);
            if(isset($ossRes) && $ossRes['code'] == 0) {
                $resource->course_id = rand(1,100);
                $resource->blind_id = rand(1,100);
                $resource->sort_id = rand(1,100);
                $resource->res_type = 1;
                $resource->file_mimetype = 'image/jpg';
                $resource->file_name = $object;
                $resource->file_type = 1;
                $resource->file_phy_name = $object;
                $resource->file_size = $ossRes['size'];
                $resource->url = $ossRes['url'];
                $resource->create_datetime = time();
                $resource->save();
                if ($resource->getErrors()){
                    return ['code' => 40000, 'message' => 'fail'];
                }else{
                    return $ossRes;
                }
            }
        } catch (OssException $e) {
            return ['code' => 40000, 'message' => 'fail'];
        }
    }

    public function actionImage11()
    {

        $extAllow = $this->params('ext',null);
        $sizeAllow = $this->params('size',null);
        $imgData = $this->params('img_data');
//        Yii::$app->redis->set('imgdata'.time(),$imgData);
//        $is_ajax = Yii::$app->request->isAjax;
//        if ($extAllow || $sizeAllow) {
//            $allow = ['ext'=>$extAllow,'size'=>$sizeAllow];
//        }
//
//        if (!$imgData || !$is_ajax) {
//            return JsonResult::error($this->errorMsg[-1],$this->errorCode[-1]);
//        }
        $imgData = base64_decode($imgData);
        $file_name = mt_rand(0, 1000).time().'.jpg';

        $object = $file_name;

        try {
            $ossRes = Yii::$app->Aliyunoss->putObject($object, $imgData);
            $resource = new WeliveResource();
            if(isset($ossRes) && $ossRes['code'] == 0) {
                $resource->course_id = rand(1,10);
                $resource->blind_id = rand(1,10);
                $resource->sort_id = rand(1,100);
                $resource->res_type = 2;
                $resource->file_mimetype = 'image/jpg';
                $resource->file_name = $object;
                $resource->file_type = 1;
                $resource->file_phy_name = $object;
                $resource->file_size = $ossRes['size'];
                $resource->url = $ossRes['url'];
                $resource->create_datetime = time();
                $resource->save();
                if ($resource->getErrors()){
                    return JsonResult::error($this->errorMsg[-1],$this->errorCode[-1]);
                }
            }
        } catch (OssException $e) {
            return $this->responseJson(ResultApi::fail('保存图片失败'));
        }
        return JsonResult::success($ossRes);
    }

    public function actionTest(){
        $data = [
            'ext' => 1,
            'url' => 'sss'
        ];
        Yii::$app->redis->set('ossres',json_encode($data));die;
        $url = 'wxLocalResource://voiceLocalId1234567890123';
        
        $savePath = $_SERVER['DOCUMENT_ROOT'].'/static/';
        // $r = $this->deleteDownloadFile('6.amr');die;
        exec("ffmpeg -i /home/www/res-zhan-com/frontend/web/static/6.mp3 /home/www/res-zhan-com/frontend/web/static/55.amr");die;
        // exec("ffmpeg -y -i ".$savePath."test.amr"." ".$dir.$mp3)
        $r = $this->amrTransCodingMp3('test.amr','66');print_r($r);die;
        $this->downAndSaveFile($url,$savePath);

        // print_r($_SERVER['DOCUMENT_ROOT']);die;
        return false;
        $resource = new WeliveResource(); 
        $redis = Yii::$app->redis;
        $redis->multi();
        $now = date('Y-m-d H:i:s');
        $resource->course_id = 1; 
        $resource->blind_id = 1; 
        $resource->sort_id = 1; 
        $resource->res_type = 1; 
        $resource->file_mimetype = 1; 
        $resource->file_name = 1; 
        $resource->file_type = 1; 
        $resource->file_phy_name = 1; 
        $resource->file_size = 1; 
        $resource->url = 1; 
        $resource->create_datetime = $now; 
        // $resource->save(); 
        $redis->exec($resource->save());
        echo $resource->id;
    }

    /**
     * 微信录音上传
     */
    public function actionAudio()
    {
        $mediaId = $this->params('mediaid',0);
        Yii::$app->redis->set('mediaid'.rand(1,100),$mediaId);
        if (!$mediaId){
            return JsonResult::error('Upload Fail');
        }
        $url = Yii::$app->wechat->getMediaDownloadUrl($mediaId);
        $savePath = dirname(Yii::$app->BasePath).'/frontend/web/temp';
        $downloadFile = $this->downloadAudioMedia($url, $savePath);
        if (!$downloadFile){
            return JsonResult::error('Upload Fail');
        }
        $mp3FileName = $this->msectime() . $this->salt(6) . '.mp3';
        Yii::$app->redis->set('arm',$downloadFile);
        Yii::$app->redis->set('mp3',$mp3FileName);

        $mp3File = $this->amrTransCodingMp3($downloadFile,$mp3FileName);
        $ossRes = Yii::$app->Aliyunoss->upload($mp3FileName,$mp3File);
//        Yii::$app->redis->set('ossres',json_encode($ossRes));
//        $this->deleteDownloadFile($downloadFile);
        $this->deleteDownloadFile($mp3File);
        $resource = new WeliveResource();
        if(isset($ossRes) && $ossRes['code'] == 0) {
            $resource->course_id = rand(1,10);
            $resource->blind_id = rand(1,10);
            $resource->sort_id = rand(1,100);
            $resource->res_type = rand(1,3);
            $resource->file_mimetype = 'audio/mp3';
            $resource->file_name = $mp3File;
            $resource->file_type = 2;
            $resource->file_phy_name = $mp3File;
            $resource->file_size = $ossRes['size'];
            $resource->url = $ossRes['url'];
            $resource->create_datetime = time();
            $resource->save();
            if ($resource->getErrors()){
                return JsonResult::error($this->errorMsg[-1],$this->errorCode[-1]);
            }
        }
        return JsonResult::success($ossRes);

    }

    /**
     * 下载微信语音素材资源到本地
     * @param  url $url  素材地址
     * @return mixed
     */
    public function downloadAudioMedia($url ,$savePath='')
    {
        // 获取文件流
        $file_flow = file_get_contents($url);

        if (!$savePath){
            $savePath = dirname(Yii::$app->BasePath).'/frontend/web/temp';
        }
        if( !file_exists($savePath) ) {
            $this->createDir($savePath);
        }

        // 生成文件名
        $filename = $this->msectime() . $this->salt(6) . '.amr';
        // 写入文件流到本地
        $flag = file_put_contents($savePath . '/' . $filename, $file_flow);
        unset($file_flow);
        if($flag !== FALSE) {
            return $savePath . '/' . $filename;
        }else {
            return FALSE;
        }

    }

    /**
     * 生成毫秒级时间戳
     */
    public function msectime()
    {
        list($msec, $sec) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    }

    /**
     * 随机取出字符串
     * @param  int $strlen 字符串位数
     * @return string
     */
    public function salt($strlen)
    {
        $str  = "abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ123456789";
        $salt = '';
        $_len = strlen($str)-1;
        for ($i = 0; $i < $strlen; $i++) {
            $salt .= $str[mt_rand(0,$_len)];
        }
        return $salt;
    }

    /**
     * 将amr格式转换成mp3格式
     *
     * @param $amr
     * @param $prefix_filename
     * @return mixed
     */
    public function amrTransCodingMp3($amr, $mp3)
    {
        $savePath = dirname(Yii::$app->BasePath).'/frontend/web/temp/';
        $mp3Name = $savePath.$mp3;
        $str = "ffmpeg -i ".$amr." ".$mp3Name;
        exec("ffmpeg -i ".$amr." ".$mp3Name .'  &');
        return $mp3Name;
    }

    /**
     * 根据URL地址，下载文件
     *
     * @param $url
     * @param $savePath
     */
    public function downAndSaveFile($url,$savePath)
    {
        ob_start();
        readfile($url);
        $img  = ob_get_contents();
        ob_end_clean();
        $size = strlen($img);
        $fp = fopen($savePath, 'a');
        fwrite($fp, $img);
        fclose($fp);
    }

    /**
     * 删除本地音频文件
     *
     * @param $filename
     * @return bool
     * @throws ParameterException
     */
    public function deleteDownloadFile($filename)
    {
        if (!unlink($filename)){
            throw new ParameterException([
                'msg' => "Error deleting $filename"
            ]);
        }else{
            return true;
        }
    }

    /**
     * 递归：生成目录
     */
    private function createDir($str)
    {
        $arr = explode('/', $str);
        if(!empty($arr))
        {
            $path = '';
            foreach($arr as $k=>$v)
            {
                $path .= $v.'/';
                if (!file_exists($path)) {
                    mkdir($path, 0777);
                    chmod($path, 0777);
                }
            }
        }
    }

    /**
     * 获取文件类型
     * $param $ext string 文件后缀
     * $return $fileType int //1：图片；2：音频；3：视频
     */
    public function getFileTypeByExt($ext){
        if (!$ext){
            return false;
        }
        $ext = strtolower($ext);
        $fileType = 0;
        $fileTypeArr = [
            'img'   => 1,
            'jpg'   => 1,
            'jpeg'  => 1,
            'gif'   => 1,
            'png'   => 1,
            'mp3'   => 2,
            'wma'   => 2,
            'wav'   => 2,
            'flac'  => 2,
            'mp4'   => 3,
            'avi'   => 3,
            'rmvb'  => 3
        ];
        if (array_key_exists($ext, $fileTypeArr)){
            $fileType = $fileTypeArr[strtolower($ext)];
        }
        return $fileType;
    }

    /**
     * 上传本地资源至阿里云OSS&&Redis
     */
    public function actionSave()
    {
        $res_type = $this->params('res_type',1);
        $course_id = $this->params('course_id',0);
        $blind_id = $this->params('blind_id',0);
        $sort_id = $this->params('sort_id',0);
        $extAllow = $this->params('ext',null);
        $sizeAllow = $this->params('size',null);
        if (!$course_id){
            return JsonResult::error('答疑id为空','40099');
        }
        $allow = null;
        if ($extAllow || $sizeAllow) {
            $allow = ['ext'=>$extAllow,'size'=>$sizeAllow];
        }
        $obj = new File();
        if(empty($_FILES)){
            return JsonResult::error($this->errorMsg[-6],$this->errorCode[-6]);
        }
        //未做事务处理
        $filelist = $obj->saveToOss($allow);
        $data = [];
        $resource = new WeliveResource();

        if(is_array($filelist)){
            $now = date('Y-m-d H:i:s');
            foreach ($filelist as $key => $value){
                $resource->course_id = $course_id;
                $resource->blind_id = $blind_id;
                $resource->sort_id = $sort_id;
                $resource->res_type = $res_type;
                $resource->file_mimetype = $value['mime'];
                $resource->file_name = $value['name'];
                $resource->file_type = $this->getFileTypeByExt($value['ext']);
                $resource->file_phy_name = $value['savename'];
                $resource->file_size = $value['size'];
                $resource->url = $value['url'];
                $resource->create_datetime = $now;
                $resource->save();
                if ($resource->getErrors()){
                    return JsonResult::error($this->errorMsg[-1],$this->errorCode[-1]);
                }
            }
            return JsonResult::success($filelist);
        }else{
            return JsonResult::error($this->errorMsg[$filelist],$this->errorCode[$filelist]);
        }

    }

    /**
     * 删除Redis资源表数据
     */
    public function actionDelete()
    {
        $urlArr = $this->params('url',[]);
        if (!$urlArr && is_array($urlArr)){
            $condition = ['in', 'url', $urlArr];
            $res = WeliveResource::deleteAll($condition);
            if ($res) {
                return JsonResult::success($ossRes);
            }
            return JsonResult::error($this->errorMsg[-7],$this->errorMsg[-7]);
        }
        return JsonResult::error($this->errorMsg[-7],$this->errorMsg[-7]);
    }

    /**
     * 暂废弃
     */
    public function actionDeleteOld()
    {
        return false;
        $resource = $this->params('resource',[]);
        $fileIds  = implode(",", array_keys($resource));
        $fileNameArr = array_values($resource);
        $ossRes = Yii::$app->Aliyunoss->delete($fileNameArr);
        if ($ossRes['code'] == 0){
            if($fileIds){
                $condition = ['in', 'id', $fileIds];
                Resource::deleteAll($condition);
                return JsonResult::success($ossRes);
            }
            return JsonResult::error($this->errorMsg[-8],$this->errorMsg[-8]);
        }
        return JsonResult::error($this->errorMsg[-7],$this->errorMsg[-7]);
    }

    public function actionSearch(){
        $resource = WeliveResource::findOne(3);
        print_r($resource);
    }

}
