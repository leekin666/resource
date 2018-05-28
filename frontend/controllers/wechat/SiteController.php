<?php
namespace frontend\controllers\wechat;

use Yii;
use yii\helpers\Url;
use frontend\controllers\base\BaseController;

class SiteController extends BaseController
{
    public $module = null; //当前模块
    public $wechat = null; //微信组件

    public function init()
    {
        parent::init();
    }
    
    /**
     * 小站备考公众号验证签名接口
     */
    public function actionValid()
    {
        // 微信网页授权:
        $signature = $this->params('signature');
        $timestamp = $this->params('timestamp');
        $nonce = $this->params('nonce');
        $echoStr = $this->params('echostr');
        $data = $signature.'/'.$timestamp.'/'.$nonce.'/'.$echoStr;
        Yii::$app->session['wechat-data']=$data;
        $res = Yii::$app->wechat->checkSignature($signature, $timestamp, $nonce);
        if ($res){
            echo $echoStr;
            exit();
        }
        print_r($res);die;
        return false;
        $echoStr = $this->params('echostr');

        if (!isset($_GET['echostr'])) { //若没有echostr，表示已经通过验证，直接调用responseMsg()方法

            return $this->responseMsg();
        } else { //若存在echostr，表示第一次提交验证申请，调用验证方法valid()，判断微信服务器(你的公众号)与网站服务器的是否连通。

            if ($this->wechat->checkSignature()) {
                return $echoStr;
            }
        }
    }

    /**
     * 网页授权 用户授权页面 第一步
     */
    public function actionGrantPage()
    {
        $go_url = $this->params('go_url');
        $accept_code_url = Url::toRoute(['accept-code', 'go_url' => $go_url], true);
        $redirect_url = $this->wechat->getOauth2AuthorizeUrl($accept_code_url, 'authorize', 'snsapi_userinfo');

        return $this->redirect($redirect_url);
    }

    /**
     * 用户授权成功后 获取code ,通过code 获取 网页授权 access_token
     */
    public function actionAcceptCode()
    {
        $go_url = $this->params('go_url');
        $code = $this->params('code');
        $token_info = $this->wechat->getOauth2AccessToken($code);

        $openid = $token_info['openid'];
        $access_token = $token_info['access_token'];

        //获取微信用户信息
        $wx_user_info = $this->wechat->getSnsMemberInfo($openid, $access_token);
        $biz_user = new User();
        $wx_user_info = $biz_user->encodeWxUserInfo($wx_user_info);

        $url = Url::toRoute(['receive-grant-info', 'go_url' => $go_url, 'wx_user_info' => $wx_user_info], true);
        return $this->redirect($url);
    }

    /**
     *接收用户授权后的信息
     */
    public function actionReceiveGrantInfo()
    {
        $go_url = $this->params('go_url', '');// 用户需要进入的url
        $weixin_user_info = $this->params('wx_user_info', '');

        if ($weixin_user_info) {

        }

        return $this->redirect(urldecode($go_url));
    }

    //响应消息
    public function responseMsg()
    {
        $request_data = $this->wechat->parseRequestData();
        $result = 'success';
        if (!empty($request_data)) {
            $msg_type = trim($request_data['MsgType']);
            switch ($msg_type) {
                case "event":
                    $result = $this->receiveEvent($request_data);
                    break;
                case "text":
                    $result = $this->receiveText($request_data);
                    break;
            }
        }

        return $result;
    }

