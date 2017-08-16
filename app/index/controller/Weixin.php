<?php
namespace app\index\controller;
use think\Config;
use think\Env;
use app\model\controller\Index as IndexModel;
use think\Controller;

class Weixin extends Controller
{
    public $publicOpenId = '1000';
    public $publicNickName = '尼克胡哲';
    public $appid = 'wxbc312a582bccb32e';
    public $appsecret = '9ffbd473b9c54dda8697791dba6dff14';

    public function index()
    {
        $timestamp = $_GET['timestamp'];  //时间戳
        $nonce = $_GET['nonce'];    //随机字串
        $token = 'findjoy';         //微信公众号填写的token
        $signature =   $_GET['signature'];  //微信公众平台加密好的一个字串
        $echostr = isset($_GET['echostr'])?$_GET['echostr']:'';
        $array = array($timestamp,$nonce,$token);    //参数放入数组排序
        sort($array);   //PHP自带排序
        //2.将排序后的三个参数拼接之后用sha1加密
        $tmpstr = implode('',$array);//或者join拼接字符串
        $tmpstr = sha1($tmpstr);  //加密
        //3.将加密后的字符串与signature进行对比,判断该请求是否来自微信
        if($tmpstr == $signature && $echostr) {
            // 第一次接入微信 api接口的时候
            echo $echostr;  //从微信传递来的参数
            exit;
        } else {
            $this->reponseMsg();
        }
    }

    // 接收事件推送并回复

    public function reponseMsg()
    {
        // 1.获取到微信推送过来的数据(xml格式)
        $postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
//        $tmpstr  = $postArr;
        // file_get_contents('php://input');
        // 处理消息类型并设置回复类型和内容
        /*<xml>
        <ToUserName><![CDATA[toUser]]></ToUserName>
        <FromUserName><![CDATA[FromUser]]></FromUserName>
        <CreateTime>123456789</CreateTime>
        <MsgType><![CDATA[event]]></MsgType>
        <Event><![CDATA[subscribe]]></Event>
        </xml>*/

        $postObj = simplexml_load_string($postArr);   //xml解析
        // $postObj -> ToUserName = '';
        // $postObj -> FromUserName = '';
        // $postObj -> CreateTime = '';
        // $postObj -> MsgType = '';
        // $postObj -> Event = '';

        // 判断该数据包是否是订阅事件推送
        if( strtolower($postObj->MsgType) == 'event') {
            //如果是 subscribe关注事件
            if( strtolower($postObj->Event) =='subscribe') {   //小写函数
                //回复用户消息 ,单图文格式
                $content = '公众账号 ToUserName: ' . $postObj->ToUserName . "- \n
                            微信用户 FromUserName: " . $postObj->FromUserName . "- \n
                            转化前的xml: " . $postObj . "- \n
                            转化后的xml是对象: " . $postArr;
                $indexModel = new IndexModel();
                $indexModel->responseSubscribe($postObj, $content);
            }
            //        如果是重扫二维码
            if( strtolower($postObj->Event == 'SCAN') ){
                if($postObj->EventKey == 2000) {
//                    如果是临时二维码扫码
//                    获取到用于制作二维码的openid和nickname昵称  //全局变量实现
//                    $this->getDetailInfo();

//                    $qrcodeLink = "临时二维码欢迎你";   //关注或者扫描二维码后的推送链接
//                    $qrcodeLink='<a href="https://www.baidu.com/">百度</a>';

                    $qrcodeLink='<a href="http://yxgonce.xin/index.php/index/qrcode/qrcode?open='.$postObj->FromUserName.'&nickName='.$postObj->ToUserName.'">制作二维码</a>';

//                    $data = array('name' => 'Foo', 'age' => 25);
//                    $data = 'nice to meet you';
//                    curl_setopt($ch, CURLOPT_URL, 'http://yxgonce.xin/index.php/index/Qrcode/qrcode.php');
//                    $url = "http://yxgonce.xin/index.php/index/Qrcode/qrcode.php";
//                    header("http://yxgonce.xin/index.php/index/Qrcode/qrcode.php?user=hello,key=two");
//                    $post_data = array ("username" => "bob","key" => "12345");
//                    $ch = curl_init();
//                    curl_setopt($ch, CURLOPT_URL, $url);
//                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//                    // post数据
//                    curl_setopt($ch, CURLOPT_POST, 1);
//                    // post的变量
//                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
//                    $output = curl_exec($ch);
//                    curl_close($ch);

                }
                if($postObj->EventKey == 3000) {
//                    如果是永久二维码扫码
                    $qrcodeLink = "永久二维码欢迎你";
                }

                $indexModel = new IndexModel();
                $indexModel->responseSubscribe($postObj,$qrcodeLink);
            }

        }

        // 用户发送图文关键字的时候,回复一个单图文
        if( strtolower($postObj->MsgType) == 'text' && trim($postObj->Content) == 'tuwen' ) {
//          从数据库中获取
            $arr = array(
                array(
                    'title'=>'imooc',
                    'descripition'=>'imooc is very cool',
                    'picUrl'=>'http://img.netbian.com/file/2017/0718/b93ec99b9cf275ebe7bf52932f5d5493.jpg',
                    'url'=>'https://www.imooc.com',
                )
            );
//            实例化模型
            $indexModel = new IndexModel();
            $indexModel-> responseMsgImg($postObj,$arr);
        } else {
            switch( trim($postObj->Content) ) {
                case 1:
                    $Content = '输入的数字是1';
                    break;
                case 2:
                    $Content = '输入的数字是2';
                    break;
                case 3:
                    $Content = '输入的数字是3';
                    break;
                case '英文':
                    $Content = 'nice to meet you';
                    break;
                case 4:
                    $Content = '<a href="https://www.baidu.com/">百度</a>';
                    break;
                case 5:
                    $Content = '微信sdk is very useful';
                    break;
                default :
                    $Content = '没有找到相关信息';
                    break;
            }
//          实例化模型
            $indexModel = new IndexModel();
            $indexModel->responseMsgText($postObj,$Content);
        }

    }

