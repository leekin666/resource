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
        header('Access-Control-Allow-Origin:*');
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
        $data = [];
        $imgData = base64_decode($imgData);
        $file_name = mt_rand(0, 1000).time().'.jpg';
        $resource = new WeliveResource();
        try {
            $object = $file_name;
            $ossRes = Yii::$app->Aliyunoss->putObject($object, $imgData);
            if(isset($ossRes) && $ossRes['code'] == 0) {
                $resource->room_id = rand(1,10);
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
            return JsonResult::error($this->errorMsg[-1],$this->errorCode[-1]);
        }
        $data = ['url' => $ossRes['url'], 'size' => $ossRes['size']];
        return JsonResult::success($data);
    }

    public function actionImageOld()
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
        $now = time();
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
        header('Access-Control-Allow-Origin:*');
        $url = $this->params('media_url',0);
        $roomId = $this->params('room_id',0);
        $blindId = $this->params('blind_id',0);
        $resType = $this->params('res_type',1);
//        Yii::$app->redis->set('mediaurl',$url);
        if (!$url){
            return JsonResult::error('Upload Fail');
        }
//        $url = Yii::$app->wechat->getMediaDownloadUrl($mediaId);
        $savePath = dirname(Yii::$app->BasePath).'/frontend/web/temp';
        $downloadFile = $this->downloadAudioMedia($url, $savePath);
        if (!$downloadFile){
            return JsonResult::error('Upload Fail');
        }
        $mp3FileName = $this->msectime() . $this->salt(6) . '.mp3';
//        Yii::$app->redis->set('arm',$downloadFile);
//        Yii::$app->redis->set('mp3',$mp3FileName);

        $mp3File = $this->amrTransCodingMp3($downloadFile,$mp3FileName);
        Yii::$app->redis->set('mp3',$mp3File);
        $ossRes = Yii::$app->Aliyunoss->upload($mp3FileName,$mp3File);

        $this->deleteDownloadFile($downloadFile);
//        Yii::$app->redis->set('delete1',1);
        $this->deleteDownloadFile($mp3File);
//        Yii::$app->redis->set('delete1',2);
        $resource = new WeliveResource();
        if(isset($ossRes) && $ossRes['code'] == 0) {
            $resource->room_id = $roomId;
            $resource->blind_id = $blindId;
            $resource->sort_id = 0;
            $resource->res_type = $resType;
            $resource->file_mimetype = 'audio/mpeg';
            $resource->file_name = $mp3FileName;
            $resource->file_type = 2;
            $resource->file_phy_name = $mp3FileName;
            $resource->file_size = $ossRes['size'];
            $resource->url = $ossRes['url'];
            $resource->create_datetime = time();
            $resource->save();
            Yii::$app->redis->set('saveoss',3);
            if ($resource->getErrors()){
                Yii::$app->redis->set('saveoss',4);
                return JsonResult::error($this->errorMsg[-1],$this->errorCode[-1]);
            }
        }
        $data = ['url' => $ossRes['url'], 'size' => $ossRes['size']];
//        Yii::$app->redis->set('ossres',json_encode($data));
        return JsonResult::success($data);

    }

    public function actionGetMediaUrl()
    {
        $mediaId = $this->params('mediaid',0);
        if (!$mediaId){
            return JsonResult::error('Upload Fail');
        }
        $url = Yii::$app->wechat->getMediaDownloadUrl($mediaId);
        $data = ['url' => $url];
        return JsonResult::success($data);
    }

    public function actionAudio1()
    {
        $mediaId = $this->params('mediaid',0);
//        Yii::$app->redis->set('mediaid',$mediaId);
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
        $this->deleteDownloadFile($downloadFile);
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
        $data = ['url' => $ossRes['url'], 'size' => $ossRes['size']];
        return JsonResult::success($data);

    }

    /**
     * 下载微信语音素材资源到本地
     * @param  url $url  素材地址
     * @return mixed
     */
    public function downloadAudioMedia($url ,$savePath='')
    {
        $file_flow = file_get_contents($url);
        if (!$savePath){
            $savePath = dirname(Yii::$app->BasePath).'/frontend/web/temp';
        }
        if( !file_exists($savePath) ) {
            $this->createDir($savePath);
        }
        $filename = $this->msectime() . $this->salt(6) . '.amr';
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
     * @param $amr arm音频文件名
     * @param $mp3 mp3音频文件名
     * @return mixed
     */
    public function amrTransCodingMp3($amr, $mp3)
    {
        $savePath = dirname(Yii::$app->BasePath).'/frontend/web/temp/';
        $mp3Name = $savePath.$mp3;
//        $str = "ffmpeg -i ".$amr." ".$mp3Name;
        exec("ffmpeg -i ".$amr." ".$mp3Name);
        Yii::$app->redis->set('mp3name',$mp3Name);
        return $mp3Name;
    }

    /**
     * 删除本地音频文件
     * @param $filename
     * @return bool
     */
    public function deleteDownloadFile($filename)
    {
        if (!unlink($filename)){
            return false;
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
            $now = time();
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
                return JsonResult::success($res);
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