    private function receiveEvent($request_data)
    {
        $from_user = $request_data['FromUserName'];
        $result = 'success';
        switch ($request_data['Event']) {
            case "subscribe":
                $response_type = "text";
//                if (!in_array($from_user, self::ALLOW_OPENID_LIST)) {
//                    $content = "[爱心]欢迎关注小站备考[爱心]\n从现在开始，我们一起在小站备考屠鸭考托吧！快戳下方菜单栏玩转备考:\n
//[勾引]\n戳【萌新区】一手机经备考资料熟悉考试，看公开课积累技巧\n
//戳【实战区】名师带队提分战团，每日学习任务助力考试提分\n[爱心]想了解更多2018考试时间、名校排名以及雅思\托福分数要求等请回复【考试时间】、【大学排名】关键词获取哦~;";
//                    break;
//                }
                //通过openid获取微信用户的unionid
                $wx_base_info = $this->wechat->getMemberInfo($from_user);
                $unionid = $wx_base_info['unionid'] ?? '';
                $nickname = $wx_base_info['nickname'] ?? '';



                $class_active_list = "https://top.zhan.com/yunying/wechat/public-class/active-list.html?source_type=subscribe";//公开课报名列表页
                $chapter_active_list = "https://top.zhan.com/yunying/wechat/chapter/active-list.html?source_type=subscribe";//战团活动报名列表页
                $jijing_list = "http://m.zhan.com/wechatbeikao/tfjijing/?source_type=subscribe";//机经列表页
                $edit_info = "https://top.zhan.com/yunying/wechat/user/edit-info.html?source_type=subscribe";//个人考试信息页

//                $url = Url::toRoute(['wechat/user/my-center'], true);
//                $content = "[爱心]欢迎关注小站备考[爱心]\n从现在开始，我们一起在小站备考屠鸭考托吧！快戳下方菜单栏玩转备考:\n
//[勾引]\n 戳<a href=\"$url\">【萌新区】</a>一手机经备考资料熟悉考试，看公开课积累技巧\n
//戳<a href=\"$url\">【实战区】</a>名师带队提分战团，每日学习任务助力考试提分\n[爱心]想了解更多2018考试时间、名校排名以及雅思\托福分数要求等请回复【考试时间】、【大学排名】关键词获取哦~";//关注后回复给用户的消息

                $content = "欢迎关注小站备考 ❤提分，我们是有技巧的❤\n
这里是“考托俱乐部，屠鸭战友团”！加入我们的免费提分战团，精英名师天团带你一起备考提分！
戳 👉<a href='$jijing_list'>【下载机经】</a>
*获取一手机经备考资料
戳 👉<a href='$class_active_list'>【学术公开课】</a>
*观看免费福利公开课
戳 👉<a href='$chapter_active_list'>【免费提分战团】</a>
*加入名师带队免费提分活动
戳 👉<a href='$edit_info'>【我的备考信息】</a>
*设置个人考试信息
";
                break;
            case "unsubscribe":
                $content = "";
                break;
            case "TEMPLATESENDJOBFINISH": //模板消息推送结束事件
//                if (!in_array($from_user, self::ALLOW_OPENID_LIST)) {
//                    break;
//                }
                $content = '';
//                $user_push_mode = UserPush::findOne(['msgid' => $request_data['MsgID']]);
//                $user_push_mode->status = $request_data['Status'];
//                $user_push_mode->save();
                break;
        }

        if (!empty($content) && isset($response_type) && $response_type = "text") {
            $result = $this->wechat->transmitText($request_data, $content);
        }
        return $result;
    }

    private function receiveText($request_data)
    {
        $keyword = trim($request_data['Content']);
        $result = 'success';

        switch ($keyword) {
            case "0314": //sat 真题资料
                $response_type = "text";
                $content = "戳 👉<a href='https://lnk0.com/JtgQpc?chn=adwo'>获取SAT资料</a>。\n✨观看更多SAT精品公开课✨请点击底部菜单【萌新区】-【学术公开课】";
                break;
            case "0315": //gre 真题资料
                $response_type = "text";
                $content = "戳 👉<a href='https://lnk0.com/UF1c0o?chn=adwo'>获取GRE资料</a>。\n✨观看更多GRE精品公开课✨请点击底部菜单【萌新区】-【学术公开课】";
                break;
        }

        if (!empty($content) && isset($response_type) && $response_type = "text") {
            $result = $this->wechat->transmitText($request_data, $content);
        }

        return $result;
    }
}