    public function http_curl($url, $type = 'get', $res = 'json', $arr = '')
    {

        //1.初始化curl
        $ch = curl_init();
        //2.设置curl的参数
        if ($type == 'post') {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
        } else {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        }
        //3.采集
        $output = curl_exec($ch);

//        var_dump($url);
//
//        var_dump($ch);
//
//        var_dump(curl_error($ch));
//
//        var_dump(curl_errno($ch));

        if ($res == 'json') {
            if (curl_errno($ch)) {
                //请求失败，返回错误信息
                return curl_error($ch);
            } else {
                //请求成功，返回信息
//                var_dump($output);
//                var_dump(json_decode($output, true));
                return json_decode($output, true);

                //4.关闭
                curl_close($ch);
            }
        }
        echo var_dump($output);
    }

    public function demo()
    {
        echo "hell0";
    }

//  获取微信AccessToken
    function getWxAccessToken() {
        session_start();
//        $appid = 'wxbc312a582bccb32e';
//        $appsecret = '9ffbd473b9c54dda8697791dba6dff14';
//        //1.请求url地址,开发手册获取
//        $ch = curl_init();
//        $url =  'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
//        //2.设置curl参数
//        curl_setopt($ch,CURLOPT_URL,$url);
//        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//        //3.采集
//        $res = curl_exec($ch);
//
////      关闭一个curl会话并且释放所有资源，curl句柄ch也会被释放，后面再使用$ch时就会报错。
////      所以curl_errno($ch),curl_error($ch)需要在curl_close($ch)之前（测试后可行）
//        if(curl_errno($ch))
//        {
//            echo 'Curl error: ' . curl_error($ch);
//        }
//
//        //4.关闭
//        curl_close($ch);
//
//        $arr = json_decode($res,true);
//        var_dump($arr);

        if ( isset($_SESSION['access_token']) && $_SESSION['expire_time'] > time()) {
            //如果session中存在access_token,而且过期时间大于当前时间,从session中取得
            return $_SESSION['access_token'];

        } else {
            //session中不存在,或者已过期
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $this->appid . "&secret=" . $this->appsecret;
            $res = $this->http_curl($url, 'get', 'json');
            $access_token = $res['access_token'];
            //将重新获得的access_tooken存到session中
            $_SESSION['access_token'] = $access_token;
            $_SESSION['expire_time'] = time() + 7000;
            return $access_token;
        }
    }

//   获取服务器地址
    function getWxServerIp() {

//        $accessToken = '-th2JpAk8pCXJ4STkT64wfUJss9yNcxeHuhlBRqaLiq39gXU6mMCjCvT-T6UuBig6Cuar5oHu6bCQZYZOl8DQp4ee0JlHUZ62vMsBJAFtW0b7ggPxXSVWbGKtiNACe7ZDMGhAEAIRL';
        $accessToken = $this->getWxAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token='.$accessToken;

        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $res = curl_exec($ch);
        if (curl_errno($ch)) {
            echo curl_error($ch);
        }
        curl_close($ch);
        $arr = json_decode($res,true);
        echo '<pre>';
        var_dump($arr);
        echo '</pre>';

    }

    function geBaseInfo()
    {
        //1.获取code
        //由于变量是url,而这个变量还需要放到url中,故转码
        $redirect_uri = urlencode('http://www.yxgonce.xin/index.php/index/Weixin/getUserOpenId');
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='
            . $this->appid . '&redirect_uri=' . $redirect_uri
            . '&response_type=code&scope=snsapi_base&state=123#wechat_redirect';
        //获取用户的openid
        header('location:' . $url);

    }

    function getUserOpenId()
    {
        //2.获取网页授权access_token
        $code = $_GET['code'];
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->appid
            . '&secret=' . $this->appsecret
            . '&code=' . $code . '&grant_type=authorization_code';

        //3.拉取用户的openid
        $res = $this->http_curl($url, 'get');
        $openid = $res['openid'];
        echo '<hr>getUserOpenId<br>';
        var_dump($res);
        echo '<hr>';
        echo '<hr>$openid<br>';
        var_dump($openid);
        echo '<hr>';
    }

    //获取用户详细信息
    function getDetailInfo()
    {
        //1.获取code
        //由于变量是url,而这个变量还需要放到url中,故转码
        $redirect_uri = urlencode('http://www.yxgonce.xin/index.php/index/Weixin/getUserInfo');
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='
            . $this->appid . '&redirect_uri=' . $redirect_uri
            . '&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect';
        //获取用户的openid
        $res = header('location:' . $url);
    }

    function getUserInfo()
    {
        header('content-type:text/html;charset=utf-8');
        //2.获取网页授权access_token
        $code = $_GET['code'];
//        https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->appid
            . '&secret=' . $this->appsecret
            . '&code=' . $code . '&grant_type=authorization_code';
        $res = $this->http_curl($url, 'get');
        $openid = $res['openid'];
        $access_token = $res['access_token'];
        //3.拉取用户详细信息
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='
            . $access_token . '&openid=' . $openid . '&lang=zh_CN';
        $res = $this->http_curl($url);
//        echo '<hr>getUserInfo<br>';
//        var_dump($res);
//        echo $openid.'<br>';
//        echo $res['nickname'];
        $this->publicOpenId = $openid;
        $this->publicNickName = $res['nickname'];
        echo $this->publicOpenId;
//        echo '<hr>';

    }

    function shareWX()
    {
        //获取jsapi_ticket票据
        $jsapi_ticket = $this->getJsApiTicket();

        echo '<hr>shareWX $jsapi_ticket<br>';
        var_dump($jsapi_ticket);
        echo '<hr>';
        // 必填，生成签名的时间戳
        $timestamp = time();

        // 必填，生成签名的随机串:signature,需要以下4个参数
        //1.取得随机字符串
        $noncestr = $this->getRandCode();

        echo '<hr>shareWX $noncestr<br>';
        var_dump($noncestr);
        echo '<hr>';

        //2.jsapi_ticket
        //3.timestamp
        //4.url
        $url = 'http://weixin.ipuxin.com/Home/index/shareWX';
        $signature = 'jsapi_ticket=' . $jsapi_ticket
            . '&noncestr=' . $noncestr . '&timestamp='
            . $timestamp . '&url=' . $url;
        echo '<hr>shareWX $signature<br>';
        var_dump($signature);
        echo '<hr>';

        $signature = sha1($signature);
        echo '<hr>shareWX sha1($signature)<br>';
        var_dump($signature);
        echo '<hr>';

        $this->assign('name', 'ipuxin');
        $this->assign('timestamp', $timestamp);
        $this->assign('noncestr', $noncestr);
        $this->assign('signature', $signature);

        $this->display('share');
    }

    //获取jsapi_ticket票据
    function getJsApiTicket()
    {
        if (isset($_SESSION['jsapi_ticket']) && $_SESSION['jsapi_ticket_expire_time'] > time()) {
            $jsapi_ticket = $_SESSION['jsapi_ticket'];
        } else {
            //获取$access_token
            $access_token = $this->getWxAccessToken();
            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $access_token . '&type=jsapi';
            $res = $this->http_curl($url);
            $jsapi_ticket = $res['ticket'];
            //存储到session中
            $_SESSION['jsapi_ticket'] = $jsapi_ticket;
            $_SESSION['jsapi_ticket_expire_time'] = time() + 7000;
        }
        return $jsapi_ticket;
    }

    //生成前面随机串16位-18位都可以
    function getRandCode($num = 16)
    {
        $array = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
        ];
        $tmpstr = '';
        $max = count($array);
        for ($i = 1; $i <= $num; $i++) {
            $key = rand(0, $max - 1);
            $tmpstr .= $array[$key];
        }
        return $tmpstr;
    }

    //微信生成临时二维码

//    function getQrCode()
//    {
//        header('content-type:text/html;charset=utf-8');
//        //全局票据:access_token 网页授权access_token 微信JS接口的临时票据jsapi_ticket
//        //1.获取ticket票据
//        $access_token = $this->getWxAccessToken();
//        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $access_token;
//
//        echo '<hr>getQrCode $url<br>';
//        var_dump($url);
//        echo '<hr>';
//
//        /*{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}*/
//        $postArr = [
//            'expire_seconds' => 604880,//24*60*60*7   7天
//            'action_name' => 'QR_SCENE',
//            'action_info' => [
//                'scene' => ['scene_id' => 2000]
//            ]
//        ];
//        $postJson = json_encode($postArr);
//        $res = $this->http_curl($url, 'post', 'json', $postJson);
//
//        $ticket = $res['ticket'];
//
//        echo '<hr>getQrCode $ticket<br>';
//        var_dump($ticket);
//        echo '<hr>';
//
//        //url中就是一个二维码图片
//        $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket);
//
//        echo '<hr>getQrCode 临时二维码<br>';
//        echo '<img src="'.$url.'"/>';
//        echo '<hr>';
//    }

    function getTimeQrCode() {
        header('content-type:text/html;charset=utf-8');
//        1.获取票据
//        全局票据access_token 网页授权票据acess_token  微信js-sdk jsapi_ticket
        $access_token = $this->getWxAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$access_token;
//        {"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}
        $postArr = array(
            'expire_seconds' =>604800,//24*60*60*7
            'action_name'=>"QR_SCENE",
            'action_info'=>array(
                'scene'=>array('scene_id'=>2000),
            )
        );
        $postJson = json_encode( $postArr );
        $res = $this->http_curl($url,'post','json',$postJson);
        var_dump($res);
        $ticket = $res['ticket'];
//        2.使用ticket获取二维码图片
        $url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket);
        $res = $this->http_curl($url);
//        var_dump($res);
//        echo "临时二维码";
        echo "<img src='".$url."'/>";
    }

//    永久二维码
    function getForverQrCode() {
        header('content-type:text/html;charset=utf-8');
//        1.获取票据
//        全局票据access_token 网页授权票据acess_token  微信js-sdk jsapi_ticket
        $access_token = $this->getWxAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$access_token;
//        {"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": 123}}}

        $postArr = array(
            'action_name'=>"QR_SCENE",
            'action_info'=>array(
                'scene'=>array('scene_id'=>3000),
            )
        );
        $postJson = json_encode( $postArr );
        $res = $this->http_curl($url,'post','json',$postJson);
        var_dump($res);
        $ticket = $res['ticket'];
//        2.使用ticket获取二维码图片
        $url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket);
        $res = $this->http_curl($url);
        echo "永久二维码";
        echo "<img src='".$url."'/>";
    }

    //微信生成永久二维码
//    function getForeverQrCode()
//    {
//        header('content-type:text/html;charset=utf-8');
//        //全局票据:access_token 网页授权access_token 微信JS接口的临时票据jsapi_ticket
//        //1.获取ticket票据
//        $access_token = $this->getWxAccessToken();
//        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $access_token;
//
//        var_dump($url);
//
//        /*{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": 123}}}*/
//        $postArr = [
//            'action_name' => 'QR_SCENE',
//            'action_info' => [
//                'scene' => ['scene_id' => 3000]
//            ]
//        ];
//        $postJson = json_encode($postArr);
//        $res = $this->http_curl($url, 'post', 'json', $postJson);
//
//        $ticket = $res['ticket'];
//
//        var_dump($ticket);
//
//        //url中就是一个二维码图片
//        $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket);
//
//        echo '<hr>getQrCode 永久二维码<br>';
//        echo '<img src="'.$url.'"/>';
//    }


}
